<?php

namespace ex;

class schedule
{

    public $options;

    public $results;


    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_auth($auth) { }

    public function set_collection($collection) { }


    public function run()
    {
        $this->cleanup_deleted_schedules();
        $this->loop_schedules();
        return $this->results;
    }



    /**
     * run_scheduler function
     * 
     * Loops through all scheduled starttimes
     *
     * @return void
     */
    private function loop_schedules()
    {   
        $job_id         = $this->options['ex_job_group']['ex_job_id'];
        $schedules      = $this->options['ex_job_schedule_id'];
        $enabled        = $schedules['ex_schedule_group']['ex_schedule_enabled'];
        $schedule_list  = $schedules['ex_schedule_list'];

        /**
         * Loop through each schedule
         */
        foreach($schedule_list as $event)
        {
            $this->event = [
                'enabled' => $enabled,
                'hook'    => 'pipeline_processor',
                'params'  => [ 
                    'job_id'      => $job_id,
                    'label'       => $event['schedule']['schedule_label'] 
                ],    
                'repeats' => $event['schedule']['ex_schedule_repeats'],
                'starts'  => $event['ex_schedule_starts'], 
            ];

            $this->run_scheduler();
        }

    }


    /**
     * run_scheduler function
     * 
     * Runs the andyp\scheduler\sceduler standalone class
     * that schedules an event.
     *
     * @return void
     */
    private function run_scheduler()
    {
        $classname = '\\andyp\\scheduler\\schedule';
        $scheduler = new $classname;
        $scheduler->set_options($this->event);
        $scheduler->run();
        $this->results[$classname][] = $scheduler->get_event();
    }




    /**
     * cleanup_deleted_schedules function
     * 
     * This will create a list of all labels for this job.
     * It will then match them against all registered jobs
     * in the cron.
     * If any are in the cron that are not in the list of
     * current labels, they will be deleted.
     * 
     * @return void
     */
    private function cleanup_deleted_schedules()
    {

        // Get all existing labels
        foreach( $this->options['ex_job_schedule_id']['ex_schedule_list'] as $key => $event)
        {
            $labels[$key] = $event['schedule']['schedule_label'];
        }


        // loop through all existing crons
        foreach (_get_cron_array() as $timestamp)
        {

            // IF not a pipeline_proceessor cron entry.
            if (!array_key_exists('pipeline_processor', $timestamp)){ continue; }

            // get first key (its a unique MD5 hash)
            $event = reset($timestamp['pipeline_processor']);

            // IF job_id doesn't match skip.
            if ($event['args']['job_id'] != $this->options['ex_job_group']['ex_job_id']){ continue; }

            // If label is in the list of existing labels, skip
            if (in_array($event['args']['label'], $labels)){ continue; }

            // Label NOT in list, so delete it.
            wp_clear_scheduled_hook( 'pipeline_processor', $event['args'] );
        }

    }

}