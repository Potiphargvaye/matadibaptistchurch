<?php

namespace Better_Payment\Lite\Classes;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Controller;
use Better_Payment\Lite\Classes\Helper;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The plugin handler class
 * 
 * @since 0.0.1
 */
class Handler extends Controller{
    use TraitsHelper;

    /**
     * PayPal button
     * 
     * @since 0.0.1
     */
    public static function paypal_button( $widget_id = '' , $settings = [], $args = [] ) {
        global $wp;
        
        $args['extra_classes']          = ! empty( $args['extra_classes'] ) ? $args['extra_classes'] : '';
        $args['extra_classes']          = is_array($args['extra_classes']) ? implode(' ', $args['extra_classes']) : $args['extra_classes'];
        $args['button_text_default']    = ! empty( $args['button_text_default'] ) ? sanitize_text_field( $args['button_text_default'] ) : 'Proceed to Payment';

        $paypal_button_text             = ! empty( $settings["better_payment_form_form_buttons_paypal_button_text"] ) ? sanitize_text_field( $settings["better_payment_form_form_buttons_paypal_button_text"] ) : __( $args['button_text_default'], 'better-payment' );

        $return_url = add_query_arg( $wp->query_vars, get_the_permalink() . '?better_payment_paypal_status=success&better_payment_widget_id=' . $widget_id );
        $cancel_url = add_query_arg( $wp->query_vars, get_the_permalink() . '?better_payment_error_status=error&better_payment_widget_id=' . $widget_id );

        ob_start();
        $error_info = [
             'business_email' => !empty($settings['better_payment_paypal_business_email'])
        ];
        ?>
        <input type="hidden" name="return" value="<?php echo esc_url( $return_url ); ?>">
        <input type="hidden" name="action" value="paypal_form_handle">
        <input type="hidden" name="security" value="<?php echo esc_attr( wp_create_nonce('better-payment-paypal') ); ?>">
        <input type="hidden" name="cancel_return" value="<?php echo esc_url( $cancel_url ); ?>">
        <button data-paypal-info = <?php echo esc_attr(wp_json_encode( $error_info) ) ; ?> class="button is-medium is-fullwidth better-payment-paypal-bt <?php echo esc_attr( self::hide_show_payment_button_class($settings, 'paypal') ); ?> <?php echo esc_attr( $args['extra_classes'] ); ?>" ><?php echo esc_html( $paypal_button_text ); ?></button>
        
        <?php
        $paypal_button_html = ob_get_clean();
        $paypal_button_html = apply_filters( 'better_payment/elementor/editor/paypal_button_html', $paypal_button_html, $widget_id, $settings );

        echo $paypal_button_html;
    }

    /**
     * Stripe button
     * 
     * @since 0.0.1
     */
    public static function stripe_button( $widget_id = '' , $settings = [], $args = [] ) {
        $args['extra_classes']          = ! empty( $args['extra_classes'] ) ? $args['extra_classes'] : '';
        $args['extra_classes']          = is_array($args['extra_classes']) ? implode(' ', $args['extra_classes']) : $args['extra_classes'];
        $args['button_text_default']    = ! empty( $args['button_text_default'] ) ? sanitize_text_field( $args['button_text_default'] ) : 'Proceed to Payment';

        $stripe_button_text             = ! empty( $settings["better_payment_form_form_buttons_stripe_button_text"] ) ? sanitize_text_field( $settings["better_payment_form_form_buttons_stripe_button_text"] ) : __( $args['button_text_default'], 'better-payment' );

        ob_start();
        ?>
        <button class="button is-medium is-fullwidth better-payment-stripe-bt <?php echo esc_attr( self::hide_show_payment_button_class($settings, 'stripe') ); ?> <?php echo esc_attr( $args['extra_classes'] ); ?>" ><?php echo esc_html( $stripe_button_text ); ?></button>
        <?php
        $stripe_button_html = ob_get_clean();
        $stripe_button_html = apply_filters( 'better_payment/elementor/editor/stripe_button_html', $stripe_button_html, $widget_id, $settings );

        echo wp_kses( $stripe_button_html,  (new Handler())->bp_allowed_tags() );
    }

    /**
     * Paystack button
     * 
     * @since 0.0.1
     */
    public static function paystack_button( $widget_id = '' , $settings = [], $args = [] ) {
        $args['extra_classes']          = ! empty( $args['extra_classes'] ) ? $args['extra_classes'] : '';
        $args['extra_classes']          = is_array($args['extra_classes']) ? implode(' ', $args['extra_classes']) : $args['extra_classes'];
        $args['button_text_default']    = ! empty( $args['button_text_default'] ) ? sanitize_text_field( $args['button_text_default'] ) : 'Proceed to Payment';

        $paystack_button_text           = ! empty( $settings["better_payment_form_form_buttons_paystack_button_text"] ) ? sanitize_text_field( $settings["better_payment_form_form_buttons_paystack_button_text"] ) : __( $args['button_text_default'], 'better-payment' );
        
        ob_start();
        ?>
        <button class="button is-medium is-fullwidth better-payment-paystack-bt <?php echo esc_attr( self::hide_show_payment_button_class($settings, 'paystack') ); ?> <?php echo esc_attr( $args['extra_classes'] ); ?>" ><?php echo esc_html( $paystack_button_text ); ?></button>
        <?php
        $paystack_button_html = ob_get_clean();
        $paystack_button_html = apply_filters( 'better_payment/elementor/editor/paystack_button_html', $paystack_button_html, $widget_id, $settings );

        echo wp_kses( $paystack_button_html,  (new Handler())->bp_allowed_tags() );
    }

    public static function hide_show_payment_button_class( $settings, $type = 'paypal' ){
        $button_hidden_class = 'is-hidden';
        $types = ['paypal', 'stripe', 'paystack'];
        
        if( ! in_array($type, $types) ){
            return $button_hidden_class;
        }

        $is_type_enable = ! empty( $settings[ "better_payment_form_{$type}_enable" ] ) && 'yes' === $settings[ "better_payment_form_{$type}_enable" ];
        
        if( ! $is_type_enable ){
            return $button_hidden_class;
        }
        
        $is_paypal_enable = ! empty( $settings[ "better_payment_form_paypal_enable" ] ) && 'yes' === $settings[ "better_payment_form_paypal_enable" ];
        $is_stripe_enable = ! empty( $settings[ "better_payment_form_stripe_enable" ] ) && 'yes' === $settings[ "better_payment_form_stripe_enable" ];
        $is_paystack_enable = ! empty( $settings[ "better_payment_form_paystack_enable" ] ) && 'yes' === $settings[ "better_payment_form_paystack_enable" ];
        
        switch( $type ){
            case 'paypal':
                $button_hidden_class = ''; 
                break;

            case 'stripe':
                if( ( ! $is_paypal_enable ) ){
                    $button_hidden_class = ''; 
                }
                
                break;

            case 'paystack':
                if( ( ! $is_paypal_enable ) && ( ! $is_stripe_enable ) ){
                    $button_hidden_class = ''; 
                }
                break;

            default:
                break;
        }

        return $button_hidden_class;
    }

