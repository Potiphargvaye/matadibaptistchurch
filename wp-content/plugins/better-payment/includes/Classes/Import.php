<?php

namespace Better_Payment\Lite\Classes;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Controller;
use Better_Payment\Lite\Models\TransactionModel;
use Better_Payment\Lite\Traits\Helper;
use Exception;
use League\Csv\Reader;
use League\Csv\Statement;

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
class Import extends Controller{

    use Helper;
    /**
     * Class constructor
     * 
     * @since 0.0.4
     */
    public function __construct() {
        
    }

    public function import_transactions() {
        $message = __('Invalid File!', 'better-payment');
        
        $invalid_nonce  = ! wp_verify_nonce( $_REQUEST['nonce'], 'better_payment_transaction_import_nonce' );
        $invalid_access = ! current_user_can('manage_options') || ! isset( $_FILES['better-payment-transaction-import-input'] ); 

		if ( $invalid_nonce || $invalid_access ) {
            set_transient('better_payment_import_transactions_error', esc_html( $message ), 30);
            wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
            exit;
		}

        $file = $_FILES['better-payment-transaction-import-input'];
        
        try {
            $this->validate_csv_file($file);
            $csv = Reader::createFromPath($file['tmp_name'], 'r');
            $csv->setHeaderOffset(0);
            
            // Header validation
            $csvHeader = $csv->getHeader();
            if ( isset( $csvHeader[0] ) && str_contains( $csvHeader[0], ';' ) ) {
                $csv->setDelimiter(';');
                $csvHeader = $csv->getHeader();
            }

            if ( ! is_array( $csvHeader ) || ! count( $csvHeader) ){
                throw new Exception( $message );
            }

            $csvHeader = array_map('sanitize_text_field', $csvHeader);
            
            $exportObj = new Export();
            $allowedHeaders = $exportObj->export_transactions_heading();

            if ( count( $allowedHeaders ) !== count( $csvHeader ) ) {
                throw new Exception( $message );
            }

            // Fetch rows
            $stmt = Statement::create()->offset(0)->limit(3000);
            $records = $stmt->process($csv);

            $data = array();

            foreach ( $records as $record ) {
                $better_form_fields = [
                    'primary_first_name'    => sanitize_text_field( $record['name'] ),
                    'email'                 => sanitize_email( $record['email'] ),
                    'source'                => sanitize_text_field( $record['source'] ),
                    'is_imported'           => intval(1),
                ];

                if ( ! empty( $record['payment_type'] ) && strtolower($record['payment_type']) === 'subscription' ) {
                    $better_form_fields_selected = isset( $record['form_fields_info'] ) ? maybe_unserialize( $record['form_fields_info'] ) : [];
                    
                    $better_form_fields['subscription_id']          = isset( $better_form_fields_selected['subscription_id'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_id'] ) : '';
                    $better_form_fields['subscription_customer_id'] = isset( $better_form_fields_selected['subscription_customer_id'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_customer_id'] ) : '';
                    $better_form_fields['subscription_plan_id']     = isset( $better_form_fields_selected['subscription_plan_id'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_plan_id'] ) : '';
                    $better_form_fields['subscription_interval']    = isset( $better_form_fields_selected['subscription_interval'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_interval'] ) : '';
                    $better_form_fields['subscription_current_period_start']     = isset( $better_form_fields_selected['subscription_current_period_start'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_current_period_start'] ) : '';
                    $better_form_fields['subscription_current_period_end']     = isset( $better_form_fields_selected['subscription_current_period_end'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_current_period_end'] ) : '';
                    $better_form_fields['subscription_status']     = isset( $better_form_fields_selected['subscription_status'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_status'] ) : '';
                    $better_form_fields['subscription_created_date']     = isset( $better_form_fields_selected['subscription_created_date'] ) ? sanitize_text_field( $better_form_fields_selected['subscription_created_date'] ) : '';
                    $better_form_fields['is_payment_split_payment']     = isset( $better_form_fields_selected['is_payment_split_payment'] ) ? sanitize_text_field( $better_form_fields_selected['is_payment_split_payment'] ) : '';
                }

                $amount_currency_array = explode(' ', $record['amount']);
                $currency = is_array($amount_currency_array) && count($amount_currency_array) > 1 ? $amount_currency_array[0] : esc_html('USD');
                $amount = is_array($amount_currency_array) && count($amount_currency_array) > 1 ? $amount_currency_array[1] : $record['amount'];

                $data[] = array(
                    'email'             => sanitize_email( $record['email'] ),
                    'currency'          => sanitize_text_field( $currency ),
                    'amount'            => floatval( $amount ),
                    // 'payment_type'      => sanitize_text_field( $record['payment_type'] ),
                    'transaction_id'    => sanitize_text_field( $record['transaction_id'] ),
                    'source'            => sanitize_text_field( $record['source'] ),
                    'status'            => sanitize_text_field( $record['status'] ),
                    'payment_date'      => date( 'Y-m-d H:i:s', strtotime( sanitize_text_field( $record['payment_date'] ) ) ),
                    'form_fields_info'  => maybe_serialize( $better_form_fields ),
                );
            }

            $table = DB::get_table_name();
            $rowsInserted = $this->bulk_insert_transactions( $table, $data );
            if ( $rowsInserted) {
                set_transient('better_payment_import_transactions_success', esc_html__( $rowsInserted . ' rows inserted successfully!', 'better-payment' ), 30);
            }
        } catch (\Exception $exception) {
            set_transient('better_payment_import_transactions_error', esc_html( $exception->getMessage() ), 30);
            wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
            exit;
        }

        wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
        exit;
    }

    public function validate_csv_file( $file ){
        $message = __('Invalid File!', 'better-payment');

        if ( empty( $file ) ){
            throw new Exception( $message );
        }

        $extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
        $allowed_extensions = ['csv'];

        if ( empty( $extension ) || ! in_array( $extension, $allowed_extensions ) ) {
            throw new Exception( $message );
        }

        return true;
    }

    public function bulk_insert_transactions($table, $rows) {
        global $wpdb;

        $headers = array_keys($rows[0]);
        asort($headers);
        $columnList = '`' . implode('`, `', $headers) . '`';

        $sql          = "INSERT INTO `$table` ($columnList) VALUES\n";
        $placeholders = array();
        $data         = array();
        
        foreach ($rows as $row) {
            ksort($row);
            $rowPlaceholders = array();
            foreach ($row as $key => $value) {
                $data[]            = $value;
                $rowPlaceholders[] = is_numeric($value) ? '%d' : '%s';
            }
            $placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
        }

        $sql .= implode(",\n", $placeholders);
        
        $rowsInserted = $wpdb->query($wpdb->prepare($sql, $data));
        return $rowsInserted;
    }
}