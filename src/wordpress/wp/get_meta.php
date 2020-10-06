<?php

namespace ex\wp;

trait wp_get_meta {

    
    public static function wp_get_meta($post_id)
    {

        $result = get_post_meta($post_id);

        return $result;
    }

}