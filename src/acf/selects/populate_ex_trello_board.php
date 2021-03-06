<?php


/**
 * Add Javascript for dynamically changing the select boxes
 */
function enqueue_trello_board_js()
{
    $src = plugin_dir_url( __FILE__ ).'../js/dynamic-board-select.js';
    wp_enqueue_script('trello_boards_js', $src, array('acf-input'));
}
add_action('acf/input/admin_enqueue_scripts', 'enqueue_trello_board_js');





function acf_populate_ex_trello_location_board_choices( $field ) {


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

    $request = "https://api.trello.com/1/members/me/boards?" . http_build_query($query);


    try {

        $response = $client->request(
            'GET', 
            $request
        );
    
    
        $boards = json_decode($response->getBody()->getContents());

    } catch (exception $e)
    {
        return $field;
    }


    foreach ($boards as $key => $board)
    {
        $field['choices'][ $board->id ] = $board->name;
    }


    return $field;

}

add_filter('acf/load_field/key=trello_board', 'acf_populate_ex_trello_location_board_choices');