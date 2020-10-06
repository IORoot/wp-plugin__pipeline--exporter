<?php

namespace ex\utils;

trait to_utf8_string
{

    public static function to_utf8_string($d)
    {

        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = self::to_utf8_string($v);
            }
        }
        
        if (is_string ($d)) {
            return utf8_encode($d);
        }

        return $d;
        

    }

}
