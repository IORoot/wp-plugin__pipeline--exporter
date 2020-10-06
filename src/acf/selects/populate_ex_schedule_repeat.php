<?php

function acf_populate_ex_schedule_repeats_choices( $field ) {
    
    $field['choices'] = [];

    $schedules = wp_get_schedules();

    foreach ($schedules as $key => $value)
    {
        $field['choices'][$key] = $value['display'];
    }

    $field['choices']['none'] = "None";

    return $field;
}

add_filter('acf/load_field/name=ex_schedule_repeats', 'acf_populate_ex_schedule_repeats_choices');