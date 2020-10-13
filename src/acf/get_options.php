<?php

namespace ex;

class options
{

    public $repeaters = [ 'job', 'content', 'auth', 'schedule', 'export', 'housekeep'];
    public $prefix = 'ex';
    public $main_repeater = 'job';

    public $instance_name;
    public $group;
    public $key;
    
    public $fields;

    public function __construct()
    {
        $this->get_all_options();
        $this->move_all_instances_to($this->main_repeater);
        $this->add_randoms();
        return;
    }

    public function get_options()
    {
        return $this->fields[$this->main_repeater];
    }

    private function add_randoms()
    {
        // Add save-only from side panel
        $this->fields[$this->main_repeater]['saveonly'] = get_field('ex_save_settings_only', 'option');

        // Add auth details from flexible content.
        $this->fields[$this->main_repeater]['auth'] = $this->fields['auth'];
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                  Get all info from repeater fields                      │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function get_all_options()
    {

        foreach ($this->repeaters as $repeater)
        {
            $this->get_repeater_options($this->prefix.'_'.$repeater.'_instance', $repeater);
        }
        
        return $this;
    }



    public function get_repeater_options($repeater_field_name, $result_parameter)
    {
        $result = have_rows($repeater_field_name, 'option');
        // If field exists as an option
        if (have_rows($repeater_field_name, 'option')) {

            // Go through all rows of 'repeater' genimage_filters
            while (have_rows($repeater_field_name, 'option')): $row = the_row(true);

                $this->get_repeater_row($row, $result_parameter);

            endwhile;
        }
    }



    public function get_repeater_row($row, $result_parameter)
    {

        $this->fields[$result_parameter][] = $row;

        return $this;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │              Move all relevant entries under main field                 │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function move_all_instances_to($main_field)
    {

        $this->main_field = $main_field;

        foreach ($this->fields as $id => $instance) {

            // just do the top level field
            if ($id != $this->main_field)
            {
                continue;
            }

            $this->iterate_through_top_level_array($instance);
        }
        
    }


    public function iterate_through_top_level_array($all_instances)
    {
        foreach ($all_instances as $instance_id => $single_instance) {
            $this->loop_through_all_ids_of_instance($instance_id, $single_instance);
        }
    }




    public function loop_through_all_ids_of_instance($instance_id, $single_instance)
    {
        foreach ($single_instance as $field_id => $field_value) {

            // skip the first field - this is the ex_job_group.
            if (is_array($field_value) )
            {
                continue;
            }

            $this->substitute_id_for_full_array($instance_id, $field_id, $field_value);
        }
    }




    public function substitute_id_for_full_array($instance_id, $field_id, $field_value)
    {

        // remove 'ex_job_' and '_id' from 'ex_job_content_id'
        $this->get_instance_name($field_id);

        // group name ex_content_group
        $this->get_group_name($field_id);
        
        // remove job_ from ex_job_content_id
        $this->get_key($field_id);

        if (!isset($this->fields[$this->instance_name]))
        { 
            return; 
        }
        // loop over each field in the instance array
        foreach ($this->fields[$this->instance_name] as $instance) 
        {
            // if the instance ID matches the name of the selection
            // we've specified...
            if ($instance[$this->group][$this->key] == $field_value) {

                // Update the main array to contain the specific instance details
                $this->fields[$this->main_field][$instance_id][$field_id] = $instance;
            }
        }
    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                               Substitutions                             │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function get_instance_name($field_id)
    {
        $instance_name = str_replace($this->prefix . '_' . $this->main_field . '_' ,'', $field_id);
        $this->instance_name = str_replace('_id' ,'', $instance_name);
    }

    public function get_group_name($field_id)
    {
        $group  = str_replace($this->main_field . '_' ,'', $field_id);
        $this->group  = str_replace('_id' ,'_group', $group);
    }

    public function get_key($field_id)
    {
        $this->key  = str_replace($this->main_field . '_' ,'', $field_id);
    }

}
