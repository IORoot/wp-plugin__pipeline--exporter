<?php

namespace ex;

class housekeep
{

    public $options;

    public $housekeep_options;

    public $results;


    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_auth($auth){ }

    public function set_collection($collection){ }


    public function run()
    {
        $this->conform_options();

        if (empty($this->housekeep_options)){ return; }

        $this->run_housekeeper();
        
        return $this->results;
    }

    /**
     * run_housekeeper function
     * 
     * Runs the andyp\housekeeper\housekeep standalone class
     * that cleans up the DB
     *
     * @return void
     */
    private function run_housekeeper()
    {
        $classname = '\\andyp\\housekeeper\\housekeep';
        $scheduler = new $classname;
        $scheduler->set_options($this->housekeep_options);
        $scheduler->run();
        $this->results['ue\\housekeep'] = $scheduler->get_result();
    }


    private function conform_options()
    {
        if ($this->options['ue_job_housekeep_id'] == 'none'){ return; }

        $this->housekeep_options['enabled'] = $this->options['ue_job_housekeep_id']['ue_housekeep_group']['ue_housekeep_enabled'];
        $this->housekeep_options['action']  = $this->options['ue_job_housekeep_id']['ue_housekeep_action'];
        $this->housekeep_options['query']   = $this->options['ue_job_housekeep_id']['ue_housekeep_query'];
    }


}