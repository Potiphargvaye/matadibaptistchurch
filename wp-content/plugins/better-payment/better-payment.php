<?php

/**
 * Plugin Name: Better Payment
 * Description: Better Payment allows you to automate payment transactions to manage donations, make payments, sell products, and more on your Elementor website.
 * Plugin URI: https://wpdeveloper.com/
 * Author: WPDeveloper
 * Version: 1.3.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author URI: https://wpdeveloper.com/
 * Text Domain: better-payment
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The plugin main class
 * 
 * @since 0.0.1
 */
final class Better_Payment {

    /**
     * Plugin version
     *
     * @var string
     * @since 0.0.1
     */
    const version = '1.3.2';

    /**
     * Class construcotr
     * 
     * @since 0.0.1
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Initialize a singleton instance
     *
     * @return \Better_Payment
     * @since 0.0.1
     */
    public static function init() {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     * @since 0.0.1
     */
    public function define_constants() {
        define('BETTER_PAYMENT_VERSION', self::version);
        define('BETTER_PAYMENT_FILE', __FILE__);
        define('BETTER_PAYMENT_BASENAME', plugin_basename(__FILE__));
        define('BETTER_PAYMENT_PATH', __DIR__);
        define('BETTER_PAYMENT_URL', plugins_url('', BETTER_PAYMENT_FILE));
        define('BETTER_PAYMENT_ASSETS', BETTER_PAYMENT_URL . '/assets');
        define('BETTER_PAYMENT_ASSETS_PATH', BETTER_PAYMENT_PATH . '/assets');
        define('BETTER_PAYMENT_DEV_ASSETS', BETTER_PAYMENT_URL . '/bpbuild');
        define('BETTER_PAYMENT_DEV_ASSETS_PATH', BETTER_PAYMENT_PATH . '/bpbuild');
        define('BETTER_PAYMENT_INCLUDES_PATH', BETTER_PAYMENT_PATH . '/includes');
        define('BETTER_PAYMENT_ADMIN_PATH', BETTER_PAYMENT_INCLUDES_PATH . '/Admin');
        define('BETTER_PAYMENT_ADMIN_VIEWS_PATH', BETTER_PAYMENT_ADMIN_PATH . '/views');
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     * @since 0.0.1
     */
    public function activate() {
        $installer = new Better_Payment\Lite\Installer();
        $installer->run();
    }

    /**
     * Initialize the plugin
     *
     * @return void
     * @since 0.0.1
     */
    public function init_plugin() {
        new Better_Payment\Lite\Assets();

        if (defined('DOING_AJAX') && DOING_AJAX) {
            new Better_Payment\Lite\Ajax();
        }

        if (is_admin()) {
            $adminObj = new Better_Payment\Lite\Admin();
            $adminObj->init();
        } else {
            new Better_Payment\Lite\Frontend();
        }

        new Better_Payment\Lite\API();

        if (defined('ELEMENTOR_VERSION')) {
            new Better_Payment\Lite\Classes\Actions();
            $el_integration = new Better_Payment\Lite\Admin\Elementor\EL_Integration();
            $el_integration->init();

            Better_Payment\Lite\Admin\Settings::save_default_settings();
        }
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Better_Payment
 * @since 0.0.1
 */

Better_Payment::init();

/**
 * Plugin migrator
 *
 * @since 0.0.2
 */
function better_payment_migrator() {
    Better_Payment\Lite\Classes\Migrator::migrator();
}


/**
 * On wp load
 * 
 * @return void
 * @since 0.0.1
 */
add_action('wp_loaded', function () {
    if (get_option('better_payment_version') != BETTER_PAYMENT_VERSION) {
        better_payment_migrator();
        update_option('better_payment_version', BETTER_PAYMENT_VERSION);
    }

    $setup_wizard = get_option('better_payment_setup_wizard');

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return;
    }
    
    if ($setup_wizard == 'redirect') {
        Better_Payment\Lite\Admin\Setup_Wizard::redirect();
    }

    if ($setup_wizard == 'init') {
        new Better_Payment\Lite\Admin\Setup_Wizard();
    }
});

/**
 * Dispatch actions
 *
 * @since 0.0.1
 */
\Better_Payment\Lite\Admin::dispatch_actions();
