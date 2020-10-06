<?php

namespace ex;

// reporting
trait debug
{

    use \ue\utils;

    public $acf_textarea;

    public $title;

    public $namespace = "ue";

    public $char_limit = 10000;

    public $trimmed_string;


    public function debug($section, $message)
    {
        $this->set_acf_textarea($section);

        $this->debug_clear($section);

        $this->debug_update($section, $message);
    }




    public function set_acf_textarea($section)
    {
        $acf_group = $this->namespace . '_' . $section . '_debug_group';
        $this->acf_textarea = $acf_group . '_' . $this->namespace . '_' . $section . '_debug';
    }






    public function debug_clear($section)
    {
        $this->set_acf_textarea($section);
        return update_field( $this->acf_textarea, '', 'option');
    }


    public function add_title($section)
    {
        $title =  PHP_EOL . '# ====================== # ';
        $title .= $section . ' - ' . date('r');
        $title .= ' # ====================== #'. PHP_EOL;
        
        return $title;
    }



    public function debug_update($section, $message)
    {
        $this->set_acf_textarea($section);

        $this->get_character_limit();

        $this->set_record_count();
        
        $title = $this->add_title($section);

        $value = $this::to_pretty_JSON($message);
        // $value = $this::to_print_r($message);

        $current = get_field($this->acf_textarea, 'option');

        $value = $title.$value.$current;

        if ($this->char_limit != 0){
            $this->trimmed_string = substr($value, 0, $this->char_limit);
        }

        $this->set_character_count();
        $this->set_line_count();

        $update = update_field($this->acf_textarea, $this->trimmed_string, 'option');

    }






    public function get_character_limit()
    {
        $field = $this->acf_textarea . '_limit';
        $this->char_limit = intval(get_field($field, 'options'));
    }
    
    public function set_record_count()
    {
        $field = $this->acf_textarea . '_records';

        if (!isset($this->results)){ return; }

        $count = count($this->results);

        return update_field( $field, $count, 'option');
    }

    public function set_character_count()
    {
        $field = $this->acf_textarea . '_characters';

        $count = strlen($this->trimmed_string);

        return update_field( $field, $count, 'option');
    }

    public function set_line_count()
    {
        $field = $this->acf_textarea . '_lines';

        if (empty($this->trimmed_string)){ return; }
        $count = substr_count( $this->trimmed_string, "\n" );

        return update_field( $field, $count, 'option');
    }

}