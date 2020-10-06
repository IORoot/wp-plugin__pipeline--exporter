<?php

namespace ex\utils;

trait string_to_array {

    public static function string_to_array($query)
    {
        
        $wp_query = preg_replace("/\r|\n/", "", $query);
        $wp_query = eval("return " . $wp_query . ";");

        return $wp_query;
    }

}