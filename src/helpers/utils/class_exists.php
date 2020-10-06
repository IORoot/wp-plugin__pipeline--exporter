<?php

namespace ex\utils;

trait class_exists
{

    public static function class_exists($class)
    {

        if (!class_exists($class)) {
            throw new \Exception('This class - '.$$class.' - does not exist, cannot instantiate class of this name.');
        }

        return true;
    }

}
