<?php

// ┌───────────────────────────────────────────────────────────────────────────┐
// │Explanations at:                                                           │
// │                                                                           │
// │- https://dev.to/ioroot/google-oauth-wordpress-youtube-service-api-4ko6    │
// │                                                                           │
// │- https://ioroot.com/wordpress-oauth-and-ajax/                             │
// │                                                                           │
// │- https://github.com/IORoot/wp-plugin__oauth-demo                          │
// │                                                                           │
// └───────────────────────────────────────────────────────────────────────────┘

class ex_youtube
{
    use \ex\debug;

    private $options;

    private $data;

    private $results;

    private $client;

    private $service;

    private $error;



    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

    public function get_error()
    {
        return $this->error;
    }


    public function run()
    {
        if ($this->noToken()){ return; }
        $this->set_client();
        if ($this->noClient()){ return; }
        $this->do_requests();
    }


    private function set_client()
    {
        try {
            $client = new oauth_google_client();
            $client->set_token_name('YT_OAUTH_REFRESH_TOKEN');
            $client->set_scope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
            $client->run();
            $this->client = $client->get_client();
        } catch (Exception $e) {
            $this->error = 'Error: Google Client Error. Returned : ' . print_r($e->getMessage(), true);
            $this->debug('export', print_r($e->getMessage(), true));
        }
        
    }


    private function do_requests()
    {
        if (empty($this->options["post_types_youtube"])){ 
            $this->error = 'Warn: No YouTube export instances found.';
            return; 
        }

        foreach ($this->options["post_types_youtube"] as $this->request_type)
        {
            $this->run_youtube_request();
        }
    }

    private function run_youtube_request()
    {
        $request_name = '\\ex\\exporter\\youtube\\' . $this->request_type['acf_fc_layout'];
        $this->request = new $request_name;
        $this->request->set_options($this->request_type);
        $this->request->set_data($this->data);
        $this->request->set_client($this->client);
        $this->request->run();
        $this->results[] = $this->request->get_result();
    }

    
    private function noClient()
    {
        if ($this->client == null)
        {
            $this->error = 'Error: No Google Client created.';
            return true;
        }
        return false;
    }

    private function noToken()
    {
        if (get_transient('YT_OAUTH_REFRESH_TOKEN') == false)
        {
            $this->error = 'Error: No transient YT_OAUTH_REFRESH_TOKEN found.';
            return true;
        }
        return false;
    }


}
