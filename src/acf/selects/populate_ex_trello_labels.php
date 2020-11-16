<?php


/**
 * See https://github.com/Hube2/acf-dynamic-ajax-select-example/tree/master/dynamic-select-example
 * for help on selects updating selects with ajax.
 */


/**
 * Add AJAX Callback
 */
function ajax_load_label_field_choices()
{

    $choices[] = array('value' => 'none', 'label' => 'None');

    if (isset($_POST['board'])) {
        $board = $_POST['board'];
    }

    $labels = get_labels($board);

    foreach ($labels as $value => $label) {
        $choices[] = array('value' => $value, 'label' => $label);
    }

    echo json_encode($choices);

    exit;
}
add_action('wp_ajax_load_label_field_choices', 'ajax_load_label_field_choices');




/**
 * Interact with the Trello API to get the labels
 * on the specific selected board.
 */
function get_labels($board)
{

    $client = new GuzzleHttp\Client();

    $headers = array(
        'Accept' => 'application/json'
    );
    

    // Get Authentication details
    if( have_rows('ex_auth_instance', 'option') ) {
        while( have_rows('ex_auth_instance', 'option') ) {
            
            $row = the_row();

            if ($row['acf_fc_layout'] != 'trello'){ continue; }

            $api_key = get_sub_field('field_5f844d18ebd3e');
            $token   = get_sub_field('field_5f844e1083944');
        }
    }

    $query = array(
        'fields' => 'name',
        'key'    => $api_key,
        'token'  => $token,
    );

    $request = "https://api.trello.com/1/boards/".$board."/labels?" . http_build_query($query);


    try {
        $response = $client->request(
            'GET', 
            $request
        );
    } catch (\Exception $e) {
        var_dump($e->getMessage(), true);
        die;
    }


    $labels = json_decode($response->getBody()->getContents());


    foreach ($labels as $key => $label)
    {
        $choices[ $label->id ] = $label->name;
    }

    /**
     * Update the actual select field in ACF
     */
    $label_field = new \ex\update_acf_options_field;
    $label_field->set_field('field_5f8489754e199');
    $label_field->set_value('choices', $choices);
    $label_field->run();


    return $choices;
}


