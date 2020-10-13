<?php

function acf_populate_ex_trello_location_board_choices( $field ) {


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
        'fields' => 'name',
        'key'    => $api_key,
        'token'  => $token,
    );

    $request = "https://api.trello.com/1/members/me/boards?" . http_build_query($query);



    $response = $client->request(
        'GET', 
        $request
    );


    $boards = json_decode($response->getBody()->getContents());


    foreach ($boards as $key => $board)
    {
        $field['choices'][ $board->id ] = $board->name;
    }



    return $field;

}

add_filter('acf/load_field/key=field_5f845ee95ad4c', 'acf_populate_ex_trello_location_board_choices');