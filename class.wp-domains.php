<?php

/**
 * Main WP_Domains class.
 *
 * Use different domains for admin and frontend on Wordpress.
 * All of the magic is hooked upon WP_Domains object initialization.
 */
class WP_Domains {

	/**
	 * Constructor.
	 *	
	 * Hooks all of the URL replacement functionality.
	 *
	 * @access public
	 */
	public function __construct() {
		// Modify URL "where" necessary
		add_filter( 'admin_url',         array($this, 'replace_url' ), 20 );
		add_filter( 'login_url',         array($this, 'replace_url' ), 20 );
		add_filter( 'logout_url',        array($this, 'replace_url' ), 20 );
		add_filter( 'preview_post_link', array($this, 'replace_url' ), 20 );
		
		// Modify URL "when" necessary
		add_filter( 'option_siteurl',    array($this, 'site_url' ), 20 );
		add_filter( 'option_home',       array($this, 'site_url' ), 20 );
	}
	
	/**
	 * Initializes WP_Domains object
	 * 
	 * @return string An instance of WP_Domains.
	 */
	public static function init() {
        return new WP_Domains();
	}

	/**
	 * Retrieve the WordPress admin URL.
	 *
	 * If the constant named 'WP_ADMINURL' is defined, then the value in that
	 * constant will always be returned. This can be used for debugging a site
	 * on your localhost while not having to change the database to your URL.
	 *
	 * @access private
	 *
	 * @param string $url Default URL.
	 * @return string The WordPress Admin URL.
	 */
	private function admin_url( $url = '' ) {
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
	 * @access public
	 *
	 * @param string $url URL to be replaced.
	 * @return string Replaced URL.
	 */
	public function replace_url( $url = '' ) {
		$adminurl = $this->admin_url();
		$siteurl  = get_site_url();
		
		if ($adminurl == $siteurl)
			return $url;
		else
			return preg_replace( "|$siteurl|", $adminurl, $url );
	}
	
	/**
	 * Filter URL based on current script name.
	 *
	 * Checks current script name, if wp-login then return admin URL otherwise
	 * returns URL intact.
	 *
	 * @access public
	 *
	 * @return string Filtered URL.
	 */
	public function site_url( $url = '' ) {
		if ($_SERVER['SCRIPT_NAME'] == '/wp-login.php')
			return $this->admin_url( $url );
		return $url;
	}
}
