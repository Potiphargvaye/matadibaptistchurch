<?php

/**
 *
 * @link              https://easy.jobs
 * @since             1.0.0
 * @package           easyjobs
 *
 * @wordpress-plugin
 * Plugin Name:       easy.jobs
 * Plugin URI:        https://easy.jobs
 * Description:       Easy solution for the job recruitment to attract, manage & hire right talent faster.
 * Version:           2.6.0
 * Author:            easy.jobs
 * Author URI:        https://easy.jobs
 * License:           GPL-3.0+
 * License URI:       https://opensource.org/licenses/GPL-3.0
 * Text Domain:       easyjobs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Defines CONSTANTS for Whole plugins.
 */
define( 'EASYJOBS_VERSION', '2.6.0' );
define( 'EASYJOBS_PLUGIN_URL', 'https://easy.jobs' );
define( 'EASYJOBS_URL', plugins_url( '/', __FILE__ ) );
define( 'EASYJOBS_ADMIN_URL', EASYJOBS_URL . 'admin/' );
define( 'EASYJOBS_PUBLIC_URL', EASYJOBS_URL . 'public/' );
define( 'EASYJOBS_FILE', __FILE__ );
define( 'EASYJOBS_BASENAME', plugin_basename( __FILE__ ) );
define( 'EASYJOBS_ROOT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EASYJOBS_ADMIN_DIR_PATH', EASYJOBS_ROOT_DIR_PATH . 'admin/' );
define( 'EASYJOBS_PUBLIC_PATH', EASYJOBS_ROOT_DIR_PATH . 'public/' );
define( 'EASYJOBS_TEXTDOMAIN', 'easyjobs' );
define( 'EASYJOBS_APPLY_URL', esc_url( 'https://app.easy.jobs/job/a/' ) );

if ( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) ) {
    define( 'EASYJOBS_APP_URL', esc_url( 'https://app.easyjobs.dev' ) );
    define( 'EASYJOBS_ANALYTICS_URL', esc_url( '//matomo.easyjobs.dev/' ) );
} else {
    define( 'EASYJOBS_APP_URL', esc_url( 'https://app.easy.jobs' ) );
	define( 'EASYJOBS_ANALYTICS_URL', esc_url( '//analytics.easy.jobs/' ) );
}
define( 'EASYJOBS_API_URL', EASYJOBS_APP_URL . '/api/v1/' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easyjobs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easyjobs() {
	$plugin = new Easyjobs();
	$plugin->run();
}
run_easyjobs();
