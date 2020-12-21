<?php


/**
 * Does nothing. The REST outputs are 'always on' which means
 * they are declared using composer and a __construct method.
 * 
 * See src/REST/rest.php
 */
class ex_rest
{
    use \ex\debug;

    private $options;

    private $data;

    private $results;

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
        return $this->results;
    }

}
