<?php

namespace ex\exporter\creator_studio\requests;

trait post_clear_cookies 
{

    private function post_clear_cookies()
    {

        if ($this->post['clear_cookies'] == false){ return; }   

        try {

            $method = 'POST';
            $uri = '/clearcookies?' . http_build_query($this->query);
            
            $json = [ "cookies" => $this->post['cookie_filename'] ];

            $response = $this->client->request( $method, $uri, [ 'json' => $json ] );

            $this->results['clearcookies'] = (string) $response->getBody();

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', print_r($this->results, true));
    }

    
}