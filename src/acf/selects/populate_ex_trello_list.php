<?php


/**
 * See https://github.com/Hube2/acf-dynamic-ajax-select-example/tree/master/dynamic-select-example
 * for help on selects updating selects with ajax.
 */


/**
 * Add AJAX Callback
 */
function ajax_load_list_field_choices()
{
    if (isset($_POST['board'])) {
        $board = $_POST['board'];
    }

    $lists = get_lists($board);

    foreach ($lists as $value => $label) {
        $choices[] = array('value' => $value, 'label' => $label);
    }

    echo json_encode($choices);

    exit;
}
add_action('wp_ajax_load_list_field_choices', 'ajax_load_list_field_choices');




/**
 * Interact with the Trello API to get the lists
 * on the specific selected board.
 */
function get_lists($board)
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
        'fields' => 'name',
        'key'    => $api_key,
        'token'  => $token,
    );

    $request = "https://api.trello.com/1/boards/".$board."/lists?" . http_build_query($query);


    try {
        $response = $client->request(
            'GET', 
            $request
        );
    } catch (\Exception $e) {
        var_dump($e->getMessage(), true);
        die;
    }


    $lists = json_decode($response->getBody()->getContents());


    foreach ($lists as $key => $list)
    {
        $choices[ $list->id ] = $list->name;
    }

    /**
     * Update the actual select field in ACF
     */
    $list_field = new \ex\update_acf_options_field;
    $list_field->set_field('field_5f845efb5ad4d');
    $list_field->set_value('choices', $choices);
    $list_field->run();


    return $choices;
}


