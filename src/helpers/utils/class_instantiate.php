<?php

namespace ex\utils;

trait class_instantiate {

    
    public static function class_instantiate($namespace, $class)
    {

        $class_name = '\\'.$namespace.'\\'.$class;

        if (!class_exists($class_name)) {
            throw new \Exception('This class - '.$class_name.' - does not exist, cannot instantiate class of this name.');
        }

        $class_object = new $class_name;

        return $class_object;
    }

}