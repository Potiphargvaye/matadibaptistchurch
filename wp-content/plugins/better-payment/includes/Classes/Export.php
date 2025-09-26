<?php

namespace Better_Payment\Lite\Classes;

use Better_Payment\Lite\Classes\Helper as ClassesHelper;
use Better_Payment\Lite\Controller;
use Better_Payment\Lite\Models\TransactionModel;
use Better_Payment\Lite\Traits\Helper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Export handler class
 * 
 * @since 0.0.4
 */
class Export extends Controller{

    use Helper;
    /**
     * Class constructor
     * 
     * @since 0.0.4
     */
    public function __construct() {
        
    }

    public function export_transactions() {
        $response = [];

		if (!wp_verify_nonce($_REQUEST['nonce'], 'better_payment_admin_nonce')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		if (!current_user_can('manage_options')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}
		
		$transaction_model = new TransactionModel();
        $all_transactions = $transaction_model->get_transactions();

        $filename = 'better-payment-transactions-' . date('Y-m-d') . '.csv';
        return $this->transactions_array_to_csv_download($all_transactions, $filename);
    }

    public function transactions_array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        $bp_helper_obj = new ClassesHelper();
        $wp_date_format = get_option('date_format');
        $wp_time_format = get_option('time_format');

        $f = fopen('php://memory', 'w'); 

        fputcsv($f, $this->export_transactions_heading(), $delimiter);

        foreach ($array as $line) { 
            $line = (array)$line;
            unset($line['id']);
            unset($line['order_id']);
            unset($line['obj_id']);
            unset($line['refund_info']);
            unset($line['customer_info']);
            // unset($line['form_fields_info']);
            unset($line['referer']);


            $line['status'] = esc_html(ucfirst($bp_helper_obj->get_type_by_transaction_status($line['status'], 'v2')));
            $line['source'] = esc_html( ucfirst( $line['source'] ) );
            $line['payment_date'] = wp_date($wp_date_format.' '.$wp_time_format, strtotime($line['payment_date']));;

            $line['form_fields_info'] = $this->form_fields_info_formatted($line['form_fields_info']);
            
            $line['form_fields_info_name'] = isset($line['form_fields_info']['customer_name']) ? $line['form_fields_info']['customer_name'] : '';
            $line['form_fields_info_email'] = isset($line['form_fields_info']['customer_email']) ? $line['form_fields_info']['customer_email'] : '';
            $line['payment_type'] = ! empty( $line['form_fields_info']['subscription_id'] ) ? 'Subscription' : 'One Time'; 
            
            unset($line['form_fields_info']['customer_name']);
            unset($line['form_fields_info']['customer_email']);
            
            $line['form_fields_info'] = array_filter($line['form_fields_info'], function($value) {
                return !empty($value);
            });

            $line_formatted = array(
                $line['form_fields_info_name'],
                $line['form_fields_info_email'],
                $line['currency'] . ' ' . $line['amount'],
                $line['payment_type'],
                $line['transaction_id'],
                $line['source'],
                $line['status'],
                $line['payment_date'],
                ! empty( $line['form_fields_info'] ) ? maybe_serialize( $line['form_fields_info'] ) : '',
            );

            fputcsv($f, $line_formatted, $delimiter); 
        }
        
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: text/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        
        fpassthru($f);
        exit;
    }  

    public function export_transactions_heading(){
        return array(
            esc_html('name'),
            esc_html('email'),
            esc_html('amount'),
            esc_html('payment_type'),
            esc_html('transaction_id'),
            esc_html('source'),
            esc_html('status'),
            esc_html('payment_date'),
            esc_html('form_fields_info'),
            // 'Order ID',
            // 'Customer Info',
            // 'Form Fields Info',
            // 'Currency',
            // 'Referer'
        );
    }

    public function form_fields_info_formatted($form_fields_info){
        $bp_form_fields_info = maybe_unserialize($form_fields_info);

        $bp_transaction_subscription_id             = isset($bp_form_fields_info['subscription_id']) ? sanitize_text_field($bp_form_fields_info['subscription_id']) : '';
        $bp_transaction_subscription_customer_id    = isset($bp_form_fields_info['subscription_customer_id']) ? sanitize_text_field($bp_form_fields_info['subscription_customer_id']) : '';
        $bp_transaction_subscription_plan_id        = isset($bp_form_fields_info['subscription_plan_id']) ? sanitize_text_field($bp_form_fields_info['subscription_plan_id']) : '';
        
        $subscription_interval      = ! empty($bp_form_fields_info['subscription_interval']) ? sanitize_text_field( $bp_form_fields_info['subscription_interval'] ) : '';
        $subscription_current_period_start  = ! empty($bp_form_fields_info['subscription_current_period_start']) ? sanitize_text_field( $bp_form_fields_info['subscription_current_period_start'] ) : '';
        $subscription_current_period_end    = ! empty($bp_form_fields_info['subscription_current_period_end']) ? sanitize_text_field( $bp_form_fields_info['subscription_current_period_end'] ) : '';
        $subscription_status        = ! empty($bp_form_fields_info['subscription_status']) ? sanitize_text_field( $bp_form_fields_info['subscription_status'] ) : '';
        $subscription_created_date  = ! empty($bp_form_fields_info['subscription_created_date']) ? sanitize_text_field( $bp_form_fields_info['subscription_created_date'] ) : '';
        $is_payment_split_payment   = ! empty($bp_form_fields_info['is_payment_split_payment']) ? intval( $bp_form_fields_info['is_payment_split_payment'] ) : 0;

        $bp_transaction_customer_name = isset($bp_form_fields_info['primary_first_name']) ? sanitize_text_field($bp_form_fields_info['primary_first_name']) : '';
        $bp_transaction_customer_name .= ' ';
        $bp_transaction_customer_name .= isset($bp_form_fields_info['primary_last_name']) ? sanitize_text_field($bp_form_fields_info['primary_last_name']) : '';

        //legacy
        if( empty($bp_transaction_customer_name) || $bp_transaction_customer_name == ' ' ){
            $bp_transaction_customer_name = isset($bp_form_fields_info['first_name']) ? sanitize_text_field($bp_form_fields_info['first_name']) : '';
            $bp_transaction_customer_name .= ' ';
            $bp_transaction_customer_name .= isset($bp_form_fields_info['last_name']) ? sanitize_text_field($bp_form_fields_info['last_name']) : '';
        }

        $bp_transaction_customer_email = isset($bp_form_fields_info['primary_email']) ? sanitize_text_field($bp_form_fields_info['primary_email']) : '';
        //legacy
        if( empty($bp_transaction_customer_email) ){
            $bp_transaction_customer_email = isset($bp_form_fields_info['email']) ? sanitize_text_field($bp_form_fields_info['email']) : '';
        }

        return [
            'customer_name'             => $bp_transaction_customer_name,
            'customer_email'            => $bp_transaction_customer_email,
            'subscription_id'           => $bp_transaction_subscription_id,
            'subscription_customer_id'  => $bp_transaction_subscription_customer_id,
            'subscription_plan_id'      => $bp_transaction_subscription_plan_id,
            'subscription_interval'     => $subscription_interval,
            'subscription_current_period_start'      => $subscription_current_period_start,
            'subscription_current_period_end'        => $subscription_current_period_end,
            'subscription_status'       => $subscription_status,
            'subscription_created_date' => $subscription_created_date,
            'is_payment_split_payment'  => $is_payment_split_payment,
        ];
    }
    
}