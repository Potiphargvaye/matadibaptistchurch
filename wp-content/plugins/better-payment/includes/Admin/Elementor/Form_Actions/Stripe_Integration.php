<?php

namespace Better_Payment\Lite\Admin\Elementor\Form_Actions;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;

/**
 * Stripe integration class
 *
 * @since 0.0.1
 */
class Stripe_Integration extends Action_Base {
    private $better_payment_global_settings = [];

    public function __construct() {
        wp_enqueue_script( 'better-payment' );
    }

    public function get_name() {
        return 'stripe';
    }

    public function get_label() {
        return __( 'Stripe', 'better-payment' );
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
            'section_stripe_payment',
            [
                'label'     => __( 'Stripe', 'better-payment' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                    'better_payment_payment_amount_enable' => 'yes'
                ],
            ]
        );

        $better_payment_helper = new \Better_Payment\Lite\Classes\Helper();

        $widget->add_control(
            'better_payment_form_stripe_currency',
            [
                'label'   => esc_html__( 'Currency', 'better-payment' ),
                'type'    => Controls_Manager::SELECT,
                'default' => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_currency']),
                'options' => $better_payment_helper->get_currency_list(),
            ]
        );

        $widget->add_control(
            'better_payment_form_currency_alignment_stripe',
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

        $better_payment_is_stripe_live = $this->better_payment_global_settings['better_payment_settings_payment_stripe_live_mode'] == 'yes' ? 1 : 0;

        $widget->add_control(
            'better_payment_stripe_public_key',
            [
                'label'       => __( 'Public Key', 'better-payment' ),
                'type'        => current_user_can('manage_options') ? Controls_Manager::TEXT : Controls_Manager::HIDDEN,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_stripe_live ? esc_html($this->better_payment_global_settings['better_payment_settings_payment_stripe_live_public']) : esc_html($this->better_payment_global_settings['better_payment_settings_payment_stripe_test_public']),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'better_payment_stripe_secret_key',
            [
                'label'       => __( 'Secret Key', 'better-payment' ),
                'type'        => current_user_can('manage_options') ? Controls_Manager::TEXT : Controls_Manager::HIDDEN,
                'input_type'  => 'password',
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_stripe_live ? esc_html($this->better_payment_global_settings['better_payment_settings_payment_stripe_live_secret']) : esc_html($this->better_payment_global_settings['better_payment_settings_payment_stripe_test_secret']),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'better_payment_stripe_live_mode',
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
            $element[ 'settings' ][ 'better_payment_form_stripe_currency' ],
            $element[ 'settings' ][ 'better_payment_stripe_public_key' ],
            $element[ 'settings' ][ 'better_payment_stripe_secret_key' ],
            $element[ 'settings' ][ 'better_payment_stripe_live_mode' ]
        );
        return $element;
    }

    /**
     * @param Form_Record $record
     * @param Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {
		// $settings = $record->get( 'form_settings' );
        $secret_key  = $record->get_form_settings( 'better_payment_stripe_secret_key' );
        $post_data   = [];
        $header_info = array(
            'Authorization'  => 'Basic ' . base64_encode( $secret_key . ':' ),
            'Stripe-Version' => '2019-05-16'
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

        $order_id = 'stripe_' . uniqid();
        $request  = [
            'success_url'                => add_query_arg( [
                'better_payment_stripe_status' => 'success',
                'better_payment_stripe_id'     => $order_id,
                'better_payment_widget_id'     => sanitize_text_field( $record->get( 'form_settings' )[ 'id' ] )
            ], get_permalink() ),
            'cancel_url'                 => add_query_arg( [
                'better_payment_error_status' => 'error',
                'better_payment_stripe_id'    => $order_id,
                'better_payment_widget_id'    => sanitize_text_field( $record->get( 'form_settings' )[ 'id' ] )
            ], get_permalink() ),
            'locale'                     => 'auto',
            'payment_method_types'       => [ 'card' ],
            'client_reference_id'        => time(),
            'billing_address_collection' => 'required',
            'metadata'                   => [
                'order_id' => $order_id
            ],
            'line_items'                 => [
                [
                    'amount'   => ( $amount * 100 ),
                    'currency' => sanitize_text_field($record->get_form_settings( 'better_payment_form_stripe_currency' )),
                    'name'     => sanitize_text_field( $record->get_form_settings( 'form_name' ) ),
                    'quantity' => $quantity
                ]
            ],
            'payment_intent_data'        => [
                'capture_method' => 'automatic',
                'description'    => sanitize_text_field( $record->get_form_settings( 'form_name' ) ),
                'metadata'       => [
                    'order_id' => $order_id
                ]
            ]
        ];

        if ( !empty( $sent_data[ 'email' ] ) ) {
            $request[ 'payment_intent_data' ][ 'metadata' ][ 'customer_email' ] = $request[ 'metadata' ][ 'customer_email' ] = $request[ 'customer_email' ] = sanitize_email( $sent_data[ 'email' ] );
        }

        if ( ! empty( $sent_data[ 'name' ] ) ) {
            $request['customer_name'] = ! empty( $request['customer_name'] ) ? $request['customer_name'] . ' ' . sanitize_text_field( $sent_data['name'] ) : sanitize_text_field( $sent_data['name'] );
        }
        
        if ( ! empty( $sent_data['first_name'] ) ) {
            $request['customer_name'] = ! empty( $request['customer_name'] ) ? $request[ 'customer_name' ] . ' ' . sanitize_text_field( $sent_data['first_name'] ) : sanitize_text_field( $sent_data['first_name'] );
        }

        if ( !empty( $sent_data[ 'last_name' ] ) ) {
            $request['customer_name'] = ! empty( $request['customer_name'] ) ? $request['customer_name'] . ' ' . sanitize_text_field( $sent_data['last_name'] ) : sanitize_text_field( $sent_data['last_name'] );
        }

        if ( ! empty( $request['customer_name'] ) ) {
            $request['payment_intent_data']['metadata']['customer_name'] = $request['metadata']['customer_name'] = $request['customer_name'];
            unset( $request['customer_name'] );
        }

        $response = wp_safe_remote_post(
            'https://api.stripe.com/v1/checkout/sessions',
            array(
                'method'  => 'POST',
                'headers' => $header_info,
                'body'    => $request,
                'timeout' => 70,
            )
        );

        $response_ar = json_decode( $response[ 'body' ] );

        if ( ! empty( $response_ar->error ) ) {
            $ajax_handler->add_error_message( sanitize_text_field( $response_ar->error->message ) );
            return false;
        }

        if ( !empty( $response_ar->payment_intent ) ) {
            
            //Form fields data to send via email
            $better_form_fields = [
                'primary_first_name' => ! empty( $request['payment_intent_data']['metadata']['customer_name'] ) ? sanitize_text_field( $request['payment_intent_data']['metadata']['customer_name'] ) : '',
                'email' => !empty( $sent_data[ 'email' ]) ? sanitize_email($sent_data[ 'email' ]) : '',
                'amount' => sanitize_text_field($record->get_form_settings( 'better_payment_form_stripe_currency' )) . floatval( $amount ),
                'el_form_fields' => maybe_serialize($sent_data),
                'referer_page_id' => $page_id,
                'referer_widget_id' => $widget_id,
                'source' => 'stripe'
            ];
            
            Handler::payment_create(
                [
                    'amount'         => $amount,
                    'order_id'       => $order_id,
                    'payment_date'   => date( 'Y-m-d H:i:s' ),
                    'source'         => 'stripe',
                    'transaction_id' => sanitize_text_field($response_ar->payment_intent),
                    'customer_info'  => maybe_serialize( $response_ar ),
                    'form_fields_info' => maybe_serialize( $better_form_fields ),
                    'obj_id'         => sanitize_text_field($response_ar->id),
                    'status'         => sanitize_text_field($response_ar->payment_status),
                    'currency'       => sanitize_text_field($record->get_form_settings( 'better_payment_form_stripe_currency' )),
                    'referer'        => "elementor-form",
                ]
            );
            $stripe_response = [
                'stripe_data'       => sanitize_text_field($response_ar->id),
                'stripe_public_key' => sanitize_text_field( $record->get_form_settings( 'better_payment_stripe_public_key' ) )
            ];

            $ajax_handler->add_response_data( 'better_stripe_data', $stripe_response );
        }
    }
}


