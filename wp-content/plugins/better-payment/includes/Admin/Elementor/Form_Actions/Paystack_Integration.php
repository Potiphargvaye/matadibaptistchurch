<?php

namespace Better_Payment\Lite\Admin\Elementor\Form_Actions;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Better_Payment\Lite\Traits\Helper;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;

/**
 * Paystack integration class
 *
 * @since 0.0.1
 */
class Paystack_Integration extends Action_Base {
    use Helper;

    private $better_payment_global_settings = [];

    public function __construct() {
        wp_enqueue_script( 'better-payment' );
    }

    public function get_name() {
        return 'paystack';
    }

    public function get_label() {
        return __( 'Paystack', 'better-payment' );
    }

    public function get_script_depends() {
        return [ 'better-payment' ];
    }

    /**
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section( $widget ) {
        $this->better_payment_global_settings = DB::get_settings();

        $widget->start_controls_section(
            'section_paystack_payment',
            [
                'label'     => __( 'Paystack', 'better-payment' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                    'better_payment_payment_amount_enable' => 'yes'
                ],
            ]
        );

        $better_payment_helper = new \Better_Payment\Lite\Classes\Helper();

        $widget->add_control(
            'better_payment_form_paystack_currency',
            [
                'label'   => esc_html__( 'Currency Symbols', 'better-payment' ),
                'type'    => Controls_Manager::SELECT,
                'default' => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_currency']),
                'options' => $better_payment_helper->get_currency_list(),
            ]
        );

        $widget->add_control(
			'better_payment_paystack_currecny_support_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Currency is not supported by Paystack!', 'better-payment' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'better_payment_form_paystack_currency!' => $this->bp_supported_currencies( 'paystack' ),
                ],
			]
		);

        $better_payment_is_paystack_live = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode'] ) && 'yes' === $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode'] ? 1 : 0;
        $paystack_live_key = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_public'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_public'] : '';
        $paystack_test_key = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_public'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_public'] : '';
        $paystack_live_secret = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_secret'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_secret'] : '';
        $paystack_test_secret = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_secret'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_secret'] : '';

        $widget->add_control(
            'better_payment_paystack_public_key',
            [
                'label'       => __( 'Public Key', 'better-payment' ),
                'type'        => current_user_can('manage_options') ? Controls_Manager::TEXT : Controls_Manager::HIDDEN,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_paystack_live ? esc_html( $paystack_live_key ) : esc_html( $paystack_test_key ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'better_payment_paystack_secret_key',
            [
                'label'       => __( 'Secret Key', 'better-payment' ),
                'type'        => current_user_can('manage_options') ? Controls_Manager::TEXT : Controls_Manager::HIDDEN,
                'input_type'  => 'password',
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_paystack_live ? esc_html( $paystack_live_secret ) : esc_html( $paystack_test_secret ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'better_payment_paystack_live_mode',
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
            $element[ 'settings' ][ 'better_payment_form_paystack_currency' ],
            $element[ 'settings' ][ 'better_payment_paystack_public_key' ],
            $element[ 'settings' ][ 'better_payment_paystack_secret_key' ],
            $element[ 'settings' ][ 'better_payment_paystack_live_mode' ]
        );
        return $element;
    }

    /**
     * @param Form_Record $record
     * @param Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {

        $secret_key  = $record->get_form_settings( 'better_payment_paystack_secret_key' );
        $post_data   = [];
        $header_info = array(
            'Authorization'  => 'Bearer ' . sanitize_text_field( $secret_key ),
            "Cache-Control: no-cache",
        );

        $sent_data = $record->get( 'sent_data' );

        $el_form_form_settings = $record->get('form_settings');
        $page_id = $el_form_form_settings['form_post_id'] ? intval($el_form_form_settings['form_post_id']) : 0; 
        $widget_id = $el_form_form_settings['id'] ? sanitize_text_field($el_form_form_settings['id']) : ''; 

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
            $quantity = intval( $sent_data[ 'pay_quantity' ][ 'value' ] );
            $amount   *= $quantity;
        }

        $order_id = 'paystack_' . uniqid();

        $redirection_url_success    = get_permalink( $page_id );
        $redirection_url_error      = get_permalink( $page_id );

        $request  = [
            'amount'   => ( $amount * 100 ),
            'cart_id'  => $order_id,
            'callback_url'  => add_query_arg( [
                'better_payment_paystack_status' => 'success',
                'better_payment_paystack_id'     => $order_id,
                'better_payment_widget_id'     => $widget_id
            ], $redirection_url_success ),
        ];

        if ( ! empty( $sent_data[ 'email' ] ) ) {
            $request['payment_intent_data']['metadata']['customer_email'] = $request['metadata']['customer_email'] = $request['email'] = sanitize_email( $sent_data['email'] );
        }

        if ( ! empty( $sent_data['name'] ) ) {
            $request['customer_name'] = ! empty( $request['customer_name'] ) ? $request['customer_name'] . ' ' . sanitize_text_field( $sent_data['name'] ) : sanitize_text_field( $sent_data['name'] );
        }

        if ( !empty( $sent_data['first_name'] ) ) {
            $request['customer_name'] = ! empty( $request['customer_name'] ) ? $request['customer_name'] . ' ' . sanitize_text_field( $sent_data['first_name'] ) : sanitize_text_field( $sent_data['first_name'] );
        }

        if ( !empty( $sent_data['last_name'] ) ) {
            $request['customer_name'] = !empty( $request['customer_name'] ) ? $request['customer_name'] . ' ' . sanitize_text_field( $sent_data['last_name'] ) : sanitize_text_field( $sent_data['last_name'] );
        }

        if ( !empty( $request['customer_name'] ) ) {
            $request['payment_intent_data']['metadata']['customer_name'] = $request['metadata']['customer_name'] = $request['customer_name'];
            unset( $request['customer_name'] );
        }

        $response = wp_safe_remote_post(
            'https://api.paystack.co/transaction/initialize',
            array(
                'method'  => 'POST',
                'headers' => $header_info,
                'body'    => $request,
                'timeout' => 70,
            )
        );

        $response_ar = json_decode( $response['body'] );
        
        if ( ! empty( $response_ar->error ) ) {
            $ajax_handler->add_error_message( sanitize_text_field( $response_ar->error->message ) );
            return false;
        }

        $paystack_supported_currencies = $this->bp_supported_currencies( 'paystack' );
        $currency_code                 = sanitize_text_field( $record->get_form_settings( 'better_payment_form_paystack_currency' ) );
        
        if ( is_array( $paystack_supported_currencies ) && ! in_array( $currency_code, $paystack_supported_currencies ) ) {
            $ajax_handler->add_error_message( 'Currency is not supported by Paystack!' );
            return false;
        }

        if ( ! ( empty( $response_ar->status ) || empty( $response_ar->data ) ) ) {
            
            //Form fields data to send via email
            $better_form_fields = [
                'email' => ! empty( $sent_data['email'] ) ? sanitize_email( $sent_data['email'] ) : '',
                'amount' => $currency_code . floatval( $amount ),
                'el_form_fields' => maybe_serialize($sent_data),
                'referer_page_id' => $page_id,
                'referer_widget_id' => $widget_id,
                'source' => 'paystack'
            ];
            
            Handler::payment_create(
                [
                    'amount'         => $amount,
                    'order_id'       => $order_id,
                    'payment_date'   => date( 'Y-m-d H:i:s' ),
                    'source'         => 'paystack',
                    'transaction_id' => '',
                    'customer_info'  => maybe_serialize( $response_ar ),
                    'form_fields_info' => maybe_serialize( $better_form_fields ),
                    'obj_id'         => !empty($response_ar->id) ? sanitize_text_field($response_ar->id) : '',
                    'status'         => 'unpaid',
                    'currency'       => $currency_code,
                    'referer'        => "elementor-form",
                ]
            );

            $authorization_url = ! empty( $response_ar->data->authorization_url ) ? esc_url_raw( $response_ar->data->authorization_url ) : '';

            $ajax_handler->add_response_data( 'better_paystack_data', [
                'authorization_url' => esc_url_raw( $authorization_url ),
            ] );
        }
    }
}


