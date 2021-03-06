<?php
/*
Plugin Name: WSUWP Extended WordPress SEO
Version: 0.0.4
Plugin URI: https://web.wsu.edu/
Description: Modifies default functionality in WordPress SEO.
Author: washingtonstateuniversity, philcable
Author URI: https://web.wsu.edu/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsu-seo.php';

add_action( 'plugins_loaded', 'WSUWP_Extend_WP_SEO' );
/**
 * Start things up.
 *
 * @return \WSUWP_Extend_WP_SEO
 */
function WSUWP_Extend_WP_SEO() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return WSUWP_Extend_WP_SEO::get_instance();
	}
}
