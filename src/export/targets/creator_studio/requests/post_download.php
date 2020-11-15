<?php

namespace ex\exporter\creator_studio\requests;

trait post_download
{

    private function post_download($url = null, $file = null)
    {
        if ($url == null || $file == null){ return; }

        try {

            $method = 'POST';
            $uri = '/vd?' . http_build_query($this->query);
            
            $json = [ 
                "url"  => $url, 
                "file" => $file 
            ];

            $response = $this->client->request( $method, $uri, [ 'json' => $json ] );

            $this->results['vd'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }

    
}