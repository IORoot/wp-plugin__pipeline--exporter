<?php

namespace ex\utils;

trait to_print_r
{

    public static function to_print_r($value)
    {

        if (is_array($value)){
            $value = self::to_utf8_string($value);
            $value = print_r($value, true);
        }

        if (is_object($value)){
            $value = (array) $value;
            $value = print_r($value, true);
        }
        
        return $value;
    }

}
