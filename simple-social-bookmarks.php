<?php
/*
Plugin Name: Social Bookmarks
Description: Display links to social bookmark sites
Version: 3.2.6
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/

/**
* Social Bookmarks
*
* Main code - include various functions
*
* @package	Artiss-Social-Bookmarks
* @since	3.2
*/

define( 'artiss_social_bookmarks_version', '3.2.6' );

/**
* Plugin initialisation
*
* Loads the plugin's translated strings
*
* @since	3.2.6
*/

function asb_plugin_init() {

	$language_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages/';

	load_plugin_textdomain( 'social-bookmarks', false, $language_dir );

}

add_action( 'init', 'asb_plugin_init' );

$functions_dir = WP_PLUGIN_DIR . '/simple-social-bookmarks/includes/';

include_once( $functions_dir . 'asb-shared-functions.php' );			        // Shared functions

// Include all the various functions

if ( is_admin() ) {

    include_once( $functions_dir . 'asb-admin-config.php' );		            // Assorted admin configuration changes

} else {

    include_once( $functions_dir . 'asb-define-bookmarks.php' );			    // Set up the bookmark definitions

	include_once( $functions_dir . 'asb-generate-bookmarks.php' );			    // Generate the bookmarks

	include_once( $functions_dir . 'asb-shortcodes.php' );			            // Shortcode definitions

	include_once( $functions_dir . 'asb-functions.php' );			            // Function calls

	include_once( $functions_dir . 'asb-deprecated.php' );			            // Deprecated functions

}
?>