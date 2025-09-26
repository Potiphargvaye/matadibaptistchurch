<?php

namespace Better_Payment\Lite\Admin\Elementor;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Admin\Elementor\Form_Actions\Payment_Amount_Field;
use Better_Payment\Lite\Admin\Elementor\Form_Actions\Payment_Amount_Integration;
use Better_Payment\Lite\Admin\Elementor\Form_Actions\Paypal_Integration;
use Better_Payment\Lite\Admin\Elementor\Form_Actions\Stripe_Integration;
use Better_Payment\Lite\Admin\Elementor\Form_Actions\Paystack_Integration;
use Better_Payment\Lite\Classes\Handler;

use function Better_Payment\Lite\Classes\better_payment_dd;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The elementor integration class
 *
 * @since 0.0.1
 */
class EL_Integration {

    private $payment_amount = 'payment_amount';

    /**
     * Init
     *
     * @since 0.0.1
     */
    public function init() {
        add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
        add_action( 'elementor/widgets/register', [ $this, 'elementor_form_integration' ], 10 );
        add_action( 'elementor-pro/forms/pre_render', [ $this, 'elementor_pro_form_response' ], 10, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'conditional_enqueue_assets' ] );
    }

    /**
     * Register widget
     *
     * @since 0.0.1
     */
    public function register_widget( $widgets_manager ) {
		$bp_admin_saved_settings = DB::get_settings();
        $is_user_dashboard_enabled = isset( $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] ) && 'yes' === sanitize_text_field( $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] ) ? 1 : 0;
        $widgets_manager->register_widget_type( new Better_Payment_Widget() );
        
        if ( $is_user_dashboard_enabled ) {
            $widgets_manager->register_widget_type( new User_Dashboard() );
        }
    }

    /**
     * Elementor form integration
     *
     * @since 0.0.1
     */
    public function elementor_form_integration() {
        if(!defined('ELEMENTOR_PRO_VERSION')){
            return false;
        }

        wp_enqueue_style( 'better-payment-common-style' );

        \ElementorPro\Modules\Forms\Module::instance()->add_form_action( 'better-payment', new Payment_Amount_Integration() ); 
        \ElementorPro\Modules\Forms\Module::instance()->add_form_action( 'PayPal', new Paypal_Integration() );
        \ElementorPro\Modules\Forms\Module::instance()->add_form_action( 'Stripe', new Stripe_Integration() );
        \ElementorPro\Modules\Forms\Module::instance()->add_form_action( 'Paystack', new Paystack_Integration() );
        \ElementorPro\Modules\Forms\Module::instance()->add_form_field_type( 'payment_amount', new Payment_Amount_Field() );
    }

    /**
     * Elementor pro form response
     *
     * @since 0.0.1
     */
    public function elementor_pro_form_response( $settings, $obj ) {
        wp_enqueue_style( 'better-payment-el' );
        $response = Handler::manage_response( $settings, $obj->get_id() );
        if ( $response ) {
			$obj->add_render_attribute( 'form', 'style', 'display:none' );
            return false;
        }
    }

    /**
     * Conditionally enqueue assets on pages with Elementor forms
     *
     * @since 0.0.1
     */
    public function conditional_enqueue_assets() {
        // Only run on frontend
        if ( is_admin() ) {
            return;
        }

        if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) || ! \Elementor\Plugin::$instance->documents->get( get_the_ID() ) ) {
            return;
        }

        $document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
        if ( ! $document ) {
            return;
        }

        $elements_data = $document->get_elements_data();
        if ( $this->has_better_payment_form( $elements_data ) ) {
            wp_enqueue_style( 'better-payment-el' );
            wp_enqueue_style( 'bp-icon-front' );
            wp_enqueue_style( 'better-payment-style' );
            wp_enqueue_style( 'better-payment-common-style' );
            wp_enqueue_style( 'better-payment-admin-style' );

            wp_enqueue_script( 'better-payment-common-script' );
            wp_enqueue_script( 'better-payment' );
        }
    }

    /**
     * Recursively check if page has forms with Better Payment
     *
     * @param array $elements
     * @return bool
     */
    private function has_better_payment_form( $elements ) {
        foreach ( $elements as $element ) {
            if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'form' ) {
                $settings = isset( $element['settings'] ) ? $element['settings'] : [];

                // Check submit actions
                $submit_actions = isset( $settings['submit_actions'] ) ? $settings['submit_actions'] : [];
                if ( is_array( $submit_actions ) ) {
                    $better_payment_actions = [ 'better-payment', 'PayPal', 'Stripe', 'Paystack' ];
                    if ( !empty( array_intersect( $submit_actions, $better_payment_actions ) ) ) {
                        return true;
                    }
                }

                // Check payment amount field
                if ( isset( $settings['better_payment_payment_amount_enable'] ) &&
                     $settings['better_payment_payment_amount_enable'] === 'yes' ) {
                    return true;
                }
            }

            // Check nested elements
            if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
                if ( $this->has_better_payment_form( $element['elements'] ) ) {
                    return true;
                }
            }
        }

        return false;
    }

}

