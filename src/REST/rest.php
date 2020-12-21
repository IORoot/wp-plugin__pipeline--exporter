<?php

/**
 * Composer will create this through the __construct method.
 */

class new_rest_endpoint
{

    public $options;

    public $request;

    private $route_queries;

    private $posts;


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
                '/exporter/(?P<slug>\w+)', 
        
        
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
    public function get_latest_exporter_post( WP_REST_Request $request )
    {
        $this->request = $request;

        $this->get_options();

        $this->loop_export_groups();

        if (!$this->is_requested_route_defined()){ return 'No such REST endpoint defined. Is it enabled?'; }

        $this->get_routes_posts();


        foreach($this->posts as $key => $post)
        {
            // Post from stdClass to Array
            $result = (array) $post;

            // Meta
            $result = array_merge($result, get_post_meta($post->ID));

            // Attachment
            $attachment = get_post_meta($result['_thumbnail_id'][0]);
            $attachment['_wp_attachment_metadata'] = unserialize($attachment['_wp_attachment_metadata'][0]);
            $result = array_merge($result, $attachment);

            // Attachment SRC
            $src['_wp_attachment_src'] = wp_get_attachment_image_src($result['_thumbnail_id'][0]);
            $result = array_merge($result, $src);

            $results[] = $this::array_flat($result);

            $result = [];
        }
    
        if ( empty( $results ) ) {
            return null;
        }
    
        return $results;
    }





    /**
     * This will get all the options for all exports and filter for the REST ones.
     * Each REST export will then run the set_routes() method.
     */
    private function get_options()
    {
        $this->options = (new \ex\option)->get_all('ex_export_instance');
    }



    private function loop_export_groups()
    {
        foreach($this->options as $this->export_group)
        {
            $this->loop_export_methods();
        }
    }




    private function loop_export_methods()
    {

        if ($this->export_group['ex_export_group']['ex_export_enabled'] == false){ return; }

        foreach($this->export_group['ex_export_target_mapping'] as $this->export_method)
        {
            if ($this->export_method['acf_fc_layout'] != "rest"){ continue; }
        
            $this->loop_routes();
        }
    }



    private function loop_routes()
    {
        foreach ($this->export_method['post_types_rest'] as $this->routes)
        {
            $this->set_route();
        }
    }



    private function set_route()
    {
        $wp_query = preg_replace("/\r|\n/", "", $this->routes['post_arguments']);

        $query = eval("return " . $wp_query . ";");

        $slug = $this->routes['endpoint_route'];

        $this->route_queries[$slug] = $query;
        
    }


    private function is_requested_route_defined()
    {
        $requested_slug = $this->request->get_param('slug');

        if (!isset($this->route_queries[$requested_slug])){ return false; }

        $this->current_route_query = $this->route_queries[$requested_slug];

        if (empty($this->current_route_query)){ return false; }

        return true;
    }



    private function get_routes_posts()
    {
        $this->posts = get_posts( $this->current_route_query );
    }



    /**
     * This flattens a multi-level array into a single-level array.
     */
    private static function array_flat($array, $prefix = '', $separator = '_')
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