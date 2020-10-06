<?php

namespace ex\exporter\youtube;

class upload_thumbnail
{

    use \ex\debug;

    private $imageURL;

    private $imagePath;

    private $videoId;

    private $client;

    private $result;

    private $service;

    public function set_imageURL($imageURL)
    {
        // Normalise path - Need FULL Absolute /var/www path.
        $imagepath = str_replace(WP_HOME, '', trim($imageURL));
        $imagepath = str_replace(ABSPATH, '', $imageURL);
        $imagepath = preg_replace('/.*wp-content/', '', $imagepath);
        $imageurl = WP_HOME . '/wp-content' . $imagepath;
        $imagepath = ABSPATH . 'wp-content' . $imagepath;

        $this->imageURL = $imageurl;
        $this->imagePath = $imagepath;
    }

    public function set_videoId($videoId)
    {
        $this->videoId = $videoId;
    }

    public function set_client($client)
    {
        $this->client = $client;
    }

    public function run()
    {
        $this->build_thumbnails();
        $this->build_reliable_settings();
        $this->insert_thumbnail();
    }
    
    public function get_result()
    {
        return $this->result;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                                 PRIVATE                                 │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    

    /**
     * build_thumbnails
     *
     * https://github.com/youtube/api-samples/blob/master/php/upload_thumbnail.php
     * 
     * @return void
     */
    private function build_thumbnails()
    {
        // Empty string
        if ($this->imageURL == ""){ return; }

        $image_info = getimagesize($this->imagePath);

        $this->thumbnail = new \Google_Service_YouTube_Thumbnail();
        $this->thumbnail->setUrl($this->imageURL);
        $this->thumbnail->setWidth($image_info[0]);
        $this->thumbnail->setHeight($image_info[1]);
        $this->mimeType = $image_info['mime'];

        $this->thumbnailDetails = new \Google_Service_YouTube_ThumbnailDetails();
        $this->thumbnailDetails->setDefault($this->thumbnail);
    }



    private function build_reliable_settings()
    {
        $this->chunkSizeBytes = 1 * 1024 * 1024;
        $this->client->setDefer(true);
    }



    /**
     * Each API provides resources and methods, usually in a chain. These can be 
     * accessed from the service object in the form $service->resource->method(args). 
     * Most method require some arguments, then accept a final parameter of an array 
     * containing optional parameters.
     */
    private function insert_thumbnail()
    {


        /**
         * Each API provides resources and methods, usually in a chain. These can be 
         * accessed from the service object in the form $service->resource->method(args). 
         * Most method require some arguments, then accept a final parameter of an array 
         * containing optional parameters.
         */
        try {
        
            /**
             * Get a new YouTube Object.
             * 
             * Services are called through queries to service specific objects. 
             * These are created by constructing the service object, and passing an 
             * instance of Google_Client to it. Google_Client contains the IO, authentication 
             * and other classes required by the service to function, and the service informs 
             * the client which scopes it uses to provide a default when authenticating a user.
             */
            $this->service = new \Google_Service_YouTube($this->client);

            
            /**
             * Which video are we going to update?
             */
            $this->setRequest = $this->service->thumbnails->set($this->videoId);

            // Create a MediaFileUpload object for resumable uploads.
            $this->media = new \Google_Http_MediaFileUpload(
                $this->client,
                $this->setRequest,
                $this->mimeType,
                null,
                true,
                $this->chunkSizeBytes
            );
            $this->media->setFileSize(filesize($this->imagePath));


            // Read the media file and upload it chunk by chunk.
            $this->status = false;
            $this->handle = fopen($this->imagePath, "rb");

            while (!$this->status && !feof($this->handle)) {
                $this->chunk = fread($this->handle, $this->chunkSizeBytes);
                $this->status = $this->media->nextChunk($this->chunk);
            }

            fclose($this->handle);

            // If you want to make other calls after the file upload, set setDefer back to false
            $this->client->setDefer(false);

            // send to debugger.
            $this->debug('export', $this->status);

            $this->result = $this->status;
        } 
        catch (\Google_Service_Exception $e) {
            $this->result = 'Caught \Google_Service_Exception: ' .  print_r($e->getMessage(), true) . "\n" . 'Request was: ' . print_r($this->localPost,true);
            $this->debug('export', $e->getMessage());
            $this->debug('export', $this->result);
            $this->debug('export', 'CHECK - Has channel got custom thumbnails enabled?');
        }
        catch (\Exception $e) {
            $this->result = 'Caught \exception: ' .  print_r($e->getMessage(),true) . "\n" . 'Request was: ' . print_r($this->localPost, true);
            $this->debug('export', $e->getMessage());
        }

    }

}