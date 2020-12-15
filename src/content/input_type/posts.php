<?php

namespace ex\content;

class posts
{

    use \ex\utils;
    use \ex\wp;

    public $args;

    public $result;

    public function set_args($query)
    {
        $this->args = $query;
    }


    public function run()
    {
        foreach ($this->args as $key => $post)
        {
            $this->result[$key] = (array) $post;
            $this->result[$key] = array_merge($this->result[$key], $this::wp_get_meta($post->ID));
        }

        return $this->result;
    }

}
