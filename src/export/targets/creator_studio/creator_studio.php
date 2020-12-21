<?php


class ex_creator_studio
{
    use \ex\debug;
    use \ex\utils;

    use \ex\exporter\creator_studio\requests\get_clear_log;

    use \ex\exporter\creator_studio\requests\get_clear_screenshots;
    
    use \ex\exporter\creator_studio\requests\get_debug_log;

    use \ex\exporter\creator_studio\requests\get_status;

    use \ex\exporter\creator_studio\requests\post_clear_cookies;

    use \ex\exporter\creator_studio\requests\post_download;

    use \ex\exporter\creator_studio\requests\post_schedule;


    private $options;

    private $data;

    private $current_key;
    private $current_post;

    private $results;


    // Guzzle options
    private $client;

    private $headers = [];

    private $url;

    private $query;

    private $json;

    private $post;




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
        $this->set_auth();
        $this->set_url();
        $this->set_headers();
        $this->set_apikey();
        $this->set_guzzle_client();

        $this->iterate_data();
        
        return;
    }

    public function get_results()
    {
        return $this->results;
    }



    private function set_auth()
    {
        foreach ($this->options['auth'] as $auth) {
            if ($auth['acf_fc_layout'] != 'igs') {
                continue;
            }

            $this->options['auth'] = $auth;
        }
    }


    private function set_url()
    {
        $this->url = 'http://' . $this->options['auth']['ip_address'];
    }




    private function set_headers()
    {
        $this->headers = array(
            'Accept' => 'application/json'
        );
    }



    private function set_guzzle_client()
    {
        if ($this->url == '') {
            return;
        }

        $this->client = new \GuzzleHttp\Client(['base_uri' => $this->url]);
    }


    private function set_apikey()
    {
        $this->query['apikey'] = $this->options['auth']['igs_api_key'];
    }



    /**
     * Iterate through each post of the collection
     * exporting to each target definition.
     */
    private function iterate_data()
    {
        foreach ($this->data as $this->current_key => $this->current_post) {
            $this->parse_moustaches();

            $this->iterate_targets();
        }
    }



    private function iterate_targets()
    {

        // Clear the log
        $this->get_clearlog();

        // Clear screenshots
        $this->get_clear_screenshots();

        foreach ($this->options["post_types_creator_studio"] as $this->post) {

            // check enabled
            if ($this->post['enabled'] == false) {
                continue;
            }

            // remove the cookie file & re-login.
            $this->post_clear_cookies();

            // Video Download
            $this->post_download($this->post['content_url'], './videos/' . $this->post["video_filename"]);

            // wait until finished.
            $this->wait_for_complete();

            // Image Download
            $this->post_download($this->post['cover_image_url'], './images/' . $this->post["image_filename"]);

            // wait until finished.
            $this->wait_for_complete();

            // Schedule Post
            $this->post_schedule();

            // wait until finished.
            $this->wait_for_complete();

            // Get debug log
            $this->get_debug_log();
        }
    }



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
        /**
         * TODO - Not doing the flat array.
         */
        $parse = new \ex\parse\replace_moustaches_in_array($this->data, $this->options["post_types_creator_studio"], true);
        $this->options["post_types_creator_studio"] = $parse->get_results();
    }



    private function wait_for_complete()
    {
        $this->get_status();

        while ($this->results['status'] == 'running') {
            sleep(5);
            $this->get_status();
        }

        if ($this->results['status'] == 'error') {
            $this->debug('export', print_r('Error on get_status', true));
            return;
        }
    }
}