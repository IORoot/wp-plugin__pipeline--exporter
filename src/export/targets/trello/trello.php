<?php


class ex_trello
{
    use \ex\debug;

    private $options;

    private $data;

    private $results;

    private $api_key;

    private $token;

    private $client;

    private $board;

    private $list;


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
        $this->get_auth();
        $this->get_guzzle_client();
        $this->create_cards();
        return $this->results;
    }


    private function get_auth()
    {
        foreach ($this->options['auth'] as $key => $auth)
        {
            if ($auth['acf_fc_layout'] == 'trello')
            {
                $this->api_key = $auth['trello_api_key'];
                $this->token   = $auth['trello_token'];
            }
        }
        
    }



    private function get_guzzle_client()
    {

        $this->client = new GuzzleHttp\Client();

    }



    private function create_cards()
    {
        foreach ($this->options["post_types_trello"] as $this->card_id => $this->card)
        {
            $this->parse_moustaches();
            $this->create_card();
            $this->update_card_with_custom_fields();
            $this->debug('export', $this->results);
        }
    }



    private function create_card()
    {
        $headers = array(
            'Accept' => 'application/json'
        );
        
        $query = array(
            'key'       => $this->api_key,
            'token'     => $this->token,
            'idList'    => $this->card['location']['list'],
            'name'      => $this->card['details']['name'],
            'desc'      => $this->card['details']['description'],
            'due'       => $this->card['details']['due_date'],
            'idLabels'  => implode(',',$this->card['details']['labels']),
            'urlSource' => $this->card['details']['source_url'],
        );

        $request = "https://api.trello.com/1/cards?" . http_build_query($query);

        try {
            $response = $this->client->request(
                'POST', 
                $request,
                $headers
            );
        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }
        

        $this->results[] = json_decode($response->getBody()->getContents());

    }


    private function update_card_with_custom_fields()
    {
        if (!isset($this->card['details']['custom_fields'])){ return; }

        foreach($this->card['details']['custom_fields'] as $cf_key => $custom_field)
        {

            $headers = array(
                'Accept' => 'application/json'
            );

            $query = array(
                'key'       => $this->api_key,
                'token'     => $this->token,
            );

            $body = array(
                        "value" => array(
                            "text" => $custom_field['value']
                        )
                    );

            $request = 'https://api.trello.com/1/card/'.$this->results[0]->id.'/customField/'.$custom_field['field'].'/item?' . http_build_query($query);

            try {
                $response = $this->client->request(
                    'PUT',
                    $request,
                    ['json' => $body]
                );
            } catch (\Exception $e) {
                $this->debug('export', print_r($e->getMessage(), true));
            }

            $this->results[] = json_decode($response->getBody()->getContents());
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
        foreach($this->data as $key => $post)
        {
            $parse = new \ex\parse\replace_moustaches_in_array($post, $this->card);
            $this->card = $parse->get_results();
        }
        
    }

}
