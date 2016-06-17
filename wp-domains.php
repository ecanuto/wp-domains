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

/**
 * Retrieve the WordPress admin URL.
 *
 * If the constant named 'WP_ADMINURL' is defined, then the value in that
 * constant will always be returned. This can be used for debugging a site
 * on your localhost while not having to change the database to your URL.
 *
 * @param string $url Default URL.
 * @return string The WordPress Admin URL.
 */
function wp_domains_admin_url( $url = '' ) {
	if ( defined( 'WP_ADMINURL' ) && ( '' != WP_ADMINURL ) )
		return untrailingslashit( WP_ADMINURL );
	if ( $url )
		return $url;
	return get_site_url();
}

/**
 * Replaces the WordPress site URL with admin URL.
 *
 * This function returns a string with all occurrences of site URL replaced
 * by admin URL. If site and admin URL are the same, it returns URL intact.
 * 
 * @param string $url URL to be replaced.
 * @return string Replaced URL.
 */
function wp_domains_replace_url( $url = '' ) {
	$adminurl = wp_domains_admin_url();
	$siteurl  = get_site_url();
	
	if ($adminurl == $siteurl)
		return $url;
	else
		return preg_replace( "|$siteurl|", $adminurl, $url );
}

add_filter( 'admin_url',         'wp_domains_replace_url', 20 );
add_filter( 'login_url',         'wp_domains_replace_url', 20 );
add_filter( 'logout_url',        'wp_domains_replace_url', 20 );
add_filter( 'preview_post_link', 'wp_domains_replace_url', 20 );

/**
 * Filter URL based on current script name.
 *
 * Checks current script name, if wp-login then return admin URL otherwise
 * returns URL intact.
 *
 * @return string Filtered URL.
 */
function wp_domains_site_url( $url = '' ) {
	if ($_SERVER['SCRIPT_NAME'] == '/wp-login.php')
		return wp_domains_admin_url( $url );
	return $url;
}

add_filter( 'option_siteurl', 'wp_domains_site_url' );
add_filter( 'option_home',    'wp_domains_site_url' );
