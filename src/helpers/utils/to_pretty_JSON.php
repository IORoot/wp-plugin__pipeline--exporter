<?php

namespace ex\utils;

trait to_pretty_JSON
{

    public static function to_pretty_JSON($value)
    {

        if (is_array($value)){
            $value = self::to_utf8_string($value);
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }

        if (is_object($value)){
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }
        
        return $value;
    }

}
