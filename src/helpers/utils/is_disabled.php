<?php

namespace ex\utils;

trait is_disabled {

    public static function is_disabled($config, $section)
    {
        if ($config['ex_'.$section.'_group']['ex_'.$section.'_enabled'] == false)
        {
            return true;
        }
        return false;
    }

}