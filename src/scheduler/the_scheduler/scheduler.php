<?php

namespace andyp\scheduler;

class schedule
{


    private $options;

    /**
     * existing_event variable
     * 
     * Contains timestamp or FALSE of next event for 
     * filter/params combo
     * 
     * example:
     * 
     * [ 
     *      'next_scheduled' => 1893456000 
     * ]
     *
     * @var array
     */
    private $existing_event;


    /**
     * set_options($options)
     * 
     * Following example data array is required for options:
     * 
     *   [
     *      'enabled' => true,
     *      'repeats' => '1hour',
     *      'starts'  => 1607601861,
     *      'hook'    => 'run_this_filter_hook',
     *      'params`  => [
     *          'param1', 'param2', 'param3'
     *      ]
     *  ]
     * 
     * 
     * @return void
     * 
     */
    public function set_options($options)
    {
        $this->options = $options;
    }


    /**
     * get_existing_event
     * 
     * Returns the scheduled event.s
     *
     * @return array
     */
    public function get_event()
    {
        return $this->existing_event;
    }

    /**
     * remove_event
     * 
     * required options: hook, params
     * 
     *  [
     *      'hook'    => 'run_this_filter_hook',
     *      'params`  => [ 'param1', 'param2', 'param3' ]
     *  ]
     *
     * @return void
     */
    public function remove_event()
    {
        // check all inputs
        if (!$this->check_delete_inputs()){ return false; }

        // get next scheduled timestamp
        $this->check_for_existing_event();

        // delete event
        $this->delete_event();
    }


    /**
     * run function
     * 
     *  all options required:
     * 
     *   [
     *      'enabled' => true,
     *      'repeats' => '1hour',
     *      'starts'  => 1607601861,
     *      'hook'    => 'run_this_filter_hook',
     *      'params`  => [
     *          'param1', 'param2', 'param3'
     *      ]
     *  ]
     *
     * @return void
     */
    public function run()
    {

        // check all inputs
        if (!$this->check_insert_inputs()){ return false; }        

        // Is there an existing event?
        $this->check_for_existing_event();
        
        // Has existing event changed?
        $this->update_if_existing_event_changed();

        // If no existing event, create one.
        $this->create_event();

        // check event exists
        $this->check_for_existing_event();

        return true;

    }


    /**
     * check_insert_inputs
     * 
     * Makes sure all required inputs are present for an insert
     *
     * @return bool
     */
    private function check_insert_inputs()
    {
        if (empty($this->options['enabled'])) { return false; }
        if (empty($this->options['repeats'])) { return false; }
        if (empty($this->options['starts'])) { return false; }
        if (empty($this->options['hook'])) { return false; }
        if (empty($this->options['params'])) { return false; }
        if (!is_array($this->options['params'])) { return false; }
        if (!is_int($this->options['starts'])) { return false; }
        return true;
    }

    /**
     * check_inputs
     * 
     * Makes sure all required inputs are present for a delete
     *
     * @return bool
     */
    private function check_delete_inputs()
    {
        if (empty($this->options['hook'])) { return false; }
        if (empty($this->options['params'])) { return false; }
        if (!is_array($this->options['params'])) { return false; }
        return true;
    }



    /**
     * check_for_existing_event
     * 
     * Checks if there is an existing schedule with the same
     * hook name and parameters.
     * 
     * 'hook' will be name of the hook to run
     * 'params' will be any parameters you are passing to the hook.
     * 
     * Together, they make a unique key that is checked on.
     *
     * @return timestamp or false
     */
    private function check_for_existing_event()
    {
        $timestamp = wp_next_scheduled($this->options['hook'], $this->options['params']);
        if ($timestamp){
            $this->existing_event['next_scheduled'] = $timestamp;
        }
    }


    /**
     * check_for_changed_starttime
     * 
     * Returns true if the inputted starttime
     * is different from the one found for the
     * existing scheduled event.
     *
     * @return bool
     */
    public function has_starttime_changed()
    {
        if (empty($this->existing_event['next_scheduled'])) { return false; }

        if ($this->existing_event['next_scheduled'] == $this->options['starts']) {
            return false;
        }

        return true;
    }


    /**
     * update_if_existing_event_changed function
     * 
     * Simple function to update only if the timestamp
     * has changed.
     *
     * @return void
     */
    private function update_if_existing_event_changed()
    {
        if (!$this->has_starttime_changed()){ return; }
        $this->update_schedule(); 
    }

    
    /**
     * update_schedule function
     * 
     * Delete existing event then add the updated one.
     *
     * @return void
     */
    private function update_schedule()
    {
        $this->delete_event();
        $this->create_event();
    }


    /**
     * delete_event function
     *
     * Make sure there is an existing event.
     * Then delete it and unset the paramter
     * holding the timestamp.
     * 
     * @return void
     */
    private function delete_event()
    {
        if (!$this->existing_event['next_scheduled']){ return; }

        wp_clear_scheduled_hook(
            $this->options['hook'], 
            $this->options['params']
        );

        unset($this->existing_event['next_scheduled']);
    }


    /**
     * create_event function
     *
     * Check there isn't any existing events.
     * Then add new one.
     * 
     * @return void
     */
    private function create_event()
    {
        if (!empty($this->existing_event['next_scheduled'])){ return; }

        wp_schedule_event(
            $this->options['starts'], 
            $this->options['repeats'],
            $this->options['hook'], 
            $this->options['params']
        );
    }


}