<?php

namespace ex\exporter\creator_studio\requests;

trait get_debug_log 
{


    private function get_debug_log()
    {

        try {

            $method = 'GET';
            $uri = '/logs/debug.log';

            $response = $this->client->request( $method, $uri );

            $this->results['debug'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }

    
}