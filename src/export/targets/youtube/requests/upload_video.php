<?php

namespace ex\exporter\youtube;

class upload_video
{

    use \ex\debug;

    private $options;

    private $data;

    private $results;

    private $client;

    private $service;

    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

    public function set_client($client)
    {
        $this->client = $client;
    }

    public function run()
    {
        
        if ($this->isDisabled()){ return; }
        $this->parse_moustaches();
        if ($this->isBadVideo()){ return; }
        $this->build_video_path();
        $this->build_video_snippet();
        $this->build_video_tags();
        $this->build_video_status();
        $this->build_video();
        $this->build_reliable_settings();
        $this->insert_video();
        if ($this->isFailed()){ return; }

        $this->update_thumbnail();
    }
    
    public function get_result()
    {
        return $this->results;
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
     * parse_moustaches
     * 
     * Substitute any moustaches for real values.
     * Split into two parts {{post_type:field}}
     * Post_type = post, meta, image
     * Field = Any found field.
     *
     * @return void
     */
    private function parse_moustaches()
    {
        foreach($this->data as $key => $post)
        {
            $parse = new \ex\parse\replace_moustaches_in_array($post, $this->options);
            $this->options = $parse->get_results();
        }
        
    }

    private function build_video_path()
    {
        $videoPath = trim($this->options['video_path']);
        $this->videoPath = $videoPath;
    }


    private function build_video_snippet()
    {
        $this->snippet = new \Google_Service_YouTube_VideoSnippet();
        $this->snippet->setTitle(substr($this->options['title'],0,70));
        $this->snippet->setDescription(substr($this->options['description'],0,5000));
        $this->snippet->setCategoryId($this->options['category']);
    }

    private function build_video_tags()
    {
        $tags = explode(',', trim($this->options['tags']));
        $this->snippet->setTags($tags);
    }



    private function build_video_status()
    {
        $this->status = new \Google_Service_YouTube_VideoStatus();
        $this->status->privacyStatus = "public";
        $this->status->setEmbeddable($this->options['embeddable']) ;

        if ($this->options['privacy_status'] != "")
        {
            $this->status->setPrivacyStatus($this->options['privacy_status']);
        }

        if ($this->options['licence'] != "")
        {
            $this->status->setLicense($this->options['licence']) ;
        }

        if ($this->options['publishat'] != "")
        {
            $this->status->setPublishAt($this->options['publishat']);
        }
    }

    private function build_video()
    {
        $this->video = new \Google_Service_YouTube_Video();
        $this->video->setSnippet($this->snippet);
        $this->video->setStatus($this->status);
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
    private function insert_video()
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

            // Create a request for the API's videos.insert method to create and upload the video.
            $this->insertRequest = $this->service->videos->insert("status,snippet", $this->video);

            // Create a MediaFileUpload object for resumable uploads.
            $this->media = new \Google_Http_MediaFileUpload(
                $this->client,
                $this->insertRequest,
                'video/*',
                null,
                true,
                $this->chunkSizeBytes
            );

            $this->media->setFileSize(filesize($this->videoPath));

            // Read the media file and upload it chunk by chunk.
            $this->returned = false;
            $this->handle = fopen($this->videoPath, "rb");

            while (!$this->returned && !feof($this->handle)) {
                $this->chunk = fread($this->handle, $this->chunkSizeBytes);
                $this->returned = $this->media->nextChunk($this->chunk);
            }

            fclose($this->handle);

            // If you want to make other calls after the file upload, set setDefer back to false
            $this->client->setDefer(false);

            // send to debugger.
            $this->debug('export', print_r($this->returned, true));

            $this->results['video'] = $this->returned;
        } 
        catch (\Google_Service_Exception $e) {
            $this->results = 'Caught \Google_Service_Exception: ' .  print_r($e->getMessage(), true) . "\n" . 'Request was: ' . print_r($this->localPost,true);
            $this->debug('export', print_r($e->getMessage(), true));
            $this->debug('export', 'CHECK - Have you run out of quota? INSERTS are 1600 credits! (only about 8 per day).');
        }
        catch (\Exception $e) {
            $this->results = 'Caught \exception: ' .  print_r($e->getMessage(),true) . "\n" . 'Request was: ' . print_r($this->localPost, true);
            $this->debug('export', print_r($e->getMessage(), true));
        }

    }



    private function update_thumbnail()
    {
        if ($this->is_thumbnail_valid() === false){ return; }

        $this->thumbnail = new upload_thumbnail();
        $this->thumbnail->set_imageURL(trim($this->options['thumbnail_path']));
        $this->thumbnail->set_videoId($this->returned['id']);
        $this->thumbnail->set_client($this->client);

        $this->result['thumbnail'] = $this->thumbnail->run();
    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                                 CHECKS                                  │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    private function is_thumbnail_valid()
    {
        if (empty($this->options['thumbnail_path'])){ return false; }

        // are there moustaches?
        if (strpos($this->options['thumbnail_path'], '{{') !== false){ return false; }

        // are there arrows?
        if (strpos($this->options['thumbnail_path'], '->') !== false){ return false; }

        // does file exist?
        if (!file_exists( WP_CONTENT_DIR . '/uploads/' . $this->options['thumbnail_path'])){ return false; }

        return true;
    }


    private function isDisabled()
    {
        if ($this->options['enabled'] == false)
        {
            return true;
        }
        return false;
    }


    private function isBadVideo()
    {
        if ($this->options['video_path'] == "")
        {
            $this->debug('export', 'No Video File.');
            return true;
        }

        $filesize = filesize(trim($this->options['video_path']));
        if ($filesize < 100)
        {
            $this->debug('export', 'Bad Video File. < 100 bytes.');
            return true;
        }

        return false;
    }

    private function isFailed()
    {
        if (!isset($this->returned['id']))
        {
            return true;
        }
        return false;
    }

}