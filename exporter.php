<?php 

/*
 * 
 * @wordpress-plugin
 * Plugin Name:       _ANDYP - Pipeline - Exporter
 * Plugin URI:        http://londonparkour.com
 * Description:       <strong>🤖 Pipeline</strong> | <em>Pipeline > Universal Exporter</em> | Schedule export of posts to targets.
 * Version:           1.0.0
 * Author:            Andy Pearson
 * Author URI:        https://londonparkour.com
 * Domain Path:       /languages
 */

define('GOOGLE_APPLICATION_CREDENTIALS', __DIR__.'/client_secret.json');

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                    Register with ANDYP Plugins                          │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/src/acf/andyp_plugin_register.php';

// ┌─────────────────────────────────────────────────────────────────────────┐
// │                         Use composer autoloader                         │
// └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/vendor/autoload.php';

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                    Google-My-Business API Service                       │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/GMB/MyBusiness.php';

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                          The ACF Admin Page                             │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/src/acf/acf_init.php';

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                          Setup REST Endpoint                            │
//  └─────────────────────────────────────────────────────────────────────────┘
new new_rest_endpoint;


//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                               The Schedules                             │
//  └─────────────────────────────────────────────────────────────────────────┘
new \andyp\scheduler\add_schedules;

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                              The Run Action                             │
//  └─────────────────────────────────────────────────────────────────────────┘
new \ex\action\pipeline_exporter;