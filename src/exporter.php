<?php

namespace ex;

use \ex\options;
use \ex\content;
use \ex\debug;

class exporter
{
    
    use debug;

    public $options;

    public $results;

    public $_export_key;

    public $job_id;
    
    public $running_from_action;


    public function __construct()
    {
        set_time_limit(600); // 10 mins - apache Timeout = 300 (5 mins)
    }

    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_job_id($job_id)
    {
        $this->job_id = $job_id;
    }


    public function run()
    {

        if ($this->is_save_only()){ return; }

        $this->clearlogs();

        // loop over each export instance.
        foreach ($this->options as $this->_export_key => $current_export) {

            // has this export been enabled?
            if ($this->options[$this->_export_key]['ex_job_group']['ex_job_enabled'] != true) { continue; }

            // run it.
            $this->process_single_export();
        }

        return;
    }


    /**
     * run_single_job function
     * 
     * Run a single job.
     *
     * @return void
     */
    public function run_single_job()
    {
        if (empty($this->job_id)){ return; }

        $this->clearlogs();
        
        $this->running_from_action = true;

        foreach ($this->options as $this->_export_key => $current_export) {

            if (!is_int($this->_export_key)){ continue; }
            if ($this->options[$this->_export_key]['ex_job_group']['ex_job_id'] != $this->job_id) { continue; }

            $this->process_single_export();
        }

        return $this->results;
    }



    private function process_single_export()
    {
        $this->run_class('ex\content');

        $this->run_class('ex\export');

        $this->run_class('ex\housekeep');

        // $this->run_class('ex\schedule');
    }




    private function run_class($classname)
    {
        $class = new $classname;
        $class->set_options($this->options[$this->_export_key]);
        $class->set_auth($this->options['auth']);
        $class->set_collection($this->results);
        $this->results[$classname] = $class->run();
    }


    

    private function is_save_only()
    {
        return $this->options['saveonly'];
    }



    private function clearlogs()
    {
        $this->debug_clear('content');
        $this->debug_clear('export');
    }


}