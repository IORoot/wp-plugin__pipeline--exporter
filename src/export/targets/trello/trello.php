<?php


class ex_trello
{
    use \ex\debug;

    private $options;

    private $data;

    private $results;

    private $auth;

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
        $this->create_cards();
        return $this->results;
    }


    private function get_auth()
    {
        foreach ($this->options['auth'] as $key => $auth)
        {
            if ($auth['acf_fc_layout'] == 'trello')
            {
                $this->auth['api_key'] = $auth['trello_api_key'];
                $this->auth['token']   = $auth['trello_token'];
            }
        }
        
    }


    private function create_cards()
    {
        foreach ($this->options["post_types_trello"] as $id => $this->card)
        {
            $this->run_trello_request();
        }
    }
    

    private function run_trello_request()
    {
        $request_name = '\\ex\\exporter\\trello\\' . $this->card['acf_fc_layout'];
        $this->request = new $request_name;
        $this->request->set_options($this->card);
        $this->request->set_data($this->data);
        $this->request->set_auth($this->auth);
        $this->request->run();
        $this->results[] = $this->request->get_result();
    }


}
