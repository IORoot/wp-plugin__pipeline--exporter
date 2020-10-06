<?php

/**
 * On save of options page, run.
 */
function save_ex_options()
{
    $screen = get_current_screen();

    if ($screen->id != "pipeline_page_exporter") {
        return;
    }
        
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘
    $ue = new \ex\exporter;
    $options = (new \ex\options)->get_options();
    $ue->set_options($options);
    $ue->run();
    
    return;
}

// MUST be in a hook
add_action('acf/save_post', 'save_ex_options', 20);