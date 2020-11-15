<?php

namespace ex\exporter\creator_studio\requests;

trait post_schedule
{

    private function post_schedule()
    {

        if ($this->post == null) { return; }

        try {

            $method = 'POST';
            $uri = '?' . http_build_query($this->query);
            
            $this->param_user();
            $this->param_pass();
            $this->param_cookies();
            $this->param_screenshots();

            $this->param_video();
            $this->param_cover();
            $this->param_date();
            $this->param_time();
            $this->param_location();
            $this->param_caption();
            $this->param_crosspost();

            $response = $this->client->request( $method, $uri, [ 'json' => $this->json ] );

            $this->results['schedule'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }



    private function param_user()
    {
        $this->json['user'] = $this->options["auth"]["username"];
    }



    private function param_pass()
    {
        $this->json['pass'] = $this->options["auth"]["password"];
    }



    private function param_cookies()
    {
        $this->json['cookies'] = $this->options["auth"]["cookie_filename"];
    }



    private function param_screenshots()
    {
        $this->json['screenshots'] = $this->options["auth"]["screenshots"];
    }



    private function param_video()
    {
        if (empty($this->options["auth"]["video_file"])){ return; }
        $this->json['video'] = $this->options["auth"]["video_file"];
    }



    private function param_cover()
    {
        if (empty($this->options["auth"]["image_file"])){ return; }
        $this->json['cover'] = $this->options["auth"]["image_file"];
    }



    private function param_date()
    {
            
        if ($this->post["schedule_or_publish"] == "schedule"){
            $today = new \DateTime($this->post["schedule"]);
            $this->json['date'] = $today->format('d/m/Y');
        }

        if ($this->post["schedule_or_publish"] == "specific"){
            $today = \DateTime::createFromFormat('Y-m-d H:i:s', $this->post["specific"]);
            $this->json['date'] = $today->format('d/m/Y');
        }

    }



    private function param_time()
    {

        if ($this->post["schedule_or_publish"] == "schedule"){
            $today = new \DateTime($this->post["schedule"]);
            $this->json['time'] = $today->format('H:i');
        }

        if ($this->post["schedule_or_publish"] == "specific"){
            $today = \DateTime::createFromFormat('Y-m-d H:i:s', $this->post["specific"]);
            $this->json['time'] = $today->format('H:i');
        }

    }



    private function param_location()
    {
        if (empty($this->post['location'])){ return; }
        $this->json['location'] = $this->post['location'];
    }



    private function param_caption()
    {
        if (empty($this->post['post_caption'])){ return; }
        $this->json['caption'] = $this->post['post_caption'];
    }



    private function param_crosspost()
    {
        $this->json['crosspost'] = $this->post['crosspost'];
    }



}