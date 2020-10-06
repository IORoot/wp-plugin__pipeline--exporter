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
    ];


    public function debug($section, $message)
    {
        $this->set_acf_textarea($section);
        $this->debug_update($section, $message);
    }

    public function debug_clear($section)
    {
        $this->set_acf_textarea($section);
        return update_field( $this->debug['acf_textarea'] . '_window', '', 'option');
    }





    private function set_acf_textarea($section)
    {

        $this->debug['acf_textarea'] = $this->debug['namespace'] . '_' . $section . '_debug';
        
    }



    private function add_title($section)
    {
        $title =  PHP_EOL . '# ====================== # ';
        $title .= $section . ' - ' . date('r');
        $title .= ' # ====================== #'. PHP_EOL;
        
        return $title;
    }



    private function debug_update($section, $message)
    {

        $field = $this->debug['acf_textarea'] . '_window';

        $this->get_character_limit();

        $this->set_record_count();
        
        $title = $this->add_title($section);

        $value = $this::to_pretty_JSON($message);
        // $value = $this::to_print_r($message);

        $current = get_field($this->debug['acf_textarea'], 'option');

        $value = $title.$value.$current;

        if ($this->debug['char_limit'] != 0){
            $this->debug['trimmed_string'] = substr($value, 0, $this->debug['char_limit']);
        }

        $this->set_character_count();
        $this->set_line_count();

        $result = update_field($field , $this->debug['trimmed_string'], 'option');

    }



    private function get_character_limit()
    {
        $field = $this->debug['acf_textarea'] . '_limit';
        $this->debug['char_limit'] = intval(get_field($field, 'options'));
    }
    
    private function set_record_count()
    {
        $field = $this->debug['acf_textarea'] . '_records';

        if (!isset($this->results)){ return; }

        $count = count($this->results);

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

}