<?php

namespace Better_Payment\Lite\Classes;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The plugin action class
 * 
 * @since 0.0.1
 */
class Actions {
    use TraitsHelper;
    /**
     * Constructor
     * 
     * @since 0.0.1
     */
    public function __construct() {
        add_action( 'admin_post_paypal_form_handle', [ $this, 'paypal_form_handle' ] );
        add_action( 'admin_post_nopriv_paypal_form_handle', [ $this, 'paypal_form_handle' ] );

        add_action( 'wp_ajax_better_payment_stripe_get_token', [ $this, 'better_payment_stripe_get_token' ] );
        add_action( 'wp_ajax_nopriv_better_payment_stripe_get_token', [ $this, 'better_payment_stripe_get_token' ] );
        
        add_action( 'wp_ajax_better_payment_paystack_get_token', [ $this, 'better_payment_paystack_get_token' ] );
        add_action( 'wp_ajax_nopriv_better_payment_paystack_get_token', [ $this, 'better_payment_paystack_get_token' ] );
    }


    /**
     * Handle the paypal form
     * 
     * @since 0.0.1
     */
    public function paypal_form_handle() {

        check_admin_referer( 'better-payment-paypal', 'security' );

        if ( !empty( $_POST[ 'better_payment_page_id' ] ) ) {
            $page_id = intval( $_POST[ 'better_payment_page_id' ], 10 );
        } else {
            $this->redirect_previous_page();
        }

        if ( !empty( $_POST[ 'better_payment_widget_id' ] ) ) {
            $widget_id = sanitize_text_field( $_POST[ 'better_payment_widget_id' ] );
        } else {
            $this->redirect_previous_page();
        }

        $el_settings = $this->better_payment_widget_settings( $page_id, $widget_id );

        if ( empty( $el_settings ) ) {
            $this->redirect_previous_page();
        }

        if ( empty( $el_settings[ 'better_payment_paypal_business_email' ] ) ) {
            $this->redirect_previous_page();
        }

        if ( $el_settings[ 'better_payment_paypal_live_mode' ] == 'yes' ) {
            $path = "paypal";
        } else {
            $path = "sandbox.paypal";
        }

        $el_settings_currency = $el_settings[ 'better_payment_form_currency' ];
        $woo_product_id = !empty($el_settings["better_payment_form_woocommerce_product_id"]) ? intval($el_settings["better_payment_form_woocommerce_product_id"]) : 0;
        $is_woo_layout = ! empty( $el_settings[ 'better_payment_form_layout' ] ) && 'layout-6-pro' === $el_settings[ 'better_payment_form_layout' ];
        
        if(!empty($el_settings['better_payment_form_currency_use_woocommerce']) && 'yes' === $el_settings['better_payment_form_currency_use_woocommerce'] &&
        !empty($el_settings['better_payment_form_currency_woocommerce'])){
            $el_settings_currency = $el_settings['better_payment_form_currency_woocommerce'];
        }

        $el_settings_currency_symbol = $this->get_currency_symbol( esc_html($el_settings_currency) );

        $primary_payment_amount = isset( $_POST[ 'primary_payment_amount' ] ) ? floatval( $_POST[ 'primary_payment_amount' ] ) : 0;

        if( empty( $_POST['primary_payment_amount'] ) && ! empty( $_POST['primary_payment_amount_radio'] ) ){
            $primary_payment_amount = floatval( $_POST['primary_payment_amount_radio'] );
        }

        $primary_payment_amount_quantity = ! empty( $_POST['payment_amount_quantity'] ) ? intval( $_POST[ 'payment_amount_quantity' ] ) : '';
        if ( $is_woo_layout ) {
            $primary_payment_amount_quantity = 1;
        }

        $primary_payment_amount = ! empty( $primary_payment_amount_quantity ) ? $primary_payment_amount * $primary_payment_amount_quantity : $primary_payment_amount;

        $order_id     = 'paypal_' . uniqid();
        $request_data = [
            'business'      => $el_settings[ 'better_payment_paypal_business_email' ],
            'currency_code' => $el_settings_currency,
            'rm'            => '2',
            'return'        => esc_url_raw( $_POST[ 'return' ] ),
            'cancel_return' => esc_url_raw( $_POST[ 'cancel_return' ] ),
            'item_number'   => $order_id,
            'item_name'     => ! empty( $el_settings['better_payment_form_title'] ) ? esc_html__( $el_settings['better_payment_form_title'], 'better-payment' ) : esc_html__('Better Payment', 'better-payment'),
            'amount'        => $primary_payment_amount,
            'cmd'           => $el_settings[ 'better_payment_paypal_button_type' ],
        ];

        //Form fields data to send via email
        $better_form_fields = [
            'amount' => sanitize_text_field($el_settings_currency_symbol) . $primary_payment_amount,
            'referer_page_id' => $page_id,
            'referer_widget_id' => $widget_id,
            'woo_product_id' => $woo_product_id,
            'source' => 'paypal',
            'amount_quantity' => ! empty( $primary_payment_amount_quantity ) ? intval( $primary_payment_amount_quantity ) : '',
            'is_woo_layout' => $is_woo_layout,
        ];

        $better_form_fields = array_merge( $better_form_fields, $this->fetch_better_form_fields($el_settings, $_POST) );
        
        if ( !empty( $better_form_fields[ 'primary_first_name' ] ) ) {
            $request_data[ 'primary_first_name' ] = sanitize_text_field( $better_form_fields[ 'primary_first_name' ] );
        }

        if ( !empty( $better_form_fields[ 'primary_last_name' ] ) ) {
            $request_data[ 'primary_last_name' ] = sanitize_text_field( $better_form_fields[ 'primary_last_name' ] );
        }

        if ( !empty( $better_form_fields[ 'primary_email' ] ) ) {
            $request_data[ 'primary_email' ] = sanitize_email( $better_form_fields[ 'primary_email' ] );
        }
        
        Handler::payment_create(
            [
                'amount'       => floatval( $primary_payment_amount ),
                'order_id'     => $order_id,
                'payment_date' => date( 'Y-m-d H:i:s' ),
                'source'       => 'paypal',
                'form_fields_info'     => maybe_serialize( $better_form_fields ),
                'currency'     => sanitize_text_field($el_settings_currency),
                'referer'      => "widget",
            ]
        );
        $paypal_url = "https://www.$path.com/cgi-bin/webscr?";
        $paypal_url .= http_build_query( $request_data );
        wp_redirect( esc_url_raw($paypal_url) );
    }

