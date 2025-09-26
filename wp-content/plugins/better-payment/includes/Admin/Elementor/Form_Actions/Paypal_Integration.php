<?php

namespace Better_Payment\Lite\Admin\Elementor\Form_Actions;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Better_Payment\Lite\Traits\Helper;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PayPal integration class
 *
 * @since 0.0.1
 */
class Paypal_Integration extends Action_Base {
    use Helper;

    private $better_payment_global_settings = [];

    public function get_name() {
        return 'paypal';
    }

    public function get_label() {
        return __( 'PayPal', 'better-payment' );
    }

    /**
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section( $widget ) {
        $this->better_payment_global_settings = DB::get_settings();

        $widget->start_controls_section(
            'section_paypal_payment',
            [
                'label'     => __( 'PayPal', 'better-payment' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                    'better_payment_payment_amount_enable' => 'yes'
                ],
            ]
        );

        $better_payment_helper = new \Better_Payment\Lite\Classes\Helper();

        $widget->add_control(
            'better_payment_form_paypal_currency',
            [
                'label'   => esc_html__( 'Currency', 'better-payment' ),
                'type'    => Controls_Manager::SELECT,
                'default' => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_currency']),
                'options' => $better_payment_helper->get_currency_list(),
            ]
        );

        $widget->add_control(
			'better_payment_paypal_currecny_support_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Currency is not supported by PayPal!', 'better-payment' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'better_payment_form_paypal_currency' => $this->bp_unsupported_currencies( 'paypal' ),
                ],
			]
		);

        $widget->add_control(
            'better_payment_form_currency_alignment_paypal',
            [
                'label' => esc_html__( 'Currency Alignment', 'better-payment' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'better-payment' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'better-payment' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
            ]
        );

        $widget->add_control(
            'better_payment_paypal_business_email',
            [
                'label'       => __( 'Business Email', 'better-payment' ),
                'type'        => current_user_can('manage_options') ? Controls_Manager::TEXT : Controls_Manager::HIDDEN,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html($this->better_payment_global_settings['better_payment_settings_payment_paypal_email']),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'better_payment_paypal_button_type',
            [
                'label'   => esc_html__( 'Button Type', 'better-payment' ),
                'type'    => Controls_Manager::HIDDEN,
                'default' => '_xclick',
                'options' => [
                    '_xclick'    => 'XCLICK',
                    '_cart'      => 'CART',
                    '_donations' => 'DONATIONS'
                ]
            ]
        );

        $widget->add_control(
            'better_payment_paypal_live_mode',
            [
                'label'        => __( 'Live Mode', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html($this->better_payment_global_settings['better_payment_settings_payment_paypal_live_mode']), //yes or no
            ]
        );
        $widget->end_controls_section();
    }

    /**
     * @param array $element
     * @return array
     */
    public function on_export( $element ) {
        unset(
            $element[ 'settings' ][ 'better_payment_paypal_business_email' ],
            $element[ 'settings' ][ 'better_payment_paypal_button_type' ],
            $element[ 'settings' ][ 'better_payment_paypal_live_mode' ],
            $element[ 'settings' ][ 'better_payment_form_paypal_currency' ]
        );

        return $element;
    }

