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

    private $card;


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
        
        return;
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
            $this->create_card();
        }
    }



    private function create_card()
    {
        $headers = array(
            'Accept' => 'application/json'
        );
        
        $query = array(
            'key'    => $this->api_key,
            'token'  => $this->token,
            'idList' => $this->card['location']['list'],
            'name'   => $this->card['details']['name'],
            'desc'   => $this->card['details']['description'],
            'due'    => $this->card['details']['due_date'],
        );

        $request = "https://api.trello.com/1/cards?" . http_build_query($query);

        $response = $this->client->request(
            'POST', 
            $request
        );

        $this->card = json_decode($response->getBody()->getContents());
    }


    



}
