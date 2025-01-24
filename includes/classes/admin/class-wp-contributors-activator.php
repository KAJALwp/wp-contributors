<?php
/**
 * Handles the activation tasks for the WP Contributors Plugin.
 *
 * This class is responsible for the tasks that need to be performed when
 * the plugin is activated. It includes checks for the WordPress version
 * and setting up any default options needed for the plugin to function.
 *
 * @package    WP_Contributors
 * @subpackage WP_Contributors/includes/classes/admin
 * @since      1.0.0
 * @author     Kajal Gohel
 */

/**
 * WP Contributors Activator class file.
 */
class WP_Contributors_Activator {

	/**
	 * Code to execute on plugin activation.
	 */
	public static function activate() {
		// Ensure the required WordPress version is available.
		if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
			wp_die( esc_html_e( 'This plugin requires WordPress 5.0 or higher.', 'wp-contributors' ) );
		}

		// Add default options or perform setup tasks if needed.
		update_option( 'wp_contributors_version', WP_CONTRIBUTORS_VERSION );
	}
}