    /**
     * @param Form_Record $record
     * @param Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {

        $email       = $record->get_form_settings( 'better_payment_paypal_business_email' );
        $button_type = $record->get_form_settings( 'better_payment_paypal_button_type' );
        $live_mode   = $record->get_form_settings( 'better_payment_paypal_live_mode' );

        if ( $live_mode == 'yes' ) {
            $path = "paypal";
        } else {
            $path = "sandbox.paypal";
        }

        if ( empty( $email ) ) {
            $ajax_handler->add_error_message( 'Business Email is required!' );
            return false;
        }

        // Get submitted Form data
        $sent_data = $record->get( 'sent_data' );

        $el_form_form_settings = $record->get('form_settings');
        $page_id = $el_form_form_settings['form_post_id'] ? $el_form_form_settings['form_post_id'] : ''; 
        $widget_id = $el_form_form_settings['id'] ? $el_form_form_settings['id'] : ''; 

        if ( empty( $sent_data['payment_amount'] ) && empty( $sent_data['primary_payment_amount_radio'] ) ) {
            $ajax_handler->add_error_message( 'Amount field is required!' );
            return false;
        }

        $amount   = isset( $sent_data['payment_amount'] ) ? floatval($sent_data['payment_amount']) : 0;

        if ( empty( $amount ) && ! empty( $sent_data['primary_payment_amount_radio'] ) ) {
            $amount = floatval( $sent_data['primary_payment_amount_radio'] );
        }

        $quantity = 1;
        if ( !empty( $sent_data[ 'pay_quantity' ] ) ) {
            $quantity = intval( $sent_data[ 'pay_quantity' ] );
        }

        $return_url = get_the_permalink() . '?better_payment_paypal_status=success&better_payment_widget_id=' . sanitize_text_field( $record->get( 'form_settings' )[ 'id' ] );
        $cancel_url = get_the_permalink() . '?better_payment_error_status=error&better_payment_widget_id=' . sanitize_text_field( $record->get( 'form_settings' )[ 'id' ] );
        $order_id   = 'paypal_' . uniqid();

        $paypal_unsupported_currencies = $this->bp_unsupported_currencies( 'paypal' );
        $currency_code                 = sanitize_text_field( $record->get_form_settings( 'better_payment_form_paypal_currency' ) );
        if ( is_array( $paypal_unsupported_currencies ) && in_array( $currency_code, $paypal_unsupported_currencies ) ) {
            $ajax_handler->add_error_message( 'Currency is not supported by PayPal!' );
            return false;
        }

        $request_data = [
            'business'      => $email,
            'currency_code' => $currency_code,
            'rm'            => '2',
            'return'        => esc_url_raw( $return_url ),
            'cancel_return' => esc_url_raw( $cancel_url ),
            'item_number'   => $order_id,
            'quantity'      => $quantity,
            'item_name'     => 'Test Product',
            'amount'        => $amount ,
            'cmd'           => $button_type,
        ];

        $customer_name = ! empty( $sent_data['name'] ) ? $sent_data['name'] : '';
        $customer_name = ! empty( $sent_data['first_name'] ) ? $customer_name . ' ' .$sent_data['first_name'] : $customer_name;
        $customer_name = ! empty( $sent_data['last_name'] ) ? $customer_name . ' ' .$sent_data['last_name'] : $customer_name;
        
        $customer_email = ! empty( $sent_data['email'] ) ? sanitize_text_field( $sent_data['email'] ) : '';
        $customer_email = ! empty( $sent_data['email_address'] ) ? sanitize_text_field( $sent_data['email_address'] ) : $customer_email;

        $better_form_fields = [
            'primary_first_name'    => $customer_name,
            'email'                 => $customer_email,
            'el_form_fields'        => maybe_serialize( $sent_data ),
            'amount'                => $currency_code . $amount,
            'referer_page_id'       => $page_id,
            'referer_widget_id'     => $widget_id,
            'source'                => 'paypal'
        ];

        Handler::payment_create(
            [
                'amount'       => $amount,
                'order_id'     => $order_id,
                'payment_date' => date( 'Y-m-d H:i:s' ),
                'source'       => 'paypal',
                'form_fields_info' => maybe_serialize( $better_form_fields ),
                'currency'     => $currency_code,
                'referer'      => "elementor-form",
            ]
        );
        $paypal_url = "https://www.$path.com/cgi-bin/webscr?";
        $paypal_url .= http_build_query( $request_data );

        if ( !empty( $paypal_url ) && filter_var( $paypal_url, FILTER_VALIDATE_URL ) ) {
            $ajax_handler->add_response_data( 'redirect_url', esc_url_raw($paypal_url) );
        }
    }
}


