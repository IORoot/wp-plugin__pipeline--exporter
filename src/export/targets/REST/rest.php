<?php


/**
 * Does nothing. The REST outputs are 'always on' which means
 * they are declared in the root exporter.php file with an
 * add_action().
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
