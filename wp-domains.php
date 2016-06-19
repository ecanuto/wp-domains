<?php
/**
 * Plugin Name:       WP Domains
 * Plugin URI:        https://github.com/ecanuto/wp-domains
 * Description:       Use different domains for admin and frontend on Wordpress.
 * Version:           0.1
 * Author:            Everaldo Canuto
 * Author URI:        http://ecanuto.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-domains
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Logout when activate or deactivate plugin.
register_activation_hook( __FILE__, 'wp_logout' );
register_deactivation_hook( __FILE__, 'wp_logout' );

// Initialize plugin
require_once( plugin_dir_path( __FILE__ ) . 'class.wp-domains.php' );
WP_Domains::init();
