<?php

namespace ex\exporter\trello;

class add_card
{
    use \ex\debug;

    private $options;

    private $data;

    private $auth;

    private $results;


    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

    public function set_auth($auth)
    {
        $this->auth = $auth;
    }

    public function get_result()
    {
        return $this->results;
    }


    public function run()
    {

        if ($this->isDisabled()){ return; }
        

        $this->parse_moustaches();


        $this->get_guzzle_client();


        $this->create_card();


        $this->update_card_with_custom_fields();

    }


    

    /**
     * Create a new Guzzle client to use on the 
     * requests.
     */
    private function get_guzzle_client()
    {
        $this->client = new \GuzzleHttp\Client();
    }



    /**
     * Call the Trello API to create a new card.
     * 
     * This will populate the main parts of a
     * card. 
     * Note that the URLSource can handle video
     * and Images! Pretty cool.
     */
    private function create_card()  
    {
        $headers = array(
            'Accept' => 'application/json'
        );
        
        $query = array(
            'key'       => $this->auth['api_key'],
            'token'     => $this->auth['token'],
            'idList'    => $this->options['trello_list'],
            'name'      => $this->options['name'],
            'desc'      => $this->options['description'],
            'due'       => $this->options['due_date'],
            'idLabels'  => implode(',',$this->options['labels']),
            'urlSource' => $this->options['source_url'],
        );

        $request = "https://api.trello.com/1/cards?" . http_build_query($query);

        try {
            $response = $this->client->request(
                'POST', 
                $request,
                $headers
            );

            $this->results = json_decode($response->getBody()->getContents());

        } catch (\Exception $e) {
            $this->debug('export', print_r($e->getMessage(), true));
        }

        $this->debug('export', $this->results);

    }


    /**
     * Update the newly created card with any
     * custom fields that have been set.
     * Currently, only Text fields are handled.
     * 
     * TODO Need to sort out 'select' fields for the
     * card 'colours' in the scheduled board.
     * Maybe switch from a repeater to flexible
     * content to handle text / selects / dates 
     * etc...
     */
    private function update_card_with_custom_fields()
    {
        if ($this->options['custom_fields'] == false){ return; }

        foreach($this->options['custom_fields'] as $cf_key => $custom_field)
        {

            $headers = array(
                'Accept' => 'application/json'
            );

            $query = array(
                'key'       => $this->auth['api_key'],
                'token'     => $this->auth['token'],
            );

            $body = array(
                        "value" => array(
                            "text" => $custom_field['value']
                        )
                    );

            try {

                $request = 'https://api.trello.com/1/card/'.$this->results->id.'/customField/'.$custom_field['field'].'/item?' . http_build_query($query);

                $response = $this->client->request(
                    'PUT',
                    $request,
                    ['json' => $body]
                );
            } catch (\Exception $e) {
                $this->debug('export', print_r($e->getMessage(), true));
            }

            $custom_fields = json_decode($response->getBody()->getContents());

            $this->debug('export', $custom_fields);
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
        $parse = new \ex\parse\replace_moustaches_in_array($this->data, $this->options, true);
        $this->options = $parse->get_results();
    }


    /**
     * Check if the card is disabled or not.
     */
    private function isDisabled()
    {
        if ($this->options['enabled'] == false)
        {
            return true;
        }
        return false;
    }


}
