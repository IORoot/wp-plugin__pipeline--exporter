<?php

namespace andyp\housekeeper;

class housekeep
{

    private $options;

    private $action_classname;

    private $action;

    private $result;


    public function set_options($options)
    {
        $this->options = $options;
    }


    public function get_result()
    {
        return $this->result;
    }

    public function run()
    {
        if (!$this->check_inputs()){ return; };

        $this->instantiate_action();

        if (!$this->check_action_classname()){ return; };

        $this->run_action();
    }

    /**
     * Check all inputs.
     * 
     * Checks that everything has been set that is required.
     */
    private function check_inputs()
    {
        if ($this->options == 'none') { return false; }
        if (!isset($this->options) ) { return false; }
        if (!isset($this->options['enabled'])) { return false; }
        if (!isset($this->options['action'])) { return false; }
        if (!isset($this->options['query'])) { return false; }
        if ($this->options['enabled'] == false) { return false; }

        return true;
    }

    /**
     * Create a new action object.  
     */
    private function instantiate_action()
    {
        $this->action_classname = '\\andyp\\housekeeper\\action\\'.$this->options['action'];
    }


    /**
     * Check the action object is a known one.
     */
    private function check_action_classname()
    {
        if (!class_exists($this->action_classname)){ return false; }
        return true;
    }

    /**
     * Run the action.
     */
    private function run_action()
    {
        $this->action = new $this->action_classname;
        $this->action->wp_query($this->options['query']);
        $this->action->run();
        $this->result = $this->action->result();
    }

}
