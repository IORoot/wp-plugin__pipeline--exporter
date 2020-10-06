<?php

namespace ex\content;

class query
{

    use \ue\utils;
    use \ue\wp;

    public $args;

    public function set_args($query)
    {
        $this->args = $this::string_to_array($query);
    }


    public function run()
    {
        $this->result = $this::wp_get_posts_with_meta($this->args);
        // $this->update_process_input_field();
        return $this->result;
    }

    /**
     * update_process_input_field
     * 
     * This is used to populate the select field in the
     * 'process and combine' steps, next.
     * 
     * Uses the first record in the results.
     *
     * @return void
     */
    private function update_process_input_field()
    {
        $flattened_record = $this::array_flat($this->result[0], '', '->');
        foreach ($flattened_record as $key => $value)
        {
            $this->select_array[] = $key; 
        }


        $field = get_field_object('field_5f6ee3637425f', 'option');
        $field['choices'] = $this->select_array;
        $result = update_field('field_5f6ee3637425f', $field, 'option');

    }  

}