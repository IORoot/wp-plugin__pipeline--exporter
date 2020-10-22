<?php

class new_rest_endpoint
{

    public $options;


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

        $this->get_options();

        $slug = $request->get_param('slug');

        $route = $this->routes[$slug];

        if (empty($route)){ return 'No such REST endpoint defined.'; }

        $posts = get_posts( $route );


        foreach($posts as $key => $post)
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



    public function get_options()
    {
        $this->options = (new \ex\option)->get_all('ex_export_instance');

        foreach($this->options[0]['ex_export_target_mapping'] as $this->export_method)
        {
            if ($this->export_method['acf_fc_layout'] != "rest"){ continue; }
            
            $this->set_routes();
        }
    }


    public function set_routes()
    {
        foreach ($this->export_method['post_types_rest'] as $routes)
        {
            // convert string to array
            $wp_query = preg_replace("/\r|\n/", "", $routes['post_arguments']);
            $this->routes[$routes['endpoint_route']] = eval("return " . $wp_query . ";");
        }
    }


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