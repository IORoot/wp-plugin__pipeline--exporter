<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Andyp_exporter
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	
	/**
     * selectively use ACF in plugins directory or in current directory,
     * depending on GITHUB CI or regular PHPUNIT
     */
    $path = '';
    if (!is_dir(dirname(dirname(__FILE__)) . '/advanced-custom-fields-pro')) {
        $path = '../';
    }
	require dirname(dirname(__FILE__)) . '/'.$path.'advanced-custom-fields-pro/acf.php';	// ACF
	require dirname(dirname(__FILE__)) . '/'.$path.'andyp_oauth_youtube/oauth_youtube.php';	// needed to test oAUTH
	require dirname(dirname(__FILE__)) . '/'.$path.'andyp_oauth_gmb/oauth_gmb.php';			// needed to test oAUTH
	require dirname(dirname(__FILE__)) . '/exporter.php';								   // This Plugin

	/**
	 * SECRETS and OAUTH Dependencies
	 * 
	 * This file will contain the username / password for the 
	 * creator studio account.
	 * 
	 * This file will manually declare the refresh token - only way
	 * to do this because of the whole oAuth separate window iframe
	 * deal that you need to do to authorise.
	 * 
	 * Run that and get the refresh_token then add it to this file.
	 * 
	 */
	require dirname( dirname( __FILE__ ) ) . '/tests/test_secrets.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

/**
 * Constants
 */
$upload_dir = wp_upload_dir();
define('UPLOAD_DIR', 'wp-content/uploads'. $upload_dir['subdir'] );
define('DIR_DATA', dirname(__FILE__) . '/data');
define('WP_HOME', 'http://example.org');