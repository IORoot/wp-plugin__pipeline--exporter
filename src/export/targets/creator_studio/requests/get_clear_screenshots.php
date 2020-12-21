<?php

namespace ex\exporter\creator_studio\requests;

trait get_clear_screenshots 
{


    
    private function get_clear_screenshots()
    {

        try {

            $method = 'GET';
            $uri = '/clearscreenshots?' . http_build_query($this->query);

            $response = $this->client->request( $method, $uri );

            $this->results['status'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }

    
}