    /**
     * Get the stripe token
     * 
     * @since 0.0.1
     */
    public function better_payment_stripe_get_token() {
        if ( !check_admin_referer( 'better-payment', 'security' ) ) {
            wp_send_json_error();
        }

        $setting_data_page_id = isset($_POST[ 'setting_data' ]['page_id']) ? intval( $_POST[ 'setting_data' ]['page_id'] ) : 0;
        $setting_data_widget_id = isset($_POST[ 'setting_data' ]['widget_id']) ? sanitize_text_field( $_POST[ 'setting_data' ]['widget_id'] ) : 0;

        if ( !empty( $setting_data_page_id ) ) {
            $page_id = $setting_data_page_id;
        } else {
            $err_msg = __( 'Page ID is missing', 'better-payment' );
            wp_send_json_error( esc_html($err_msg) );
        }

        if ( !empty( $setting_data_widget_id ) ) {
            $widget_id = sanitize_text_field( $setting_data_widget_id );
        } else {
            $err_msg = __( 'Widget ID is missing', 'better-payment' );
            wp_send_json_error( esc_html($err_msg) );
        }

        $el_settings = $this->better_payment_widget_settings( $page_id, $widget_id );
        $is_woo_layout = ! empty( $el_settings[ 'better_payment_form_layout' ] ) && 'layout-6-pro' === $el_settings[ 'better_payment_form_layout' ];

        $better_payment_keys = [
            'public_key' => 'yes' === sanitize_text_field( $el_settings[ 'better_payment_stripe_live_mode' ] ) ? sanitize_text_field( $el_settings[ 'better_payment_stripe_public_key_live' ] ) : sanitize_text_field( $el_settings[ 'better_payment_stripe_public_key' ] ),
            'secret_key' => 'yes' === sanitize_text_field( $el_settings[ 'better_payment_stripe_live_mode' ] ) ? sanitize_text_field( $el_settings[ 'better_payment_stripe_secret_key_live' ] ) : sanitize_text_field( $el_settings[ 'better_payment_stripe_secret_key' ] ),
        ];

        if ( empty( $el_settings ) ) {
            wp_send_json_error( esc_html(__( 'Setting Data is missing', 'better-payment' )) );
        }

        $is_payment_split_payment = ! empty( $el_settings["better_payment_form_payment_type"] ) && 'split-payment' === $el_settings["better_payment_form_payment_type"];
        
        $amount = isset($_POST['fields']['primary_payment_amount']) ? floatval($_POST['fields']['primary_payment_amount']) : 0;
        
        if ( empty( $_POST['fields']['primary_payment_amount'] ) && ! empty( $_POST['fields']['primary_payment_amount_radio'] ) ) {
            $amount = floatval($_POST['fields']['primary_payment_amount_radio']);
        }

        $amount_quantity = ! empty( $_POST['fields']['payment_amount_quantity'] ) ? intval( $_POST['fields']['payment_amount_quantity'] ) : '';
        
        if ( $is_woo_layout ) {
            $amount_quantity = 1;
        }

        $amount = ! empty( $amount_quantity ) ? $amount * $amount_quantity : $amount;
        
        if ( empty( $better_payment_keys['public_key'] ) || empty( $better_payment_keys['secret_key'] ) ) {
            wp_send_json_error( esc_html(__( 'Stripe Key missing', 'better-payment' )) );
        }

        $header_info = array(
            'Authorization'  => 'Basic ' . base64_encode( sanitize_text_field( $better_payment_keys['secret_key'] ) . ':' ),
            'Stripe-Version' => '2019-05-16'
        );

        $order_id = 'stripe_' . uniqid();

        $el_settings_currency = $el_settings[ 'better_payment_form_currency' ];
        
        if(!empty($el_settings['better_payment_form_currency_use_woocommerce']) && 'yes' === $el_settings['better_payment_form_currency_use_woocommerce'] &&
        !empty($el_settings['better_payment_form_currency_woocommerce'])){
            $el_settings_currency = $el_settings['better_payment_form_currency_woocommerce'];
        }

        $el_settings_currency_symbol = $this->get_currency_symbol( esc_html($el_settings_currency) );

        $request  = [
            'success_url'                => add_query_arg( [
                'better_payment_stripe_status' => 'success',
                'better_payment_stripe_id'     => $order_id,
                'better_payment_widget_id'     => $widget_id
            ], get_permalink( $setting_data_page_id )  ),
            'cancel_url'                 => add_query_arg( [
                'better_payment_error_status' => 'error',
                'better_payment_stripe_id'    => $order_id,
                'better_payment_widget_id'    => $widget_id
            ], get_permalink( $setting_data_page_id )  ),
            'locale'                     => 'auto',
            'payment_method_types'       => [ 'card' ],
            'client_reference_id'        => time(),
            'billing_address_collection' => 'required',
            'metadata'                   => [
                'order_id' => $order_id
            ],
            'line_items'                 => [
                [
                    'amount'   => (int) round( $amount * 100 ),
                    'currency' => $el_settings_currency,
                    'name'     => ! empty( $el_settings['better_payment_form_title'] ) ? esc_html__( $el_settings['better_payment_form_title'], 'better-payment' ) : esc_html__('Better Payment', 'better-payment'),
                    'quantity' => 1
                ]
            ],
            'payment_intent_data'        => [
                'capture_method' => 'automatic',
                'description'    => ! empty( $el_settings['better_payment_form_title'] ) ? esc_html__( $el_settings['better_payment_form_title'], 'better-payment' ) : esc_html__('Better Payment', 'better-payment'),
                'metadata'       => [
                    'order_id' => $order_id
                ]
            ]
        ];

        $is_payment_recurring = ! empty( $_POST['fields']['better_payment_recurring_mode'] ) && 'subscription' === sanitize_text_field( $_POST['fields']['better_payment_recurring_mode'] );
        $recurring_price_id = ! empty( $_POST['fields']['better_payment_recurring_price_id'] ) ? sanitize_text_field( $_POST['fields']['better_payment_recurring_price_id'] ) : '';
        
        //Form fields data to send via email
        $better_form_fields = [
            'amount' => $el_settings_currency_symbol . $amount,
            'referer_page_id' => $page_id,
            'referer_widget_id' => $widget_id,
            'source' => 'stripe',
            'amount_quantity' => ! empty( $amount_quantity ) ? $amount_quantity : '',
            'is_woo_layout' => $is_woo_layout,
        ];

        $better_form_fields = array_merge( $better_form_fields, $this->fetch_better_form_fields($el_settings, $_POST['fields']) );

        if ( !empty( $better_form_fields['primary_first_name'] ) ) {
            $request[ 'customer_name' ] = $better_form_fields['primary_first_name'];
        }

        if ( !empty( $better_form_fields['primary_last_name'] ) ) {
            $request[ 'customer_name' ] = !empty( $request[ 'customer_name' ] ) ? $request[ 'customer_name' ] . ' ' . $better_form_fields['primary_last_name'] : $better_form_fields['primary_last_name'];
        }

        if ( !empty( $request[ 'customer_name' ] ) ) {
            $request[ 'metadata' ][ 'customer_name' ]                          = $request[ 'customer_name' ];
            $request[ 'payment_intent_data' ][ 'metadata' ][ 'customer_name' ] = $request[ 'customer_name' ];
            unset( $request[ 'customer_name' ] );
        }

        if ( !empty( $better_form_fields['primary_email'] ) ) {
            $request[ 'customer_email' ]                                        = $better_form_fields['primary_email'];
            $request[ 'metadata' ][ 'customer_email' ]                          = $request[ 'customer_email' ];
            $request[ 'payment_intent_data' ][ 'metadata' ][ 'customer_email' ] = $request[ 'customer_email' ];

        }

        if ( $is_payment_split_payment ) {
            $installment_price_id = ! empty($_POST['fields']['split_payment_installment']) ? sanitize_text_field($_POST['fields']['split_payment_installment']) : '';
            $split_payment_installments_data = ! empty( $el_settings['better_payment_split_installment_price_ids'] ) ? $el_settings['better_payment_split_installment_price_ids'] : [];

            if ( is_array( $split_payment_installments_data ) && count( $split_payment_installments_data ) ){
                foreach( $split_payment_installments_data as $split_payment_installment_data ){
                    $item_price_id = ! empty( $split_payment_installment_data[ 'better_payment_split_installment_price_id' ] ) ? sanitize_text_field( $split_payment_installment_data[ 'better_payment_split_installment_price_id' ] ) : '';
                    
                    if ( $installment_price_id === $item_price_id ) {
                        $installment_price_iteration = ! empty( $split_payment_installment_data[ 'better_payment_split_installment_iteration' ] ) ? sanitize_text_field( $split_payment_installment_data[ 'better_payment_split_installment_iteration' ] ) : '';
                        break;
                    }
                }
            }

            $better_form_fields['is_payment_split_payment'] = 1;
            $better_form_fields['split_payment_installment_price_id'] = $installment_price_id;
            $better_form_fields['split_payment_total_amount'] = $amount;
            $better_form_fields['split_payment_total_amount_price_id'] = $recurring_price_id;
            $better_form_fields['split_payment_installment_iteration'] = $installment_price_iteration ?? 1;
        }
        
        if ( $is_payment_recurring || $is_payment_split_payment ) {
            $request['mode'] = 'subscription';
            unset($request['line_items'][0]);
            unset($request['payment_intent_data']);
            $request['line_items'][0]['price'] = $is_payment_split_payment && ! empty( $installment_price_id ) ? $installment_price_id : $recurring_price_id;
            $request['line_items'][0]['quantity'] = 1;

            $better_form_fields['mode'] = $request['mode'];
            $better_form_fields['recurring_price_id'] = $request['line_items'][0]['price'];
        }

        #ToDo: Need to prefill name field on stripe checkout
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
        
        if ( ! empty( $response_ar->payment_intent ) || ( ! empty( $response_ar->mode ) && 'subscription' === $response_ar->mode ) ) {
            Handler::payment_create(
                [
                    'amount'         => $amount,
                    'order_id'       => $order_id,
                    'payment_date'   => date( 'Y-m-d H:i:s' ),
                    'source'         => 'stripe',
                    'transaction_id' => sanitize_text_field($response_ar->payment_intent),
                    'customer_info'  => maybe_serialize( $response_ar ),
                    'form_fields_info'  => maybe_serialize( $better_form_fields ),
                    'obj_id'         => sanitize_text_field($response_ar->id),
                    'status'         => sanitize_text_field($response_ar->payment_status),
                    'currency'       => $el_settings_currency,
                    'referer'        => "widget",
                ]
            );
            wp_send_json_success(
                [
                    'stripe_data'       => sanitize_text_field($response_ar->id),
                    'stripe_public_key' => sanitize_text_field( $better_payment_keys['public_key'] )
                ]
            );
        } else {
            $error_message = 'Something went wrong!';

            if (isset($response_ar->error)){
                $error_message = sanitize_text_field($response_ar->error->message);
            }

            wp_send_json_error( $error_message );
        }
    }

