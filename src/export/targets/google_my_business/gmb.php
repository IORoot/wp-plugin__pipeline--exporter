<?php

// namespace ex\exporter;

class ex_google_my_business
{
    
    use \ex\debug;
    
    private $options;

    private $data;

    private $results;

    private $client;

    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_data($data)
    {
        $this->data = $data;
    }


    public function run()
    {
        $this->set_client();
        if ($this->noClient()){ return; }
        $this->do_requests();
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
        foreach ($this->options["ex_cta_universal_exporter_google_my_business_posts"] as $this->request_type)
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
            return true;
        }
        return false;
    }
    
}
