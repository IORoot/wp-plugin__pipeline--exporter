<?php

namespace ex\content;

class query
{

    use \ex\utils;
    use \ex\wp;

    public $args;

    public function set_args($query)
    {
        $this->args = $this::string_to_array($query);
    }


    public function run()
    {
        $this->result = $this::wp_get_posts_with_meta($this->args);
        return $this->result;
    }

}