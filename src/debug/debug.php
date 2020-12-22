<?php

namespace ex;

// reporting
trait debug
{

    use \ex\utils;

    public $debug = [
        'acf_textarea' => '',
        'title' => '',
        'namespace' => 'ex',
        'char_limit' => 10000,
        'trimmed_string' => '',
        'section' => '',
        'message' => '',
    ];


    public function debug($section, $message)
    {
        $this->debug['section'] = $section;
        $this->debug['message'] = $message;
        $this->set_acf_textarea();
        $this->debug_update();
    }

    public function debug_clear($section)
    {
        $this->debug['section'] = $section;
        $this->set_acf_textarea();
        update_field( $this->debug['acf_textarea'] . '_window', '', 'option');
    }




    /**
     * Private
     */

    private function set_acf_textarea()
    {
        $this->debug['acf_textarea'] = $this->debug['namespace'] . '_' . $this->debug['section'] . '_debug';
    }

    
    private function add_title()
    {
        $title =  PHP_EOL . '# ====================== # ';
        $title .= $this->debug['section'] . ' - ' . date('r');
        $title .= ' # ====================== #'. PHP_EOL;
        
        return $title;
    }


    private function debug_update()
    {

        $field = $this->debug['acf_textarea'] . '_window';

        $this->cast_objects_to_array();

        $this->get_character_limit();

        $this->set_record_count();
        
        $this->output_new_debug_message();

        $this->set_character_count();

        $this->set_line_count();

        update_field($field , $this->debug['trimmed_string'], 'option');

    }


    private function cast_objects_to_array()
    {
        $message = $this->debug['message'];
        if (!isset($message)){ return; }
        if (!is_object($message)){ return; }
        $this->debug['message'] = (array) $message;
    }


    private function get_character_limit()
    {
        $field = $this->debug['acf_textarea'] . '_limit';
        $value = get_field($field, 'options');
        $this->debug['char_limit'] = intval($value);
    }
    
    private function set_record_count()
    {
        $field = $this->debug['acf_textarea'] . '_records';

        if (!isset($this->debug['message'])){ return; }

        $count = 0;

        if (is_object($this->debug['message'])){
            $count = count($this->debug['message']);
        }
        if (is_array($this->debug['message'])){
            $count = count($this->debug['message']);
        }


        return update_field( $field, $count, 'option');
    }

    private function set_character_count()
    {
        $field = $this->debug['acf_textarea'] . '_characters';

        $count = strlen($this->debug['trimmed_string']);

        return update_field( $field, $count, 'option');
    }

    private function set_line_count()
    {
        $field = $this->debug['acf_textarea'] . '_lines';

        if (empty($this->debug['trimmed_string'])){ return; }
        $count = substr_count( $this->debug['trimmed_string'], "\n" );

        return update_field( $field, $count, 'option');
    }



    private function output_new_debug_message()
    {
        
        // add a title.
        $value = $this->add_title();
        $value .= PHP_EOL;

        // convert to outputtable value.
        $value .= $this::to_pretty_JSON($this->debug['message']);
        $value .= PHP_EOL;


        // get the last message.
        $old_value = get_field($this->debug['acf_textarea'], 'option');
        if (is_array($old_value)){
            $value .= $old_value['window'];
            $value .= PHP_EOL;
        }


        // update output
        if ($this->debug['char_limit'] != 0){
            $this->debug['trimmed_string'] = substr($value, 0, $this->debug['char_limit']);
        }

    }

}