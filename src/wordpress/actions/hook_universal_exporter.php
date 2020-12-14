<?php

namespace ue\action;

class universal_exporter {


    public function __construct()
    {
        add_action( 'universal_exporter', array($this,'run_exporter'), 10, 2);
    }

    
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘

    public function run_exporter($job_id, $label = null){

        $ex = new \ex\exporter;
        $options = (new \ex\options)->get_options();
        $ex->set_options($options);
        $ex->set_job_id($job_id);
        $results = $ex->run_single_job();

        return $results;
    }
    
}