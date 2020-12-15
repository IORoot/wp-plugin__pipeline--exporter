<?php

namespace andyp\housekeeper\action;

use andyp\housekeeper\interfaces\housekeepInterface;

class none implements housekeepInterface {


    public function wp_query($config)
    {
    }

    public function run()
    {
    }

    public function result()
    {
        return true;
    }

}