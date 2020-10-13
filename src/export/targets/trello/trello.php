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
        $this->get_board();
        
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



    private function get_board()
    {

        $headers = array(
            'Accept' => 'application/json'
        );
        
        $query = array(
            'key'    => $this->api_key,
            'token'  => $this->token
        );

        $request = "https://api.trello.com/1/members/me/boards?" . http_build_query($query);

        $response = $this->client->request(
            'GET', 
            $request
        );


        $this->board = json_decode($response->getBody()->getContents());

    }


    



}
