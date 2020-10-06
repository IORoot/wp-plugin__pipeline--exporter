<?php

namespace ex\utils;

trait is_disabled {

    public static function is_disabled($config, $section)
    {
        if ($config['ue_'.$section.'_group']['ue_'.$section.'_enabled'] == false)
        {
            return true;
        }
        return false;
    }

}