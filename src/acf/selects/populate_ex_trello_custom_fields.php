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
    $choices = [];

    if (isset($_POST['board'])) {
        $board = $_POST['board'];
    }

    $fields = get_custom_fields($board);

    foreach ($fields as $value => $field) {
        $choices[] = array('value' => $value, 'label' => $field);
    }

    if (!empty($choices))
    {
        echo json_encode($choices);
    }

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
            $row = the_row();

            if ($row['acf_fc_layout'] != 'trello'){ continue; }
            
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

    foreach ($custom_fields as $key => $custom_field) {
        $choices[$custom_field->id] = $custom_field->name;
    }

    /**
     * Add parent flexible fields.
     */
    $acf_custom_field = new \ex\update_acf_options_field;
    $acf_custom_field->set_field('field_5f8979aa7d0e5');
    $acf_custom_field->set_value('choices', $choices);
    $result = $acf_custom_field->run();

    return $choices;
}

