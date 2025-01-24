<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/kajalgohel/
 * @since             1.0.0
 * @package           WP_Contributors
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Contributors
 * Plugin URI:        https://profiles.wordpress.org/kajalgohel/
 * Description:       Assign multiple contributors to posts and display them on the front-end.
 * Version:           1.0
 * Author:            Kajal Gohel
 * Author URI:        https://profiles.wordpress.org/kajalgohel/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-contributors
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'WP_CONTRIBUTORS_VERSION', '1.0.0' );
define( 'WP_CONTRIBUTORS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CONTRIBUTORS_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CONTRIBUTORS_BUILD_LIBRARY_URI', untrailingslashit( WP_CONTRIBUTORS_URL . 'assets' ) );

// Autoload required classes.
require_once WP_CONTRIBUTORS_PATH . 'includes/classes/admin/class-wp-contributors-activator.php';
require_once WP_CONTRIBUTORS_PATH . 'includes/classes/admin/class-wp-contributors-deactivator.php';
require_once WP_CONTRIBUTORS_PATH . 'includes/classes/admin/class-wp-contributors-admin.php';
require_once WP_CONTRIBUTORS_PATH . 'includes/classes/frontend/class-wp-contributors-frontend.php';

// Activation hook.
register_activation_hook( __FILE__, array( 'WP_Contributors_Activator', 'activate' ) );

// Deactivation hook.
register_deactivation_hook( __FILE__, array( 'WP_Contributors_Deactivator', 'deactivate' ) );

/**
 * Begins execution of the plugin.
 *
 * @return void
 * @since    1.0.0
 */
function wp_contributors_run() {
	$admin    = new WP_Contributors_Admin();
	$frontend = new WP_Contributors_Frontend();

	$admin->run();
	$frontend->run();
}
wp_contributors_run();
