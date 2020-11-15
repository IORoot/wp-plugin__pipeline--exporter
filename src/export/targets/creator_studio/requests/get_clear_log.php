<?php

namespace ex\exporter\creator_studio\requests;

trait get_clear_log 
{


    private function get_clearlog()
    {

        try {

            $method = 'GET';
            $uri = '/clearlog?' . http_build_query($this->query);

            $response = $this->client->request( $method, $uri );

            $this->results['clearlog'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }

    
}