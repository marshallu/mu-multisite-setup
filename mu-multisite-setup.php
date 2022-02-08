<?php
/**
 * MU Multisite Setup
 *
 * This plugin was built to allow for Marshall University websites to display photo galleries.
 *
 * @package MU Multisite Setup
 *
 * Plugin Name: MU Multisite Setup
 * Plugin URI: https://www.marshall.edu
 * Description: Activate plugins, set options, etc on site creation.
 * Version: 1.0
 * Author: Christopher McComas
 */

/**
 * Flush rewrites whenever the plugin is activated.
 */
function mu_multisite_setup_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_multisite_setup_activate' );

/**
 * Flush rewrites whenever the plugin is deactivated, also unregister 'employee' post type and 'department' taxonomy.
 */
function mu_multisite_setup_deactivate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_multisite_setup_deactivate' );

/**
 * Setup WordPress sites on Marshall's network with default settings.
 *
 * Set proper upload_url_path for site
 * Set WWW group for CAS Authentication plugin
 * Activate Classic Editor plugin
 *
 * @param object $new_site The array of data when created a new site.
 */
function mu_multisite_setup_actions( $new_site ) {
	switch_to_blog( $new_site->blog_id );

	$homepage_post = array(
		'ID'           => 2,
		'post_title'   => 'Homepage',
		'post_content' => 'This is your homepage.',
	);

	update_option( 'upload_url_path', $new_site->path . 'files', true );
	$strip_path = str_replace( '/', '', $new_site->path );
	update_option( 'mucasauth-settings', array( 'allowedADGroups' => array( 'WWW_' . $strip_path ) ), true );
	activate_plugin( 'classic-editor/classic-editor.php' );

	wp_update_post( $homepage_post );
	update_option( 'page_on_front', 2 );
	update_option( 'show_on_front', 'page' );

	update_option( 'timezone_string', 'America/Detroit' );

	restore_current_blog();
}
add_action( 'wp_initialize_site', 'mu_multisite_setup_actions', 11, 2 );
