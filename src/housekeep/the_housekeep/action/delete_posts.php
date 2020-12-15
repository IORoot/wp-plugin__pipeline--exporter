<?php

namespace andyp\housekeeper\action;

use andyp\housekeeper\interfaces\housekeepInterface;

class delete_posts implements housekeepInterface{


    public $query;

    public $post_list;

    public $response;


    public function wp_query($wp_query)
    {
        $config = preg_replace( "/\r|\n/", "", $wp_query );
        $this->query = @eval("return " . $config . ";");

        $this->post_list();

        return;
    }

    public function run()
    {
        if (empty($this->post_list)){
            return;
        }
        foreach($this->post_list as $post)
        {
            $this->response[] = wp_delete_post( $post->ID, true);
        }

        return;
    }

    /**
     * Report : What WILL happen and what HAS happened.
     */
    public function result()
    {
        if (!isset($this->post_list)){ return false; }
        if (empty($this->post_list)){ return false; }
        return true;
    }


    public function post_list()
    {
        if (!is_array($this->query)){
            return;
        }
        $this->post_list = get_posts($this->query);
    }


}