    /**
     * Payment create in db
     * 
     * @since 0.0.1
     */
    public static function payment_create( $data ) {
        global $wpdb;
        $table = "{$wpdb->prefix}better_payment";
        $wpdb->insert( $table, $data );
        if ( $wpdb->insert_id ) {
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Mamage response
     * 
     * @since 0.0.1
     */
    public static function manage_response( $settings = [], $widget_id = '' ) {
        $status = false;

        //if widget id is different then return false
        if ( !empty( $_REQUEST[ 'better_payment_widget_id' ] ) && $_REQUEST[ 'better_payment_widget_id' ] !== $widget_id ) {
            return $status;
        }
        
        if ( !empty( $_REQUEST[ 'better_payment_error_status' ] ) ) {
            self::failed_message_template( $settings );
            $redirection_url_error = ! empty( $settings['better_payment_form_error_page_url']['url'] ) ? esc_url( $settings['better_payment_form_error_page_url']['url'] ) : '';
            
            if( $redirection_url_error ){
                ?>
                <script>
                    setTimeout(function(){
                        window.location.replace("<?php echo esc_url( $redirection_url_error ); ?>");
                    }, 2000);
                </script>
                <?php
            }

            return $status;
        }

        if ( !empty( $_REQUEST[ 'better_payment_paypal_status' ] ) && $_REQUEST[ 'better_payment_paypal_status' ] == 'success' ) {
            $status = self::paypal_payment_success( $settings );
        } elseif ( !empty( $_REQUEST[ 'better_payment_stripe_status' ] ) && $_REQUEST[ 'better_payment_stripe_status' ] == 'success' ) {
            $status = self::stripe_payment_success( $settings );
        } elseif ( !empty( $_REQUEST[ 'better_payment_paystack_status' ] ) && $_REQUEST[ 'better_payment_paystack_status' ] == 'success' ) {
            $status = self::paystack_payment_success( $settings );
        }

        if ( $status ) {
            self::success_message_template( $settings, $status );
            $redirection_url_success = ! empty( $settings['better_payment_form_success_page_url']['url'] ) ? esc_url( $settings['better_payment_form_success_page_url']['url'] ) : '';
            
            if( $redirection_url_success ){
                ?>
                <script>
                    setTimeout(function(){
                        window.location.replace("<?php echo esc_url( $redirection_url_success ); ?>");
                    }, 2000);
                </script>
                <?php 
            }
        }
        self::remove_arg();

        return $status;
    }

    /**
     * Paypal payment success
     * 
     * @since 0.0.1
     */
    public static function paypal_payment_success( $settings = [] ) {
        $data = $_REQUEST;
        $frontend_data = [
            'amount' => ! empty( $data['payment_gross'] ) ? floatval( $data['payment_gross'] ) : 0,
            'email' => ! empty( $data['payer_email'] ) ? sanitize_email( $data['payer_email'] ) : '',
            'transaction_id' => ! empty( $data[ 'txn_id' ] ) ? sanitize_text_field( $data[ 'txn_id' ] ) : '',
            'currency' => ! empty( $data['mc_currency'] ) ? sanitize_text_field( $data['mc_currency'] ) : __( 'USD', 'better-payment' ),
            'method' => 'paypal',
        ];
        if ( !empty( $data[ 'payment_status' ] ) && !empty( $data[ 'payer_id' ] ) && !empty( $data[ 'payer_status' ] ) ) {
            global $wpdb;
            $table   = "{$wpdb->prefix}better_payment";
            $results = $wpdb->get_row(
                $wpdb->prepare( "SELECT id,form_fields_info,referer FROM $table WHERE order_id=%s and status is NULL limit 1", sanitize_text_field( $_REQUEST[ 'item_number' ] ) )
            );
            
            if ( !empty( $results->id ) ) {
                $updated = $wpdb->update(
                    $table,
                    array(
                        'transaction_id' => sanitize_text_field( $data[ 'txn_id' ] ),
                        'status'         => sanitize_text_field( $data[ 'payment_status' ] ),
                        'email'          => sanitize_email( $data[ 'payer_email' ] ),
                        'customer_info'  => maybe_serialize( $data ),
                    ),
                    array( 'ID' => $results->id )
                );
                
                if ( false !== $updated ) {
                    //Send email notification
                    if ( 
                        ( isset($settings[ 'better_payment_form_email_enable' ]) && $settings[ 'better_payment_form_email_enable' ] == 'yes' ) 
                        || ( $results->referer === 'elementor-form' )
                        ) {
                        $is_elementor_form = ! empty( $results->referer ) && $results->referer === 'elementor-form'  ? 1 : 0;
                        self::better_email_notification(sanitize_text_field( $data[ 'txn_id' ] ), sanitize_email( $data[ 'payer_email' ] ), $settings, 'PayPal', $results->form_fields_info, $is_elementor_form);
                    }

                    return $frontend_data;
                }
            }
        }else if($data['better_payment_paypal_status'] === 'success'){
            return ( isset($data[ 'txn_id' ]) && !empty( sanitize_text_field( $data[ 'txn_id' ] ) ) ) ? $frontend_data : __('Payment under processing!', 'better-payment');
        }
        return false;
    }

    /**
     * Stripe payment success
     * 
     * @since 0.0.1
     */
    public static function stripe_payment_success( $settings = [] ) {

        $data = $_REQUEST;

        if ( !empty( $data[ 'better_payment_stripe_id' ] ) ) {
            global $wpdb;
            $table   = "{$wpdb->prefix}better_payment";
            $results = $wpdb->get_row(
                $wpdb->prepare( "SELECT id, obj_id, amount, transaction_id, currency, form_fields_info, referer FROM $table WHERE order_id=%s and status = 'unpaid' limit 1", sanitize_text_field( $data[ 'better_payment_stripe_id' ] ) )
            );
            
            if ( !empty( $results->obj_id ) && !empty( $settings[ 'better_payment_stripe_secret_key' ] ) ) {
                $header = array(
                    'Authorization'  => 'Basic ' . base64_encode( sanitize_text_field( $settings[ 'better_payment_stripe_secret_key' ] ) . ':' ),
                    'Stripe-Version' => '2019-05-16',
                );

                $request = [
                    'expand' => [
                        'subscription.latest_invoice.payment_intent',
                        'payment_intent'
                    ]
                ];
                $form_fields_info = maybe_unserialize($results->form_fields_info);
                $is_payment_recurring = ! empty( $form_fields_info['mode'] ) && 'subscription' === $form_fields_info['mode'];
                $is_payment_split_payment = ( $is_payment_recurring ) && ( ! empty( $form_fields_info['is_payment_split_payment'] ) && 1 === intval( $form_fields_info['is_payment_split_payment'] ) );
                
                $action_data = [
                    'form_fields_info' => $form_fields_info,
                    'is_payment_recurring' => $is_payment_recurring,
                    'is_payment_split_payment' => $is_payment_split_payment,
                    'checkout_session_id' => $results->obj_id,
                ];

                $response = wp_safe_remote_post(
                    'https://api.stripe.com/v1/checkout/sessions/' . $results->obj_id,
                    array(
                        'method'  => 'GET',
                        'headers' => $header,
                        'timeout' => 70,
                        'body'    => $request
                    )
                );

                if ( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
                    return new \WP_Error( 'stripe_error', __( 'There was a problem connecting to the Stripe API endpoint.', 'better-payment' ) );
                }

                $response = json_decode( $response[ 'body' ] );
                $transaction_id = ! empty( $results->transaction_id ) ? sanitize_text_field( $results->transaction_id ) : '';
                
                if ( $is_payment_recurring && ! empty( $response->subscription->id ) ) {
                    $transaction_id = sanitize_text_field( $response->subscription->id );
                    $subscription_obj = $response->subscription;
                    $form_fields_info['subscription_id'] = sanitize_text_field( $transaction_id );
                    $form_fields_info['subscription_customer_id'] = ! empty( $response->customer ) ? sanitize_text_field( $response->customer ) : '';
                    $form_fields_info['subscription_plan_id'] = ! empty( $subscription_obj->items->data[0]->plan->id ) ? sanitize_text_field( $subscription_obj->items->data[0]->plan->id ) : '';
                    $form_fields_info['subscription_interval'] = ! empty( $subscription_obj->items->data[0]->plan->interval_count ) ? intval( $subscription_obj->items->data[0]->plan->interval_count ) : '';
                    $form_fields_info['subscription_interval'] .= ! empty( $subscription_obj->items->data[0]->plan->interval ) ? ' ' . sanitize_text_field( $subscription_obj->items->data[0]->plan->interval ) : $form_fields_info['subscription_interval'];
                    $form_fields_info['subscription_current_period_start'] = ! empty( $subscription_obj->current_period_start ) ? intval( $subscription_obj->current_period_start ) : 0;
                    $form_fields_info['subscription_current_period_end'] = ! empty( $subscription_obj->current_period_end ) ? intval( $subscription_obj->current_period_end ) : 0;
                    $form_fields_info['subscription_status'] = sanitize_text_field( $response->status );
                    $form_fields_info['subscription_created_date'] = sanitize_text_field( $subscription_obj->created );
                }

                if ( isset( $response->error->message ) ) {
                    return new \WP_Error( 'stripe_error', __( 'There was a problem connecting to the Stripe API endpoint.', 'better-payment' ) );
                }

                $customer_email = $is_payment_recurring && ( ! empty( $response->customer_email ) ) ? sanitize_email( $response->customer_email ) : '';
                $customer_email_optional = '';
                if (
                    isset( $response->payment_intent ) &&
                    isset( $response->payment_intent->charges ) &&
                    isset( $response->payment_intent->charges->data ) &&
                    ! empty( $response->payment_intent->charges->data )
                ) {
                    $charge = current( $response->payment_intent->charges->data );
            
                    if (
                        isset( $charge->billing_details ) &&
                        isset( $charge->billing_details->email ) &&
                        ! empty( $charge->billing_details->email )
                    ) {
                        $customer_email_optional = sanitize_email( $charge->billing_details->email );
                    }
                }
                
                $updated = $wpdb->update(
                    $table,
                    array(
                        'status'        => sanitize_text_field( $response->payment_status ),
                        'email'         => $is_payment_recurring ? $customer_email : $customer_email_optional,
                        'customer_info' => maybe_serialize( $response ),
                        'form_fields_info' => maybe_serialize( $form_fields_info ),
                        'transaction_id' => $transaction_id,
                    ),
                    array( 'ID' => $results->id )
                );

                do_action('better_payment/stripe_payment/success', $action_data);
                
                $frontend_data = [
                    'amount' => ! empty( $results->amount ) ? floatval( $results->amount ) : 0,
                    'email' => $is_payment_recurring ? $customer_email : $customer_email_optional,
                    'transaction_id' => $transaction_id,
                    'currency' => ! empty( $results->currency ) ? $results->currency : __( 'USD', 'better-payment' ),
                    'method' => 'stripe',
                    'is_payment_recurring' => $is_payment_recurring,
                    'is_payment_split_payment' => $is_payment_split_payment,
                ];

                if ( $is_payment_split_payment ) {
                    $total_amount = ! empty( $form_fields_info['split_payment_total_amount'] ) 
                        ? sanitize_text_field( $form_fields_info['split_payment_total_amount'] ) 
                        : 0;
                    $total_installment = ! empty( $form_fields_info['split_payment_installment_iteration'] ) 
                        ? sanitize_text_field( $form_fields_info['split_payment_installment_iteration'] ) 
                        : 1;

                    $frontend_data['amount'] = floatval( $total_amount / $total_installment );
                    $frontend_data['total_amount'] = $total_amount;
                }

                if ( false !== $updated ) {
                    //Send email notification
                    $better_customer_email = $is_payment_recurring ? $customer_email : $customer_email_optional;
                    
                    if ( 
                        (isset($settings[ 'better_payment_form_email_enable' ]) && $settings[ 'better_payment_form_email_enable' ] == 'yes' ) 
                        || ( $results->referer === 'elementor-form' )
                        ) {
                        $is_elementor_form = ! empty( $results->referer ) && $results->referer === 'elementor-form'  ? 1 : 0;
                        self::better_email_notification($transaction_id, $better_customer_email, $settings, 'Stripe', $results->form_fields_info, $is_elementor_form);                    
                    }

                    return $frontend_data;
                }
            }
        }
        return false;
    }
    
    /**
     * Paystack payment success
     * 
     * @since 0.0.1
     */
    public static function paystack_payment_success( $settings = [] ) {
        $data = $_REQUEST;

        if ( ! empty( $data[ 'better_payment_paystack_id' ] ) ) {
            global $wpdb;
            $table   = "{$wpdb->prefix}better_payment";
            $results = $wpdb->get_row(
                $wpdb->prepare( "SELECT id,obj_id,transaction_id,form_fields_info,referer FROM $table WHERE order_id=%s and status = 'unpaid' limit 1", sanitize_text_field( $data[ 'better_payment_paystack_id' ] ) )
            );
            
            $header_info = array(
                'Authorization'  => 'Bearer ' . sanitize_text_field( $settings[ 'better_payment_paystack_secret_key' ] ),
                "Cache-Control: no-cache",
            );
            
            $response = wp_safe_remote_get(
                'https://api.paystack.co/transaction/verify/'. sanitize_text_field( $data[ 'reference' ] ),
                array(
                    'headers' => $header_info,
                    'timeout' => 70,
                )
            );

            $response = json_decode(wp_remote_retrieve_body($response));
    
            if ( ! empty( $response->status ) ) {
                $updated = $wpdb->update(
                    $table,
                    array(
                        'status'        => ! empty( $response->data->status ) ? sanitize_text_field( $response->data->status ) : '',
                        'amount'        => ! empty( $response->data->amount ) ? floatval( ( $response->data->amount ) / 100 ) : 0,
                        'obj_id'        => ! empty( $response->data->id ) ? sanitize_text_field( $response->data->id ) : '',
                        'transaction_id' => ! empty( $response->data->reference ) ? sanitize_text_field( $response->data->reference ) : '',
                        'customer_info' => ! empty( $response->data->customer ) ? maybe_serialize( $response->data->customer ) : '',
                        'email'         => ! empty( $response->data->customer->email ) ? sanitize_email( $response->data->customer->email ) : '',
                    ),
                    array( 'ID' => $results->id )
                );
            }

            $better_customer_email = ! empty( $response->data->customer->email ) ? maybe_serialize( $response->data->customer->email ) : '';
            $transaction_id = ! empty( $response->data->reference ) ? sanitize_text_field( $response->data->reference ) : '';

            $frontend_data = [
                'amount' => ! empty( $response->data->amount ) ? floatval( ( $response->data->amount ) / 100 ) : 0,
                'email' => ! empty( $better_customer_email ) ? $better_customer_email : '',
                'transaction_id' => $transaction_id,
                'currency' => ! empty( $response->data->currency ) ? sanitize_text_field( $response->data->currency ) : __( 'USD', 'better-payment' ),
                'method' => 'paystack',
            ];

            if ( ! empty( $updated ) && $better_customer_email ) {
                //Send email notification
                if ( 
                    (isset($settings[ 'better_payment_form_email_enable' ]) && $settings[ 'better_payment_form_email_enable' ] == 'yes' ) 
                    || ( $results->referer === 'elementor-form' )
                    ) {
                    $is_elementor_form = ! empty( $results->referer ) && $results->referer === 'elementor-form'  ? 1 : 0;
                    self::better_email_notification($transaction_id, $better_customer_email, $settings, 'Paystack', $results->form_fields_info, $is_elementor_form);                    
                }

                return $frontend_data;
            }
        }
        return false;
    }

    /**
     * Success message template
     * 
     * @since 0.0.1
     */
    public static function success_message_template( $settings = [], $tr_id = '' ) {
        if ( empty( $tr_id ) || is_wp_error($tr_id)) {
            return false;
        }
        wp_enqueue_script('better-payment-admin-script');
        $image_url = BETTER_PAYMENT_ASSETS . '/img/success.svg';
        $show_icon = 0;
        $helper_obj = new Helper();

        $store_name = ( $settings['better_payment_form_payment_source'] === 'manual' && !empty( $settings['better_payment_form_title'] ) ) 
            ? esc_html($settings['better_payment_form_title']) 
            : esc_html( get_bloginfo('name') );

        $payment_desc_text = !empty( $settings['better_payment_form_success_message_heading'] ) 
            ? esc_html($settings['better_payment_form_success_message_heading']) 
            : esc_html( __('You paid', 'better-payment') . '[currency_symbol][amount] '. __(' to ', 'better-payment') . '[store_name]');

        $bp__currency_symbol = esc_html( $helper_obj->get_currency_symbol( $tr_id['currency'] ) );

        $payment_desc_text = str_replace( '[currency_symbol]', '<span class="bp-bold">' . $bp__currency_symbol . '</span>', $payment_desc_text );

        $payment_desc_text = str_replace( '[amount]', '<span class="bp-bold">' . esc_html( $tr_id['amount'] ) . '</span>', $payment_desc_text );
        
        $payment_desc_text = str_replace( '[store_name]', '<span class="bp-bold">' . esc_html( $store_name ) . '</span>', $payment_desc_text );

        $payment_mail_text = !empty( $settings['better_payment_form_success_message_sub_heading'] ) ? esc_html($settings['better_payment_form_success_message_sub_heading']) : esc_html( __('Payment Confirmation email will be sent to ', 'better-payment') . '[customer_email]');

        $payment_mail_text = str_replace( '[customer_email]', '<span class="bp-bold">' . esc_html( $tr_id['email'] ) . '</span>', $payment_mail_text );

        if ( !empty( $settings[ 'better_payment_form_success_message_icon' ][ 'library' ] ) ) {
            if ( $settings[ 'better_payment_form_success_message_icon' ][ 'library' ] == 'svg' ) {
                $image_url = $settings[ 'better_payment_form_success_message_icon' ][ 'value' ][ 'url' ];
            } else {
                $show_icon = 1;
                $image_url = $settings[ 'better_payment_form_success_message_icon' ][ 'value' ];
            }
        }


        $allowed_payment_methods = [
            'paypal' => BETTER_PAYMENT_ASSETS . '/img/paypal-2.svg',
            'stripe' => BETTER_PAYMENT_ASSETS . '/img/stripe-2.svg',
            'paystack' => BETTER_PAYMENT_ASSETS . '/img/paystack-2.svg',
        ];
        $payment_method_logo = isset( $allowed_payment_methods[ $tr_id['method'] ] ) ? $allowed_payment_methods[ $tr_id['method'] ] : ''; 

        $better_payment_form_success_message_thanks = !empty( $settings['better_payment_form_success_message_thanks'] ) 
            ? esc_html( $settings['better_payment_form_success_message_thanks'] ) 
            : esc_html__('Thank You!', 'better-payment');
        $better_payment_form_success_message_transaction = !empty( $settings['better_payment_form_success_message_transaction'] ) 
            ? esc_html__( $settings['better_payment_form_success_message_transaction'], 'better-payment' ) 
            : esc_html__('Transaction ID', 'better-payment');
        $better_payment_form_success_message_amount_text = !empty( $settings['better_payment_form_success_message_amount_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_amount_text'] ) 
            : esc_html__( 'Amount', 'better-payment' );
        $better_payment_form_success_message_currency_text = !empty( $settings['better_payment_form_success_message_currency_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_currency_text'] ) 
            : esc_html__( 'Currency', 'better-payment' );
        $better_payment_form_success_message_pay_method_text = !empty( $settings['better_payment_form_success_message_pay_method_text'] ) ? esc_html( $settings['better_payment_form_success_message_pay_method_text'] ) : esc_html__( 'Payment Method', 'better-payment' );
        $better_payment_form_success_message_pay_type_text = !empty( $settings['better_payment_form_success_message_pay_type_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_pay_type_text'] ) 
            : esc_html__( 'Payment Type', 'better-payment' );
        $better_payment_form_payment_type = !empty( $settings['better_payment_form_payment_type'] ) 
            ? esc_html( $settings['better_payment_form_payment_type'] ) 
            : '';
        if ( ! empty( $better_payment_form_payment_type ) ) {
            if (  'one-time' === $better_payment_form_payment_type ) {
                $better_payment_form_payment_type_value = esc_html__( 'One Time Payment', 'better-payment' );
            } elseif (  'recurring' === $better_payment_form_payment_type ) {
                $better_payment_form_payment_type_value = esc_html__( 'Recurring Payment', 'better-payment' );
            } else {
                $better_payment_form_payment_type_value = esc_html__( 'Split Payment', 'better-payment' );
            }
        }
        $better_payment_form_success_message_merchant_details_text = !empty( $settings['better_payment_form_success_message_merchant_details_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_merchant_details_text'] ) 
            : esc_html__( 'Merchant Details', 'better-payment' );
        $better_payment_form_success_message_merchant_details_value = $settings['better_payment_form_payment_source'] === 'manual' && ! empty( $settings['better_payment_form_title'] ) 
            ? esc_html($settings['better_payment_form_title']) 
            : esc_html( get_bloginfo( 'name' ) );
        $better_payment_form_success_message_paid_amount_text = !empty( $settings['better_payment_form_success_message_paid_amount_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_paid_amount_text'] ) 
            : esc_html__( 'Paid Amount', 'better-payment' );
        $better_payment_form_success_message_purchase_details_text = !empty( $settings['better_payment_form_success_message_purchase_details_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_purchase_details_text'] ) 
            : esc_html__( 'Purchase Details', 'better-payment' );
        $better_payment_form_success_message_print_text = !empty( $settings['better_payment_form_success_message_print_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_print_text'] ) 
            : esc_html__( 'Print', 'better-payment' );
        $better_payment_form_success_message_view_details_btn_text = !empty( $settings['better_payment_form_success_message_view_details_btn_text'] ) 
            ? esc_html( $settings['better_payment_form_success_message_view_details_btn_text'] ) 
            : esc_html__( 'View Details', 'better-payment' );
        $better_payment_form_success_page_view_details_url = !empty( $settings['better_payment_form_success_page_view_details_url']['url'] ) ? esc_url( $settings['better_payment_form_success_page_view_details_url']['url'] ) : 'javascript:void(0)';
        ?>
        <section class="bp-thank_page">
            <div class="bp-thank_page-wrapper">
                <div class="bp-thank_page-logo">
                    <span class="bp-thank_page-logo_wrapper">
                        <img src="<?php echo esc_url( BETTER_PAYMENT_ASSETS . '/img/success-2.svg' ); ?>" alt="Better Payment logo">
                    </span>
                </div>
                <div class="bp-thank_page-text">
                    <h2 class="bp-font_ibm bp-page_header">
                        <?php echo wp_kses_post( $better_payment_form_success_message_thanks ); ?>
                        <span> &#127881;</span>
                    </h2>
                    <p class="bp-font_ibm bp-reason_for-text">
                        <?php echo wp_kses_post( $payment_desc_text ); ?>
                    </p>
                    <?php if ($settings['better_payment_form_email_enable'] && !empty( $tr_id['email'] )): ?>
                        <p class="bp-font_ibm bp-payment_info">
                            <?php echo wp_kses_post( $payment_mail_text ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="bp-flex bp-transaction_info-wrapper">
                        <p class="bp-flex bp-font_ibm  bp-transaction_info"> <span class="line-height-0">
                            <img src="<?php echo esc_url( BETTER_PAYMENT_ASSETS . '/img/wallet.svg' ); ?>" alt="Better Payment wallet"> 
                            </span>
                            <span>
                                <?php echo wp_kses_post( $better_payment_form_success_message_transaction ); ?>: 
                            </span>
                            <span class="bp-bold bp-transaction_id"><?php echo esc_html( $tr_id['transaction_id'] ); ?></span>
                        </p>
                    
                        <button class="bp-transaction_data-copy_btn ">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.0002 15.601C16.3257 15.601 17.4002 14.5265 17.4002 13.201V8.40098C17.4002 5.38399 17.4002 3.87549 16.463 2.93823C15.5257 2.00098 14.0172 2.00098 11.0002 2.00098H7.80024C6.47476 2.00098 5.40024 3.07549 5.40024 4.40098M10.2002 18.001H7.80024C5.5375 18.001 4.40613 18.001 3.70319 17.298C3.00024 16.5951 3.00024 15.4637 3.00024 13.201V9.20098C3.00024 6.93823 3.00024 5.80686 3.70319 5.10392C4.40613 4.40098 5.5375 4.40098 7.80024 4.40098H10.2002C12.463 4.40098 13.5944 4.40098 14.2973 5.10392C15.0002 5.80686 15.0002 6.93824 15.0002 9.20098V13.201C15.0002 15.4637 15.0002 16.5951 14.2973 17.298C13.5944 18.001 12.463 18.001 10.2002 18.001Z"
                                stroke-width="1.152" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bp-thank_page-info">
                    <ul class="bp-page_info-list">
                        <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                            <span class="bp-info_content">
                                <?php
                                    echo wp_kses_post( $better_payment_form_success_message_amount_text );
                                ?>
                            </span>
                            <span class="bp-info_content  bp-bold"><?php echo $bp__currency_symbol . esc_html( $tr_id['amount'] ); ?></span>
                        </li>
                        <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                            <span class=" bp-info_content">
                                <?php
                                    echo wp_kses_post( $better_payment_form_success_message_currency_text );
                                ?>
                            </span>
                            <span class="bp-info_content  bp-bold"><?php echo esc_html( $tr_id['currency'] ); ?></span>
                        </li>
                        <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                            <span class="bp-info_content">
                                <?php
                                    echo wp_kses_post( $better_payment_form_success_message_pay_method_text );
                                ?>
                            </span>
                            <div class="bp-payment_method-logo-wrapper">
                                <?php if ( !empty( $payment_method_logo ) ) : ?>
                                    <img src="<?php echo esc_url( $payment_method_logo ); ?>" alt="<?php echo esc_attr( $tr_id['method'] ); ?>" class="bp-payment_method-logo">
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php if( !empty( $better_payment_form_payment_type ) ): ?>
                            <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                                <span class="bp-info_content">
                                    <?php
                                        echo wp_kses_post( $better_payment_form_success_message_pay_type_text );
                                    ?>
                                </span>
                                <span class="bp-info_content  bp-bold"><?php echo wp_kses_post( $better_payment_form_payment_type_value ); ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                            <span class="bp-info_content">
                                <?php
                                    echo wp_kses_post( $better_payment_form_success_message_merchant_details_text );
                                ?>
                            </span>
                            <span class="bp-info_content  bp-bold">
                                <?php echo wp_kses_post( $better_payment_form_success_message_merchant_details_value );  ?>
                            </span>
                        </li>
                        <?php if( isset( $tr_id['is_payment_split_payment'] ) && $tr_id['is_payment_split_payment'] ): ?>
                            <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                                <span class="bp-info_content">
                                    <?php
                                        echo wp_kses_post( $better_payment_form_success_message_paid_amount_text );
                                    ?>
                                </span>
                                <span class="bp-info_content  bp-bold">
                                    <?php echo $bp__currency_symbol . esc_html($tr_id['amount']); ?> out of <?php echo $bp__currency_symbol . esc_html($tr_id['total_amount']); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if( !empty($settings['better_payment_form_woocommerce_product_id']) || !empty( $settings['better_payment_form_woocommerce_product_ids'] ) ): ?>
                        <li class="bp-flex bp-font_ibm  bp-page_info-list_item">
                            <span class="bp-info_content">
                                <?php
                                    echo wp_kses_post( $better_payment_form_success_message_purchase_details_text );
                                ?>
                            </span>
                            <?php if( !empty( $settings['better_payment_form_woocommerce_product_id'] ) ): ?>
                                <span class="bp-info_content  bp-bold"><?php echo esc_html( get_the_title( $settings['better_payment_form_woocommerce_product_id'] ) ); ?></span>
                            <?php endif; ?>
                            <?php
                                if( !empty( $settings['better_payment_form_woocommerce_product_ids'] ) ):
                                    $product_ids = $settings['better_payment_form_woocommerce_product_ids'];
                                    $product_names = [];
                                    foreach( $product_ids as $product_id ):
                                        $product_names[] = get_the_title( $product_id );
                                    endforeach;
                                    echo '<span class="bp-info_content  bp-bold">' . implode( ', ', $product_names ) . '</span>';
                                endif;
                            ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="bp-flex bp-button_group">
                    <button class="bp-flex bp-font_ibm bp-print_btn">
                        <span style="line-height: 0;">
                            <img src="<?php echo esc_url( BETTER_PAYMENT_ASSETS . '/img/print.svg' ); ?>" alt="Better Payment print">
                        </span>
                        <span class="bp-font_ibm">
                            <?php echo wp_kses_post( $better_payment_form_success_message_print_text ); ?>
                        </span>
                    </button>
                    
                    <div class="bp-flex bp-btn_wrapper">
                        <a href="<?php echo $better_payment_form_success_page_view_details_url; ?>" target="_blank" class="bp-font_ibm bp-details_btn">
                            <?php echo wp_kses_post( $better_payment_form_success_message_view_details_btn_text ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Error message template
     * 
     * @since 0.0.1
     */
    public static function failed_message_template( $settings = [] ) {

        $image_url = BETTER_PAYMENT_ASSETS . '/img/fail.svg';
        $show_icon = 0;

        if ( !empty( $settings[ 'better_payment_form_error_message_icon' ][ 'library' ] ) ) {
            if ( $settings[ 'better_payment_form_error_message_icon' ][ 'library' ] == 'svg' ) {
                $image_url = $settings[ 'better_payment_form_error_message_icon' ][ 'value' ][ 'url' ];
            } else {
                $show_icon = 1;
                $image_url = $settings[ 'better_payment_form_error_message_icon' ][ 'value' ];
            }
        }
        ?>
        <div class="pb140 background__grey2">
            <div class="better-payment-payment__report better-payment-error-report">
                <div class="report__thumb">
                    <?php if($show_icon) : ?>
                        <i class="<?php echo esc_attr($image_url); ?>"></i>
                    <?php else : ?>    
                        <img src="<?php echo esc_url($image_url); ?>" alt="Error image">
                    <?php endif; ?>
                </div>
                <div class="report__content">
                    <h3 class="transaction__failed"><?php echo ( isset( $settings['better_payment_form_error_message_heading'] ) ) ? esc_html__( $settings['better_payment_form_error_message_heading'], 'better-payment' ) : esc_html__('Payment Failed', 'better-payment'); ?></h3>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Remove args from url
     * 
     * @since 0.0.1
     */
    public static function remove_arg() {
        ?>
        <script>

            if(typeof params === 'undefined'){
                let params = '';                
            }

            params = new URLSearchParams(location.search);

            if (params.has('better_payment_paypal_status') || params.has('better_payment_stripe_status') || params.has('better_payment_paystack_status')) {
                params.delete('better_payment_paypal_status');
                params.delete('better_payment_stripe_status');
                params.delete('better_payment_paystack_status');
                params.delete('better_payment_widget_id');
                params.delete('better_payment_stripe_id');
                params.delete('better_payment_paystack_id');
                window.history.replaceState({}, '', `${location.pathname}?${params}`);
            }
        </script>
        <?php
    }

    /**
     * Email template
     * 
     * @since 0.0.1
     */
    public static function better_email_notification($transaction_id, $customer_email, $settings, $referrer='Stripe', $form_fields_info='', $is_elementor_form = 0){
        //Send Email: customer and admin
        $better_txn_id = sanitize_text_field( $transaction_id );
        $better_cus_email = sanitize_email( $customer_email );
        $default_subject = sprintf(__('Better Payment transaction on %s', 'better-payment'), esc_html(get_option('blogname')));
        $better_payment_global_settings = DB::get_settings();

        $better_payment_email_to = !empty($better_payment_global_settings['better_payment_settings_general_email_to']) ? $better_payment_global_settings['better_payment_settings_general_email_to'] : get_option( 'admin_email' );

        $better_payment_email_subject = !empty($better_payment_global_settings['better_payment_settings_general_email_subject']) ? $better_payment_global_settings['better_payment_settings_general_email_subject'] :  $default_subject;
        $better_payment_email_content = !empty($better_payment_global_settings['better_payment_settings_general_email_message_admin']) ? $better_payment_global_settings['better_payment_settings_general_email_message_admin'] : 'Email Content';
        $better_payment_email_from = !empty($better_payment_global_settings['better_payment_settings_general_email_from_email']) ? $better_payment_global_settings['better_payment_settings_general_email_from_email'] : '';
        $better_payment_email_from_name = !empty($better_payment_global_settings['better_payment_settings_general_email_from_name']) ? $better_payment_global_settings['better_payment_settings_general_email_from_name'] : '';
        $better_payment_email_reply_to = !empty($better_payment_global_settings['better_payment_settings_general_email_reply_to']) ? $better_payment_global_settings['better_payment_settings_general_email_reply_to'] : '';
        $better_payment_email_cc = !empty($better_payment_global_settings['better_payment_settings_general_email_cc']) ? $better_payment_global_settings['better_payment_settings_general_email_cc'] : '';
        $better_payment_email_bcc = !empty($better_payment_global_settings['better_payment_settings_general_email_bcc']) ? $better_payment_global_settings['better_payment_settings_general_email_bcc'] : '';
        $better_payment_email_content_type = !empty($better_payment_global_settings['better_payment_settings_general_email_send_as']) ? $better_payment_global_settings['better_payment_settings_general_email_send_as'] : 'html';
        
        $better_payment_email_subject_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_subject_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_subject_customer'] : $default_subject;
        $better_payment_email_content_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_message_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_message_customer'] : 'Email Content';
        $better_payment_email_from_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_from_email_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_from_email_customer'] : '';
        $better_payment_email_from_name_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_from_name_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_from_name_customer'] : '';
        $better_payment_email_reply_to_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_reply_to_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_reply_to_customer'] : '';
        $better_payment_email_cc_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_cc_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_cc_customer'] : '';
        $better_payment_email_bcc_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_bcc_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_bcc_customer'] : '';
        $better_payment_email_content_type_customer = !empty($better_payment_global_settings['better_payment_settings_general_email_send_as_customer']) ? $better_payment_global_settings['better_payment_settings_general_email_send_as_customer'] : 'html';
        $args = [];

        $args['email_form_name'] = ! empty( $settings['better_payment_form_title'] ) ? sanitize_text_field( $settings['better_payment_form_title'] ) : 'N/A';
        $args['email_content_heading_show'] = ! empty( $settings['better_payment_email_content_heading'] ) && 'yes' === $settings['better_payment_email_content_heading'] ? 1 : 0;
        $args['email_content_from_section_show'] = ! empty( $settings['better_payment_email_content_from_section'] ) && 'yes' === $settings['better_payment_email_content_from_section'] ? 1 : 0;
        $args['email_content_to_section_show'] = ! empty( $settings['better_payment_email_content_to_section'] ) && 'yes' === $settings['better_payment_email_content_to_section'] ? 1 : 0;
        $args['email_content_transaction_summary_show'] = ! empty( $settings['better_payment_email_content_transaction_summary'] ) && 'yes' === $settings['better_payment_email_content_transaction_summary'] ? 1 : 0;
        $args['email_content_footer_text_show'] = ! empty( $settings['better_payment_email_content_footer_text'] ) && 'yes' === $settings['better_payment_email_content_footer_text'] ? 1 : 0;
        $args['email_content_customer'] = ! empty( $settings['better_payment_email_content_customer'] ) ? $settings['better_payment_email_content_customer'] : '';
        $args['email_content_admin'] = ! empty( $settings['better_payment_email_content'] ) ? $settings['better_payment_email_content'] : '';
        $args['is_elementor_form'] = $is_elementor_form;

        if ( ! empty( $settings['better_payment_email_content_greeting'] ) ) {
            $args['email_content_greeting'] = $settings['better_payment_email_content_greeting'];
        }
        
        if ( ! empty( $settings['better_payment_form_email_logo']['url'] ) ) {
            $args['email_logo_url'] = $settings['better_payment_form_email_logo']['url'];
        }
        
        if ( ! empty( $settings['better_payment_form_email_attachment']['id'] ) ) {
            $args['email_attachment_id'] = intval( $settings['better_payment_form_email_attachment']['id'] );
            $args['email_attachment_path'] = get_attached_file( $args['email_attachment_id'] );
        }
        
        if ( ! empty( $settings['better_payment_form_email_attachment_pdf_show'] ) && ! empty( $settings['better_payment_form_email_attachment_pdf']['id'] ) ) {
            $args['email_attachment_id'] = intval( $settings['better_payment_form_email_attachment_pdf']['id'] );
            $args['email_attachment_path'] = get_attached_file( $args['email_attachment_id'] );
        }

        if(isset($settings['better_payment_email_to'])){
            $better_payment_email_to = sanitize_email($settings['better_payment_email_to']);
        }

        if(isset($settings['better_payment_email_subject'])){
            $better_payment_email_subject = sanitize_text_field($settings['better_payment_email_subject']);
        }

        if(isset($settings['better_payment_email_subject_customer'])){
            $better_payment_email_subject_customer = sanitize_text_field($settings['better_payment_email_subject_customer']);
        }

        if(isset($settings['better_payment_email_content_type'])){
            $better_payment_email_content_type = sanitize_text_field($settings['better_payment_email_content_type']);
        }
        
        if(isset($settings['better_payment_email_content_type_customer'])){
            $better_payment_email_content_type_customer = sanitize_text_field($settings['better_payment_email_content_type_customer']);
        }

        $args['email_content_type'] = $better_payment_email_content_type;
        $args['email_content_type_customer'] = $better_payment_email_content_type_customer;

		$line_break = $better_payment_email_content_type == 'html' ? '<br>' : "\n";
		$line_break_customer = $better_payment_email_content_type_customer == 'html' ? '<br>' : "\n";

        global $wpdb;
        $table   = "{$wpdb->prefix}better_payment";
        $transaction_obj = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM $table WHERE transaction_id=%s limit 1", sanitize_text_field( $transaction_id ) )
        );

        if(isset($settings['better_payment_email_content'])){
            $better_payment_email_content = sanitize_text_field($settings['better_payment_email_content']);
            $better_payment_email_content = self::better_render_email_body( $better_payment_email_content, $form_fields_info, $line_break, 'admin', $transaction_obj, $args );
        } else if ( !empty($better_payment_email_content) ){
            $better_payment_email_content = self::better_render_email_body( $better_payment_email_content, $form_fields_info, $line_break, 'admin', $transaction_obj, $args );
        }

        if(isset($settings['better_payment_email_content_customer'])){
            $better_payment_email_content_customer = sanitize_text_field($settings['better_payment_email_content_customer']);
            $better_payment_email_content_customer = self::better_render_email_body( $better_payment_email_content_customer, $form_fields_info, $line_break_customer, 'customer', $transaction_obj, $args );
        } else if ( !empty($better_payment_email_content_customer) ){
            $better_payment_email_content_customer = self::better_render_email_body( $better_payment_email_content_customer, $form_fields_info, $line_break, 'customer', $transaction_obj, $args ); //$line_break_customer not used : need to check type on parent method and add headers accordingly.
        }

        if(isset($settings['better_payment_email_from'])){
            $better_payment_email_from = sanitize_email($settings['better_payment_email_from']);
        }

        if(isset($settings['better_payment_email_from_name'])){
            $better_payment_email_from_name = sanitize_text_field($settings['better_payment_email_from_name']);
        }

        if(isset($settings['better_payment_email_reply_to'])){
            $better_payment_email_reply_to = sanitize_email($settings['better_payment_email_reply_to']);
        }

        if(isset($settings['better_payment_email_cc'])){
            $better_payment_email_cc = sanitize_email($settings['better_payment_email_cc']);
        }

        if(isset($settings['better_payment_email_bcc'])){
            $better_payment_email_bcc = sanitize_email($settings['better_payment_email_bcc']);
        }

        //Customer Email
        if(isset($settings['better_payment_email_from_customer'])){
            $better_payment_email_from_customer = sanitize_email($settings['better_payment_email_from_customer']);
        }

        if(isset($settings['better_payment_email_from_name_customer'])){
            $better_payment_email_from_name_customer = sanitize_text_field($settings['better_payment_email_from_name_customer']);
        }

        if(isset($settings['better_payment_email_reply_to_customer'])){
            $better_payment_email_reply_to_customer = sanitize_email($settings['better_payment_email_reply_to_customer']);
        }

        if(isset($settings['better_payment_email_cc_customer'])){
            $better_payment_email_cc_customer = sanitize_email($settings['better_payment_email_cc_customer']);
        }

        if(isset($settings['better_payment_email_bcc_customer'])){
            $better_payment_email_bcc_customer = sanitize_email($settings['better_payment_email_bcc_customer']);
        }

        if($better_payment_email_content == ''){
            $better_payment_email_content = __('New better payment transaction! ', 'better-payment');
        }

        if($better_payment_email_content_customer == ''){
            $better_payment_email_content_customer = __('New better payment transaction! ', 'better-payment');
        }

        $better_payment_email_headers = [];
        $better_payment_email_headers_customer = [];

        if($better_payment_email_content_type == 'html'){
            $better_payment_email_headers[] = 'Content-Type: text/html; charset=UTF-8';
        }
        
        if($better_payment_email_content_type_customer == 'html'){
            $better_payment_email_headers_customer[] = 'Content-Type: text/html; charset=UTF-8';
        }

        if($better_payment_email_from != ''){
            $better_payment_email_headers[] = "From: $better_payment_email_from_name <$better_payment_email_from>";
        }

        if($better_payment_email_cc != ''){
            $better_payment_email_headers[] = "Cc: <$better_payment_email_cc>";
        }

        if($better_payment_email_bcc != ''){
            $better_payment_email_headers[] = "BCc: $better_payment_email_bcc";
        }

        if($better_payment_email_reply_to != ''){
           $better_payment_email_headers[] = "Reply-To: $better_payment_email_reply_to";
        }

        //Customer Email
        if($better_payment_email_from_customer != ''){
            $better_payment_email_headers_customer[] = "From: $better_payment_email_from_name_customer <$better_payment_email_from_customer>";
        }

        $form_fields_info_array = [];
        
        if( ! empty( $form_fields_info ) ){
            $form_fields_info_array = maybe_unserialize( $form_fields_info );
        }

        $form_fields_info_cus_email = ! empty( $form_fields_info_array['primary_email'] ) ? sanitize_email( $form_fields_info_array['primary_email'] ) : '';
        
        if($better_payment_email_cc_customer != '' || ! empty( $form_fields_info_cus_email ) ){
            $better_payment_email_cc_customer_multiple = implode(',', [$form_fields_info_cus_email, $better_payment_email_cc_customer]);
            $better_payment_email_headers_customer[] = "Cc: $better_payment_email_cc_customer_multiple";
        }

        if($better_payment_email_bcc_customer != ''){
            $better_payment_email_headers_customer[] = "BCc: $better_payment_email_bcc_customer";
        }

        if($better_payment_email_reply_to_customer != ''){
           $better_payment_email_headers_customer[] = "Reply-To: $better_payment_email_reply_to_customer";
        }

        $email_attachments = ! empty( $args['email_attachment_path'] ) ? [ $args['email_attachment_path'] ] : [];
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf');

        $file_info = count( $email_attachments ) ? pathinfo($email_attachments[0]) : [];
        $file_extension = count( $file_info ) ? strtolower($file_info['extension']) : '';

        if ( ! in_array( $file_extension, $allowed_extensions ) ){
            $email_attachments = [];
        }

        self::send_better_email($better_cus_email, $better_payment_email_subject_customer, $better_payment_email_content_customer, $better_payment_email_headers_customer, $email_attachments); //customer mail
        self::send_better_email($better_payment_email_to, $better_payment_email_subject, $better_payment_email_content, $better_payment_email_headers); //admin mail
    }

    /**
     * Sends an HTML email.
     *
     * @since  0.0.2
     *
     * @param string $to
     * @param string $subject
     * @param array  $message
     *
     * @return bool false indicates mail sending failed while true doesn't gurantee that mail sent.
     * true just indicate script processed successfully without any errors. 
     * Ref: https://developer.wordpress.org/reference/functions/wp_mail/
     */
    public static function send_better_email( $to, $subject, $message, $headers=[], $attachments = [] ) {
        $response = false;

        $mailValidated = filter_var($to, FILTER_VALIDATE_EMAIL);

        if ($mailValidated) {
            $response = wp_mail( $to, $subject, $message, $headers, $attachments );
        }

        return $response;
    }

    /**
     * Email body
     *
     * 
     * @return string
     * @since  0.0.1
     */
    public static function better_render_email_body( $email_body, $form_fields_info, $line_break, $type = 'admin', $transaction_obj = null, $args = [] ) {
        $transaction_id = 
        $currency =
        $payment_date =
        $customer_name =
        $customer_first_name = 
        $customer_last_name = 
        $amount = '';
        $helper_obj = new Helper();
        
        if(null !== $transaction_obj && is_object($transaction_obj)){
           $transaction_id = $transaction_obj->transaction_id;
           $amount = $transaction_obj->amount;
           $status = $transaction_obj->status;
           $source = $transaction_obj->source;
           $currency = $transaction_obj->currency;
           $currency_symbol    = $helper_obj->get_currency_symbol( esc_html($currency) );
           $payment_date = wp_date(get_option('date_format').' '.get_option('time_format'), strtotime($transaction_obj->payment_date));
        }

        $is_empty_email_body = empty($email_body);

        $email_body = do_shortcode( $email_body );
        $bp_form_fields_html_content = $email_body;
        
        $bp_all_fields_shortcode = 'bp-all-fields';
        $referer_content_page_link = '#';
		
        $field_text = __('Field', 'better-payment');
        $value_text = __('Entry', 'better-payment');

        $form_fields_info_arr = maybe_unserialize($form_fields_info);

        $email_logo_url = ! empty( $args['email_logo_url'] ) ? $args['email_logo_url'] : '';
        $email_content_greeting = ! empty( $args['email_content_greeting'] ) ? 1 : 0;
        $email_form_name = ! empty( $args['email_form_name'] ) ? $args['email_form_name'] : 'N/A';

        $bp_form_fields_html_header = '<table style="margin-bottom: 20px; font-size: 14px; border-collapse: collapse; width: 100%;">';

        $bp_form_fields_html_footer = "</table>";

        $content = ''. $line_break;
        
        foreach ( $form_fields_info_arr as $key => $field ) {
            if ( $key === 'is_payment_split_payment' ) {
                $is_payment_split_payment = 1;
            }
            //Hide few fields
            if(
                $key === 'referer_page_id' || $key === 'referer_widget_id' || $key === 'source' || $key === 'el_form_fields'
                || $key === 'is_woo_layout'
                || $key === 'is_payment_split_payment'
                || $key === 'split_payment_total_amount'
                || $key === 'split_payment_total_amount_price_id'
                || $key === 'split_payment_installment_price_id'
            ) {
                if($key === 'referer_page_id'){
                    $referer_content_page_link = !empty($field) ? get_permalink( $field ) : $referer_content_page_link;
                }

                continue;
            }

            if($key === 'primary_first_name'){
                $key = 'first_name';
                $customer_first_name = $field;
            }
            if($key === 'primary_last_name'){
                $key = 'last_name';
                $customer_last_name = $field;
            }

            if($key === 'primary_email'){
                $key = 'email';
            }

            if($key === 'primary_payment_amount'){
                $key = 'payment_amount';
            }
            
            $key_formatted = self::better_title_case($key);
            if($key_formatted === 'Amount'){
                $key_formatted = __('Paid', 'better-payment');

                if ( ! empty( $is_payment_split_payment ) ) {
                    $key_formatted = __('Product Price: ', 'better-payment');
                }
            }

            $content .= "<tr>
                            <td style='padding: 7px; border: 1px solid #666;'> <strong>$key_formatted</strong> </td>
                            <td style='padding: 7px; border: 1px solid #666;'> $field </td>
                        </tr>";
        }

        if($customer_first_name) {
            $customer_name = $customer_first_name . ' ';
        }
        if($customer_last_name) {
            $customer_name .= $customer_last_name . ' ';
        }

        $allowed_sources = ['paypal', 'stripe', 'paystack'];
        $source_image_url = BETTER_PAYMENT_ASSETS . '/img/stripe.png';
        $source_image_alt = 'Stripe';
        
        if( in_array( strtolower( $transaction_obj->source ), $allowed_sources ) ){
            $source_image_url = strtolower( $transaction_obj->source ) == 'paypal' ? BETTER_PAYMENT_ASSETS . '/img/paypal.png' : BETTER_PAYMENT_ASSETS . "/img/{$transaction_obj->source}.png";
            $source_image_alt = strtolower( $transaction_obj->source ) == 'paypal' ? 'PayPal' : ucfirst( strtolower( $transaction_obj->source ) );
        }
        
        $amount_quantity = ! empty( $form_fields_info_arr['amount_quantity'] ) ? intval( $form_fields_info_arr['amount_quantity'] ) : 1;
        // #ToDo Product id or ids with comma
        $woo_product_id = ! empty( $form_fields_info_arr['woo_product_id'] ) ? intval( $form_fields_info_arr['woo_product_id'] ) : 0;
        $product_name = '';
        $product_permalink = '';
        $product_image_src = '';

        if (function_exists('wc_get_product') && $woo_product_id ) {
            $bp_woocommerce_product = wc_get_product($woo_product_id);
            $product = $bp_woocommerce_product;
    
            if ($product) {
                $product_name = $product->get_name();
                $product_permalink = get_permalink($product->get_id());
                $product_price = $product->get_price();
                $product_image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
                $product_image_src = is_array( $product_image_src_array ) && count( $product_image_src_array ) ? $product_image_src_array[0] : '';
            }
        }
        
        $all_data = [
            'amount' => $amount,
            'amount_quantity' => $amount_quantity,
            'woo_product_id' => $woo_product_id,
            'product_name' => $product_name,
            'product_permalink' => $product_permalink,
            'product_image_src' => $product_image_src,
            'amount_single' => $amount_quantity > 0 ? floatval( $amount / $amount_quantity ) : $amount,
            'currency' => ! empty( $currency ) ? $currency : '',
            'currency_symbol' => ! empty( $currency_symbol ) ? $currency_symbol : '',
            'payment_date_time' => ! empty( $payment_date ) ? $payment_date : '',
            'transaction_id' => ! empty( $transaction_id ) ? $transaction_id : '',
            'payment_date_only' => wp_date(get_option('date_format'), strtotime($transaction_obj->payment_date)),
            'status' => ucfirst( $status ),
            'form_fields_info_arr' => $form_fields_info_arr,
            // 'admin_name' => $admin_name,
            'customer_name' => $customer_name,
            'site_title' => get_bloginfo( 'name' ),
            'site_admin_email' => get_bloginfo( 'admin_email' ),
            'form_name' => $email_form_name,
            'payment_method' => ucfirst( $source ),
            'email_logo_url' => $email_logo_url,
            'source_image_url' => $source_image_url,
            'source_image_alt' => $source_image_alt,
            'view_transaction_link' => admin_url("admin.php?page=better-payment-transactions&action=view&id={$transaction_obj->id}"),
            'email_content_heading_show' => intval( $args['email_content_heading_show'] ),
            'email_content_from_section_show' => intval( $args['email_content_from_section_show'] ),
            'email_content_to_section_show' => intval( $args['email_content_to_section_show'] ),
            'email_content_transaction_summary_show' => intval( $args['email_content_transaction_summary_show'] ),
            'email_content_footer_text_show' => intval( $args['email_content_footer_text_show'] ),
            'email_content_customer' => wp_kses_post( $args['email_content_customer'] ),
            'email_content_admin' => wp_kses_post( $args['email_content_admin'] ),
            'is_elementor_form' => intval( $args['is_elementor_form'] ),
            'email_content_type' => $args['email_content_type'] ?? 'html',
            'email_content_type_customer' => $args['email_content_type_customer'] ?? 'html',
        ];

        $bp_form_fields_html_body = $content;
        $bp_form_fields_html_content = $bp_form_fields_html_header . $bp_form_fields_html_body . $bp_form_fields_html_footer ;

        //Replace shortcode with form fields info
        $email_body = str_replace( "[$bp_all_fields_shortcode]", $bp_form_fields_html_content, $email_body );

        // Email V2
        // General content
        $form_name = 

        ob_start();

        include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/template-email-notification.php";
        
        $email_body = ob_get_contents();
        ob_end_clean();

		return $email_body;
	}

    /**
     * String helper method
     * helps to convert string to title case
     * 
     * @return string
     * @since  0.0.1
     */
    public static function better_title_case($string){
        $string = str_replace('_',' ', $string);
        $string = ucwords($string);
        return $string;
    }
}
