<?php

namespace ex\wp;

trait wp_get_posts {

    
    public static function wp_get_posts($args)
    {

        $result = get_posts($args);

        return $result;
    }

}