<?php

namespace ex\exporter\rest;

class new_post
{


    public function __construct()
    {
        $this->register_endpoint();
    }


    /**
     * Register a new endpoint to
     * point to our callback function.
     */
    public function register_endpoint()
    {
        add_action( 'rest_api_init', function () {

            register_rest_route( 
        
                /**
                 * vendor/API_Version
                 */
                'pipeline/v1', 
        
        
                /**
                 * route and parameters 
                 */
                '/exporter/(?P<postcount>\d+)', 
        
        
                /**
                 * Extra Settings
                 */
                array(
        

                    /**
                     * REST Method
                     */
                    'methods' => 'GET',
        

                    /**
                     * Callback function to call
                     * (see below)
                     */
                    'callback' => array($this,'get_latest_exporter_post'),
        

                    /**
                     * Permissions required to
                     * run endpoint.
                     * (public)
                     */
                    'permission_callback' => '__return_true',
                ) 
        
        
            );
        
        } );
    }



    /**
     * The callback function to run
     * when called.
     */
    public function callback( \WP_REST_Request $postcount )
    {
        $posts = \get_posts( array(
            'post_type' => 'exporter',
            'post_status' => 'publish',
            'order' => 'DESC',
            'numberposts' => $postcount->get_param('postcount'),
        ) );
    
        if ( empty( $posts ) ) {
            return null;
        }
    
        return $posts;
    }


}
