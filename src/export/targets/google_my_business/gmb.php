<?php


class ex_google_my_business
{

    private $options;

    private $data;

    private $results;

    private $client;

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

    public function get_results()
    {
        return $this->results;
    }

    public function get_client()
    {
        return $this->client;
    }


    public function run()
    {
        if ($this->noToken()){ return false; }
        $this->set_client();
        if ($this->noClient()){ return false; }
        $this->do_requests();
        if (empty($this->results)){ return false; }
        
        return true;
    }



//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                                                                         │░
//  │                                                                         │░
//  │                                 PRIVATE                                 │░
//  │                                                                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    private function set_client()
    {
        $client = new oauth_google_client();
        $client->set_token_name('GMB_OAUTH_REFRESH_TOKEN');
        $client->set_scope('https://www.googleapis.com/auth/business.manage');
        $client->run();
        $this->client = $client->get_client();
    }




    private function do_requests()
    {
        foreach ($this->options["post_types_google_my_business"] as $this->request_type)
        {
            $this->run_gmb_request();
        }
    }

    private function run_gmb_request()
    {
        $request_name = '\\ex\\exporter\\gmb\\' . $this->request_type['acf_fc_layout'];
        $this->request = new $request_name;
        $this->request->set_options($this->request_type);
        $this->request->set_data($this->data);
        $this->request->set_client($this->client);
        $this->request->run();
        $this->results[] = $this->request->get_result();
    }


    /**
     * noTokens function
     *
     * Check to see if there are tokens.
     * 
     * @return void
     */
    private function noClient()
    {
        if ($this->client == null)
        {
            $this->error = 'A google client cannot be created from class oauth_google_client.';
            return true;
        }
        return false;
    }
    
    private function noToken()
    {
        $transient = get_transient('GMB_OAUTH_REFRESH_TOKEN');

        if ($transient == false) { 
            $this->error = 'GMB_OAUTH_REFRESH_TOKEN Transient not set.';
            return true; 
        }
        return false;
    }
}
