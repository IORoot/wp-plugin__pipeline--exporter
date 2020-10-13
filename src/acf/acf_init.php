<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                         Include ACF Options Page                        │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/acf_admin_page.php';
require __DIR__.'/acf_admin_css.php';



//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │            Populate all of the 'select' types automatically             │
//  └─────────────────────────────────────────────────────────────────────────┘

// jobs
require __DIR__.'/selects/populate_ex_job_auth.php';
require __DIR__.'/selects/populate_ex_job_content.php';
require __DIR__.'/selects/populate_ex_job_export.php';
require __DIR__.'/selects/populate_ex_job_housekeep.php';
require __DIR__.'/selects/populate_ex_job_schedule.php';

// trello boad / list
require __DIR__.'/selects/populate_ex_trello_board.php';



//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │               Only run when the UPDATE button is clicked                │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/on_update.php';