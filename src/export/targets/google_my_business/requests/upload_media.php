<?php

namespace ex\exporter\gmb;

class upload_media
{

    use \ex\debug;

    private $media;

    private $client;

    private $service;



    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_client($client)
    {
        $this->client = $client;
    }

    public function run()
    {
        $this->upload_curl();
        $this->process_result();
        return $this->media;
    }




    private function upload_curl()
    {

        try {

            $token = $this->client->getAccessToken();
            
            $data = array(
                'mediaFormat' => $this->options["settings"]["media_type"],
                'locationAssociation' => array(
                    "category" => $this->options["settings"]["media_category"]
                ),
                'sourceUrl' => $this->options["settings"]["media_source_url"]
            );
            $json = json_encode($data);
            $url = 'https://mybusiness.googleapis.com/v4/'.$this->options["locationid"].'/media';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json',
                'Authorization: Bearer ' . $token['access_token'] 
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $this->upload = curl_exec($ch);
            curl_close ($ch);

            $this->debug('export', $this->upload);

        } 
        catch (\Google_Service_Exception $e) {
            $this->results = 'Caught \Google_Service_Exception: ' .  print_r($e->getMessage(), true) . "\n" . 'Request was: ' . print_r($this->localPost,true);
        }
        catch (\Exception $e) {
            $this->results = 'Caught \exception: ' .  print_r($e->getMessage(),true) . "\n" . 'Request was: ' . print_r($this->localPost, true);
        }

    }



    private function process_result()
    {
        $this->upload = json_decode($this->upload);
        $this->media = new \Google_Service_MyBusiness_MediaItem();
        $this->media->setSourceUrl($this->upload->googleUrl);
        $this->media->setMediaFormat($this->upload->mediaFormat);
    }



}