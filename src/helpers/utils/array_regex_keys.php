<?php

namespace ex\utils;

trait array_regex_keys
{

    public static function array_regex_keys($pattern, $input)
    {
        $matches = array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input))));

        return $matches;
    }

}


