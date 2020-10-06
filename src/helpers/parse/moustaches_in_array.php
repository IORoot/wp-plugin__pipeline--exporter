<?php

namespace ex\parse;

class replace_moustaches_in_array
{

    use \ue\utils\array_flat;

    private $post_id;
    private $array_to_change;

    private $post;
    private $meta;

    private $current_value;

    public function __construct($post_id, $array_to_change)
    {
        $this->post_id = $post_id;
        $this->array_to_change = $array_to_change;
        $this->run();
    }

    public function run()
    {
        $this->get_post_and_meta();
        $this->array_to_change = $this->walk_array($this->array_to_change);
    }

    public function get_results()
    {
        return $this->array_to_change;
    }

    private function get_post_and_meta()
    {
        $post = (array) get_post($this->post_id);
        $meta = (array) get_post_meta($this->post_id);
        $post_meta = array_merge($post, $meta);
        $this->post = $this::array_flat($post_meta);
    }



    /**
     * walk_array
     * 
     * This is recursive to handle multidimensional arrays.
     *
     * @param array $array_to_change
     * @return void
     */
    private function walk_array($current_array)
    {

        foreach ($current_array as $key => $this->current_value)
        {
            // Make Recursive.
            if (is_array($this->current_value))
            {
                $current_array[$key] = $this->walk_array($this->current_value);
                continue;
            }

            $this->match_moustaches();
            $current_array[$key] = $this->current_value;
        }

        return $current_array;

    }



    private function match_moustaches()
    {

        preg_match_all('/\{\{(.*?)\}\}/', $this->current_value, $matches, PREG_SET_ORDER);

        if ($matches == null){ return; }

        foreach($matches as $match)
        {

            if ( strpos($match[1],'image:') !== false)
            {
                $this->replace_with_image_data($match);
            }
            
            $this->replace_with_post_data($match);
        }
        $matches = null;

        return;
    }



    private function replace_with_post_data($match)
    {

        // post & meta fields
        if (isset($this->post[$match[1]]))
        {
            $real_value = $this->post[$match[1]];
        }

        if (!isset($real_value)){ return; }
        
        $this->current_value = str_replace($match[0], $real_value, $this->current_value);
    }


    private function replace_with_image_data($match)
    {

        if ($this->post['post_type'] != 'attachment')
        {
            return;
        }

        $actual_key = str_replace('image:', '', $match[1]);

        // attachment post & meta fields
        if (isset($this->post[$actual_key]))
        {
            $real_value = $this->post[$actual_key];
        }

        if (!isset($real_value)){ return; }
        
        $this->current_value = str_replace($match[0], $real_value, $this->current_value);
    }



}