    /**
     * Get the paystack token
     * 
     * @since 0.0.1
     */
    public function better_payment_paystack_get_token() {
        if ( !check_admin_referer( 'better-payment', 'security' ) ) {
            wp_send_json_error();
        }
        
        $error_message = '';

        if( empty( $_POST[ 'setting_data' ]['page_id'] ) ){
           $error_message = __( 'Page ID is missing', 'better-payment' );
        }

        if( empty( $_POST[ 'setting_data' ]['widget_id'] ) ){
            $error_message = __( 'Widget ID is missing', 'better-payment' );
        }

        if( ! empty( $error_message ) ){
            wp_send_json_error( esc_html( $error_message ) );
        }

        $page_id = intval( $_POST[ 'setting_data' ]['page_id'] );
        $widget_id = sanitize_text_field( $_POST[ 'setting_data' ]['widget_id'] );
        $el_settings = $this->better_payment_widget_settings( $page_id, $widget_id );
        $is_woo_layout = ! empty( $el_settings[ 'better_payment_form_layout' ] ) && 'layout-6-pro' === $el_settings[ 'better_payment_form_layout' ];
        
        if ( empty( $el_settings ) ) {
            wp_send_json_error( esc_html(__( 'Setting Data is missing', 'better-payment' )) );
        }

        if ( empty( $el_settings[ 'better_payment_paystack_public_key' ] ) || empty( $el_settings[ 'better_payment_paystack_secret_key' ] ) ) {
            wp_send_json_error( esc_html(__( 'Paystack Key missing', 'better-payment' )) );
        }

        $amount = isset($_POST[ 'fields' ][ 'primary_payment_amount' ]) ? floatval($_POST[ 'fields' ][ 'primary_payment_amount' ]) : 0;
        
        if ( empty( $_POST[ 'fields' ][ 'primary_payment_amount' ] ) && ! empty( $_POST[ 'fields' ][ 'primary_payment_amount_radio' ] ) ) {
            $amount = floatval($_POST[ 'fields' ][ 'primary_payment_amount_radio' ]);
        }

        $amount_quantity = ! empty( $_POST['fields']['payment_amount_quantity'] ) ? intval( $_POST['fields']['payment_amount_quantity'] ) : '';
        if ( $is_woo_layout ) {
            $amount_quantity = 1;
        }

        $amount = ! empty( $amount_quantity ) ? $amount * $amount_quantity : $amount;

        $header_info = array(
            'Authorization'  => 'Bearer ' . sanitize_text_field( $el_settings[ 'better_payment_paystack_secret_key' ] ),
            "Cache-Control: no-cache",
        );

        $order_id = 'paystack_' . uniqid();

        $el_settings_currency = $el_settings[ 'better_payment_form_currency' ];
        $woo_product_id = !empty($el_settings["better_payment_form_woocommerce_product_id"]) ? intval($el_settings["better_payment_form_woocommerce_product_id"]) : 0;
        
        if(!empty($settings['better_payment_form_currency_use_woocommerce']) && 'yes' === $el_settings['better_payment_form_currency_use_woocommerce'] &&
        !empty($settings['better_payment_form_currency_woocommerce'])){
            $el_settings_currency = $el_settings['better_payment_form_currency_woocommerce'];
        }
        
        $el_settings_currency_symbol = $this->get_currency_symbol( esc_html($el_settings_currency) );

        $redirection_url_success    = get_permalink( $page_id );
        $redirection_url_error      = get_permalink( $page_id );

        $request  = [
            'amount'   => ( $amount * 100 ),
            'currency' => $el_settings_currency,
            'cart_id'  => $order_id,
            'callback_url'  => add_query_arg( [
                'better_payment_paystack_status' => 'success',
                'better_payment_paystack_id'     => $order_id,
                'better_payment_widget_id'     => $widget_id
            ], $redirection_url_success ),
        ];
        
        //Form fields data to send via email
        $better_form_fields = [
            'amount' => $el_settings_currency_symbol . $amount,
            'referer_page_id' => $page_id,
            'referer_widget_id' => $widget_id,
            'woo_product_id' => $woo_product_id,
            'source' => 'paystack',
            'amount_quantity' => ! empty( $amount_quantity ) ? $amount_quantity : '',
            'is_woo_layout' => $is_woo_layout,
        ];

        $better_form_fields = array_merge( $better_form_fields, $this->fetch_better_form_fields($el_settings, $_POST['fields']) );

        if ( !empty( $better_form_fields['primary_email'] ) ) {
            $request[ 'email' ] = $better_form_fields['primary_email'];
        }

        if ( !empty( $better_form_fields['primary_first_name'] ) ) {
            $request[ 'customer_name' ] = $better_form_fields['primary_first_name'];
        }

        if ( !empty( $better_form_fields['primary_last_name'] ) ) {
            $request[ 'customer_name' ] = !empty( $request[ 'customer_name' ] ) ? $request[ 'customer_name' ] . ' ' . $better_form_fields['primary_last_name'] : $better_form_fields['primary_last_name'];
        }

        if ( !empty( $request[ 'customer_name' ] ) ) {
            $request[ 'metadata' ][ 'customer_name' ]   = $request[ 'customer_name' ];
            unset( $request[ 'customer_name' ] );
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

        $response_ar = json_decode( $response[ 'body' ] );
        
        if( empty( $response_ar->status ) || empty( $response_ar->data ) ){
            $error_message = $response_ar->message ? sanitize_text_field($response_ar->message) : 'Something went wrong!';

            if (isset($response_ar->error)){
                $error_message = sanitize_text_field($response_ar->error->message);
            }

            wp_send_json_error( $error_message );
        }

        Handler::payment_create(
            [
                'amount'         => $amount,
                'order_id'       => $order_id,
                'payment_date'   => date( 'Y-m-d H:i:s' ),
                'source'         => 'paystack',
                'transaction_id' => '',
                'customer_info'  => maybe_serialize( $response_ar ),
                'form_fields_info'  => maybe_serialize( $better_form_fields ),
                'status'         => 'unpaid',
                'currency'       => $el_settings_currency,
                'referer'        => "widget",
            ]
        );

        $authorization_url = ! empty( $response_ar->data->authorization_url ) ? esc_url_raw( $response_ar->data->authorization_url ) : '';
        
        wp_send_json_success(
            [
                'authorization_url' => $authorization_url,
            ]
        );
    }

    /**
     * Widget settings
     * 
     * @since 1.0.0
     */
    public function better_payment_widget_settings( $page_id, $widget_id ) {
        $document = \Elementor\Plugin::$instance->documents->get( $page_id );
        $settings = [];
        if ( $document ) {
            $elements    = \Elementor\Plugin::instance()->documents->get( $page_id )->get_elements_data();
            $widget_data = $this->find_element_recursive( $elements, $widget_id );
            $widget      = ! empty( $widget_data ) && is_array( $widget_data ) ? \Elementor\Plugin::instance()->elements_manager->create_element_instance( $widget_data ) : '';
            if ( ! empty( $widget ) ) {
                $settings = $widget->get_settings_for_display();
            }
        }
        return $settings;
    }

    /**
     * Find element recursive
     * 
     * @since 1.0.0
     */
    public function find_element_recursive( $elements, $form_id ) {

        foreach ( $elements as $element ) {
            if ( $form_id === $element[ 'id' ] ) {
                return $element;
            }

            if ( !empty( $element[ 'elements' ] ) ) {
                $element = $this->find_element_recursive( $element[ 'elements' ], $form_id );

                if ( $element ) {
                    return $element;
                }
            }
        }

        return false;
    }

    /**
     * Redirect to referer page
     * 
     * @since 1.0.0
     */
    public function redirect_previous_page() {
        $location = $_SERVER[ 'HTTP_REFERER' ];
        wp_safe_redirect( $location );
        exit();
    }

    /**
     * Fetch form fields
     * 
     * @since 0.0.4
     */

    public function fetch_better_form_fields($el_settings, $post_data_form_fields) {
        $better_form_fields = array();

        $better_payment_helper_obj = new Helper();
        $post_data_primary_first_name = '';
        $post_data_primary_last_name = '';
        $post_data_primary_email = '';
        
        $post_fields = $post_data_form_fields;

        $layout = ! empty( $el_settings['better_payment_form_layout'] ) ? sanitize_text_field( $el_settings['better_payment_form_layout'] ) : 'layout-1';

        switch( $layout ) {
            case 'layout-4-pro':
                $el_settings['better_payment_form_fields'] = $el_settings['better_payment_form_fields_layout_4_5_6'];
                break;

            case 'layout-5-pro':
                $el_settings['better_payment_form_fields'] = $el_settings['better_payment_form_fields_layout_4_5_6_desc'];
                break;
                
            case 'layout-6-pro':
                $el_settings['better_payment_form_fields'] = $el_settings['better_payment_form_fields_layout_4_5_6_woo'];
                break;

            default:
                break;
        }
        
        //in case of duplicate post name using the first one
        if(isset($el_settings['better_payment_form_fields']) && count($el_settings['better_payment_form_fields'])){
            foreach ($el_settings['better_payment_form_fields'] as $item) {

                $item_field_name = $better_payment_helper_obj->titleToSnake($item["better_payment_field_name_heading"]);
                if(!empty($item['better_payment_primary_field_type']) ){
                    $item_primary_field_type = $item['better_payment_primary_field_type'];

                    if( isset($post_fields[$item_primary_field_type]) ) {
                        $post_fields[$item_primary_field_type] =  is_array($post_fields[$item_primary_field_type]) ? $post_fields[$item_primary_field_type][0] : $post_fields[$item_primary_field_type];
                    }
                    
                    if( isset($post_fields[$item_field_name]) ) {
                        $post_fields[$item_field_name] =  is_array($post_fields[$item_field_name]) ? $post_fields[$item_field_name][0] : $post_fields[$item_field_name];
                    }

                    if ( 'primary_first_name' == $item_primary_field_type && isset($post_fields[$item_primary_field_type])){                        
                        $better_form_fields[$item_primary_field_type] =  sanitize_text_field($post_fields[$item_primary_field_type]);
                        $post_data_primary_first_name = sanitize_text_field($post_fields[$item_primary_field_type]);
                    
                    }else if ( 'primary_last_name' == $item_primary_field_type && isset($post_fields[$item_primary_field_type])){
                        $better_form_fields[$item_primary_field_type] = sanitize_text_field($post_fields[$item_primary_field_type]);
                        $post_data_primary_last_name = sanitize_text_field($post_fields[$item_primary_field_type]);
                    
                    }else if( 'primary_email' == $item_primary_field_type && isset($post_fields[$item_primary_field_type])){
                        $better_form_fields[$item_primary_field_type] = sanitize_email($post_fields[$item_primary_field_type]);
                        $post_data_primary_email = sanitize_email($post_fields[$item_primary_field_type]);
                    
                    }else if ( 'primary_payment_amount' == $item_primary_field_type && isset($post_fields[$item_primary_field_type]) ){
                        $better_form_fields[$item_primary_field_type] = floatval($post_fields[$item_primary_field_type]);
                    
                    } else {
                        $better_form_fields[$item_field_name] = !empty($post_fields[$item_field_name]) ? sanitize_text_field($post_fields[$item_field_name]) : '';
                    }

                }
            }
        }
        
        return $better_form_fields;
    }
    
}

