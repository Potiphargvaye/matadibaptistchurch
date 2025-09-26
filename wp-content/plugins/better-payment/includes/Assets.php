<?php

namespace Better_Payment\Lite;

use Better_Payment\Lite\Traits\Helper as TraitsHelper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets handler class
 * 
 * @since 0.0.1
 */
class Assets extends Controller {
    use TraitsHelper;
    /**
     * Class constructor
     * 
     * @since 0.0.1
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     * @since 0.0.1
     */
    public function get_scripts() {
        return [
            'better-payment-common-script' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/js/common.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/js/common.min.js' ),
                'deps'    => [ 'jquery', 'wp-util' ]
            ],
            'better-payment-stripe' => [
                'src'     => 'https://js.stripe.com/v3/',
            ],
            'better-payment' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/js/better-payment.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/js/better-payment.min.js' ),
                'deps'    => [ 'jquery', 'better-payment-stripe', 'toastr-js' ]
            ],
            'toastr-js' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/toastr/js/toastr.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/vendor/toastr/js/toastr.min.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'bp-admin-settings' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/js/admin.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/js/admin.min.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'toastr-js-admin' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/toastr/js/toastr.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/vendor/toastr/js/toastr.min.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'sweetalert2-js' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/sweetalert2/js/sweetalert2.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/vendor/sweetalert2/js/sweetalert2.min.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'better-payment-script' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/js/frontend.min.js',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/js/frontend.min.js' ),
                'deps'    => [ 'jquery' ]
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     * @since 0.0.1
     */
    public function get_styles() {
        return [
            'better-payment-common-style' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/css/common.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/css/common.min.css' )
            ],
            'better-payment-el' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/css/better-payment-el.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/css/better-payment-el.min.css' )
            ],
            'bp-icon-front' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/icon/style.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/icon/style.min.css' )
            ],
            'toastr-css' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/toastr/css/toastr.min.css',
            ],
            'jquery-ui' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/jquery-ui/css/jquery-ui.min.css',
            ],
            'bp-settings-style' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/css/style.min.css',
            ],
            'bp-icon-admin' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/icon/style.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/icon/style.min.css' )
            ],            
            'toastr-css-admin' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/toastr/css/toastr.min.css',
            ],
            'sweetalert2-css' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/vendor/sweetalert2/css/sweetalert2.min.css',
            ],
            'better-payment-style' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/css/frontend.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/css/frontend.min.css' )
            ],
            'better-payment-admin-style' => [
                'src'     => BETTER_PAYMENT_ASSETS . '/css/admin.min.css',
                'version' => filemtime( BETTER_PAYMENT_PATH . '/assets/css/admin.min.css' )
            ],
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     * @since 0.0.1
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $version = isset( $script['version'] ) ? $script['version'] : false;
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $version, true );
        }

        foreach ( $styles as $handle => $style ) {
            $version = isset( $style['version'] ) ? $style['version'] : false;
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $version );
        }

        wp_localize_script( 'better-payment', 'betterPayment', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'better-payment' ),
            'confirm' => __( 'Are you sure?', 'better-payment' ),
            'error' => __( 'Something went wrong', 'better-payment' ),
            'custom_texts' => [
                'redirecting' => __('Redirecting', 'better-payment'),
                // 'field' => __('Field', 'better-payment'),
                // 'required' => __('Required', 'better-payment'),
                'field_is_required' => __('field is required', 'better-payment'),
                'business_email_is_required' => __('Business Email is required', 'better-payment'),
                'payment_amount_field_is_required' => __('Payment Amount field is required', 'better-payment'),
                'minimum_amount_is_one' => __('Minimum amount is 1', 'better-payment'),
                'something_went_wrong' => __( 'Something went wrong', 'better-payment' ),
            ],
            'currency_symbols' => $this->get_currency_symbols_list(),
        ] );

        wp_localize_script('bp-admin-settings', 'betterPaymentObj', array(
			'nonce'  => wp_create_nonce('better_payment_admin_nonce'),
			'alerts' => [
				'confirm' => __('Are you sure?', 'better-payment'),
				'confirm_description' => __("You won't be able to revert this!", 'better-payment'),
				'yes' => __('Yes, delete it!', 'better-payment'),
				'no' => __('No, cancel!', 'better-payment'),
			],
			'messages' => [
				'success' => __('Changes saved successfully!', 'better-payment'),
				'error' => __('Opps! something went wrong!', 'better-payment'),
				'no_action_taken' => __('No action taken!', 'better-payment'),
            ]
		));
    }
}
