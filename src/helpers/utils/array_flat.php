<?php

namespace ex\utils;

trait array_flat
{

    public static function array_flat($array, $prefix = '', $separator = '_')
    {
        $result = array();

        foreach ($array as $key => $value)
        {
            if ($prefix == '') { $sep = ''; } else { $sep = $separator; }

            $new_key = $prefix . $sep . $key;

            if (is_array($value))
            {
                $result = array_merge($result, self::array_flat($value, $new_key, $separator));
            }
            else
            {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

}


