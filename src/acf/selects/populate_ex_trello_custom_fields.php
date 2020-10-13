<?php


/**
 * See https://github.com/Hube2/acf-dynamic-ajax-select-example/tree/master/dynamic-select-example
 * for help on selects updating selects with ajax.
 */


/**
 * Add AJAX Callback
 */
function ajax_load_custom_fields_choices()
{
    if (isset($_POST['board'])) {
        $board = $_POST['board'];
    }

    $custom_fields = get_custom_fields($board);

    foreach ($custom_fields as $value => $label) {
        $choices[] = array('value' => $value, 'label' => $label);
    }

    echo json_encode($choices);

    exit;
}
add_action('wp_ajax_load_custom_fields_choices', 'ajax_load_custom_fields_choices');




/**
 * Interact with the Trello API to get the custom_fields
 * on the specific selected board.
 */
function get_custom_fields($board)
{

    $client = new GuzzleHttp\Client();

    $headers = array(
        'Accept' => 'application/json'
    );
    

    if( have_rows('ex_auth_instance', 'option') ) {
        
        // while has rows
        while( have_rows('ex_auth_instance', 'option') ) {
            
            // instantiate row
            the_row();
            
            // vars
            $api_key = get_sub_field('field_5f844d18ebd3e');
            $token   = get_sub_field('field_5f844e1083944');
            
        }
        
    }

    $query = array(
        'key'    => $api_key,
        'token'  => $token,
    );

    $request = "https://api.trello.com/1/boards/".$board."/customFields?" . http_build_query($query);



    try {
        $response = $client->request(
            'GET', 
            $request
        );
    } catch (\Exception $e) {
        var_dump($e->getMessage(), true);
        die;
    }


    $custom_fields = json_decode($response->getBody()->getContents());


    foreach ($custom_fields as $key => $custom_field)
    {
        $choices[ $custom_field->id ] = $custom_field->name;
    }

    /**
     * Update the actual select field in ACF
     */
    $custom_field_field = new \ex\update_acf_options_field;
    $custom_field_field->set_field('field_5f85a329b1641');
    $custom_field_field->set_value('choices', $choices);
    $custom_field_field->run();


    return $choices;
}


