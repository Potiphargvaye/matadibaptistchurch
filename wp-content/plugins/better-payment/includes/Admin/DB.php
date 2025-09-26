<?php

namespace Better_Payment\Lite\Admin;

use Better_Payment\Lite\Classes\Helper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * This class responsible for database work
 * using wordpress functionality 
 * get_option and update_option.
 * 
 * @since 0.0.1
 */
class DB {

    public static function get_table_name() {
        global $wpdb;

        $table   = "{$wpdb->prefix}better_payment";
        return $table;
    }
    /**
     * Get all default settings value.
     *
     * @param string $name
     * @return array
     * @since 0.0.1
     */
    public static function default_settings() {
        return apply_filters('better_payment_option_default_settings', array(
            'better_payment_settings_general_general_paypal' => 'yes',
            'better_payment_settings_general_general_stripe' => 'yes',
            'better_payment_settings_general_general_paystack' => '',
            'better_payment_settings_general_general_user_dashboard' => 'yes',
            'better_payment_settings_general_general_email' => 'yes',
            'better_payment_settings_general_general_currency' => 'USD',

            'better_payment_settings_general_email_to' => '',
            'better_payment_settings_general_email_subject' => '',
            'better_payment_settings_general_email_message_admin' => '',
            'better_payment_settings_general_email_from_email' => '',
            'better_payment_settings_general_email_from_name' => '',
            'better_payment_settings_general_email_reply_to' => '',
            'better_payment_settings_general_email_cc' => '',
            'better_payment_settings_general_email_bcc' => '',
            'better_payment_settings_general_email_send_as' => 'html',

            'better_payment_settings_general_email_subject_customer' => '',
            'better_payment_settings_general_email_message_customer' => '',
            'better_payment_settings_general_email_from_email_customer' => '',
            'better_payment_settings_general_email_from_name_customer' => '',
            'better_payment_settings_general_email_reply_to_customer' => '',
            'better_payment_settings_general_email_cc_customer' => '',
            'better_payment_settings_general_email_bcc_customer' => '',
            'better_payment_settings_general_email_send_as_customer' => 'html',

            'better_payment_settings_payment_paypal_live_mode' => 'no',
            'better_payment_settings_payment_paypal_email' => '',            
            'better_payment_settings_opt_in' => '',
            'better_payment_settings_payment_paypal_live_client_id' => '',
            'better_payment_settings_payment_paypal_test_client_id' => '',
            'better_payment_settings_payment_paypal_live_secret' => '',
            'better_payment_settings_payment_paypal_test_secret' => '',
            'better_payment_settings_payment_stripe_live_mode' => 'no',
            'better_payment_settings_payment_stripe_live_public' => '',
            'better_payment_settings_payment_stripe_live_secret' => '',
            'better_payment_settings_payment_stripe_test_public' => '',
            'better_payment_settings_payment_stripe_test_secret' => '',
            'better_payment_settings_payment_paystack_live_mode' => 'no',
            'better_payment_settings_payment_paystack_live_public' => '',
            'better_payment_settings_payment_paystack_live_secret' => '',
            'better_payment_settings_payment_paystack_test_public' => '',
            'better_payment_settings_payment_paystack_test_secret' => '',
        ));
    }
    /**
     * Get all settings value from options table.
     *
     * @param string $name
     * @return array
     * @since 0.0.1
     */
    public static function get_settings($name = '')
    {
        $settings = get_option('better_payment_settings', true);
        $default = self::default_settings();
        if (!empty($name) && isset($settings[$name])) {
            return $settings[$name];
        }

        if (!empty($name) && !isset($settings[$name]) && isset($default[$name])) {
            return $default[$name];
        }

        if (!empty($name) && !isset($settings[$name])  && !isset($default[$name])) {
            return '';
        }

        return is_array($settings) ? $settings : [];
    }
    /**
     * Update settings 
     * @param array $value
     * @return boolean
     * @since 0.0.1
     */
    public static function update_settings($value, $key = '')
    {
        if (!empty($key)) {
            return update_option($key, $value);
        }
        return update_option('better_payment_settings', $value);
    }

    /**
     * Get all transactions from better payment table.
     *
     * @param string $args
     * @return array
     * @since 0.0.1
     */
    public static function get_transactions($args = [], $count_only=0, $version = 'v1', $fetchNull = 0, $transaction_type = '')
    {
        global $wpdb;
        $items = '';

        $defaults = array(
            'search_text' => '',
            'payment_date_from' => '',
            'payment_date_to' => '',
            'order_by' => 'payment_date',
            'order' => 'DESC',
            'source' => '', 
            'paged' => 1,
            'per_page' => 20, 
            'offset' => 0,
            'status' => 'all', 
        );

        $allowed_order_by = array(
            'id',
            'payment_date',
            'email',
            'amount'
        );

        $allowed_order = array(
            'DESC',
            'ASC'
        );

        $better_payment_db_obj = new DB();
        $better_payment_helper_obj = new Helper();

        $allowed_statuses = $better_payment_db_obj->allowed_statuses('all', 'v2');
        $allowed_statuses_types = $better_payment_db_obj->allowed_statuses_types('v2');

        $args = wp_parse_args($args, $defaults);
        $table   = self::get_table_name();

        $search_text  = sanitize_text_field($args['search_text']);
        $search_text = empty($search_text) ? '' : "%$search_text%";

        $order_by = in_array($args['order_by'], $allowed_order_by) ? sanitize_text_field($args['order_by']) : $defaults['order_by'];
        $order = in_array($args['order'], $allowed_order) ? sanitize_text_field($args['order']) : $defaults['order'];
        $offset = $args['offset'] > 0 ? intval($args['offset']) : $defaults['offset'];
        $per_page = $args['per_page'] > 0 ? intval($args['per_page']) : $defaults['per_page'];
        
        if($args['status'] == 'null') {
            $status = sanitize_text_field($args['status']);
        }else {
            $allowed_statuses_by_version = ($version == 'v1') ? $allowed_statuses : $allowed_statuses_types;
            $status = in_array($args['status'], $allowed_statuses_by_version) ? sanitize_text_field($args['status']) : $defaults['status'];
        }
        
        $statuses_by_transaction_type = array();

        if( !empty($transaction_type) ){
            $statuses_by_types = $better_payment_helper_obj->transaction_statuses_with_type_v2();
            $statuses_by_transaction_type = isset($statuses_by_types[$transaction_type]['statuses']) ? $statuses_by_types[$transaction_type]['statuses'] : $defaults['status'];
            $status = '';
        }
        
        $whereQuery = ''; 

        $valid_search_text = $search_text != '' && strlen($search_text) >= 2;
        if($valid_search_text){
            $whereQuery = $wpdb->prepare(' AND email LIKE %s OR form_fields_info LIKE %s OR transaction_id LIKE %s OR amount LIKE %s OR source LIKE %s ', $search_text, $search_text ,$search_text, $search_text, $search_text );
        }

        if( $args['source'] != '' ){
            $whereQuery .= $wpdb->prepare(" AND source = %s", $args['source']); 
        }

        if( $status != 'all' ){
            if($status == 'null'){
                $whereQuery .= esc_sql(" AND status is NULL");
            }else {
                //version 2
                if('v1' === $version){
                    $whereQuery .= $wpdb->prepare( " AND status = %s OR status = %s", $status, strtolower($status) ); 
                } else {
                    
                    if($transaction_type){
                        $statuses_by_type = $statuses_by_transaction_type;
                    }else {
                        $statuses_by_type = $better_payment_db_obj->allowed_statuses($status, 'v2');
                    }

                    $statuses = array_map(function($status) {
                        return "'" . esc_sql($status) . "'";
                    }, $statuses_by_type);
            
                    $statuses = implode(',', $statuses);
                    
                    $nullDataQuery = $fetchNull ? " OR status IS NULL " : '';
                    $whereQuery .= " AND ( status IN ( $statuses ) " . esc_sql($nullDataQuery) . " ) "; 
                }
            }
        }
        
        if( $args['payment_date_from'] != '' && $args['payment_date_to'] != ''){
            $whereQuery .= $wpdb->prepare(" AND payment_date BETWEEN %s AND %s", $args['payment_date_from'], $args['payment_date_to']); 
        }

        if($count_only === 1){
            if($valid_search_text){
                $items = $wpdb->get_var(
                        "SELECT count(id) FROM $table WHERE 1
                        $whereQuery
                        ORDER BY $order_by $order
                        "
                );
            }else {
                $items = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT count(id) FROM $table WHERE 1
                        $whereQuery
                        ORDER BY $order_by $order
                        LIMIT %d, %d
                        ",
                        $offset,
                        $per_page
                    )
                );
            }
            
        }else {
            $items = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table WHERE 1
                    $whereQuery
                    ORDER BY $order_by $order
                    LIMIT %d, %d
                    ",
                    $offset,
                    $per_page
                )
            );
            
        }

        return $items;
    }

    /**
     * Get the count of total transactions
     *
     * @return int
     * @since 0.0.1
     */
    public static function get_transaction_count($args = '', $version = 'v1', $fetchNull = 0, $transaction_type = '')
    {
        global $wpdb;
        $table = self::get_table_name();

        $count = 0;
        
        if( !empty($transaction_type) ){
            $count = self::get_transactions($args, 1, $version, $fetchNull, $transaction_type);
            return $count;
        }

        if($args !== ''){
            $count = self::get_transactions($args, 1, $version, $fetchNull);
        }else {
            $count = $wpdb->get_var(
                "SELECT count(id) FROM $table"
            );
        }

        return $count;
    }

    /**
     * Get transactions analytics
     *
     * @return int
     * @since 0.0.1
     */
    public static function get_transactions_analytics($filtered_transactions = array())
    {
        global $wpdb;
        $transaction_analytics = [];

        $table = self::get_table_name();
        $better_payment_db_obj = new DB();

        $total_transactions = $better_payment_db_obj->get_transactions_amount_by_statuses(array(), '', '', 'count', 1, $filtered_transactions );

        $completed_statuses = $better_payment_db_obj->allowed_statuses('completed', 'v2');
        $completed_transactions = $better_payment_db_obj->get_transactions_amount_by_statuses( $completed_statuses, '', '', 'count', 0, $filtered_transactions );

        $incomplete_statuses = $better_payment_db_obj->allowed_statuses('incomplete', 'v2');
        $incomplete_transactions = $better_payment_db_obj->get_transactions_amount_by_statuses( $incomplete_statuses, '', '', 'count', 1, $filtered_transactions );
        
        $transaction_analytics['total_transactions']        = $total_transactions;
        $transaction_analytics['completed_transactions']    = $completed_transactions;
        $transaction_analytics['incomplete_transactions']   = $incomplete_transactions;
        
        return $transaction_analytics;
    }

    /**
     * Get transactions analytics
     *
     * @return int
     * @since 0.0.1
     */
    public static function get_transactions_analytics_dashboard($filtered_transactions = array())
    {
        global $wpdb;
        $transaction_analytics = [];

        $table = self::get_table_name();
        $better_payment_db_obj = new DB();

        $total_transactions_count = 0;
        $completed_transactions_count = 0;
        $incomplete_transactions_count = 0;
        $refunded_transactions_count = 0;

        $total_transactions_amount = 0;
        $completed_transactions_amount = 0;
        $incomplete_transactions_amount = 0;
        $refunded_transactions_amount = 0;

        if ( is_array( $filtered_transactions ) && count( $filtered_transactions ) ) {
            $total_transactions_count = $better_payment_db_obj->get_transactions_amount_by_statuses(array(), '', '', 'count', 1, $filtered_transactions );
            $total_transactions_amount = $better_payment_db_obj->get_transactions_amount_by_statuses(array(), '', '', 'amount', 1, $filtered_transactions );
    
            $completed_statuses = $better_payment_db_obj->allowed_statuses('completed', 'v2');
            $completed_transactions_count = $better_payment_db_obj->get_transactions_amount_by_statuses( $completed_statuses, '', '', 'count', 0, $filtered_transactions );
            $completed_transactions_amount = $better_payment_db_obj->get_transactions_amount_by_statuses( $completed_statuses, '', '', 'amount', 0, $filtered_transactions );
    
            $incomplete_statuses = $better_payment_db_obj->allowed_statuses('incomplete', 'v2');
            $incomplete_transactions_count = $better_payment_db_obj->get_transactions_amount_by_statuses( $incomplete_statuses, '', '', 'count', 1, $filtered_transactions );
            $incomplete_transactions_amount = $better_payment_db_obj->get_transactions_amount_by_statuses( $incomplete_statuses, '', '', 'amount', 1, $filtered_transactions );
            
            $refunded_statuses = $better_payment_db_obj->allowed_statuses('refunded', 'v2');
            $refunded_transactions_count = $better_payment_db_obj->get_transactions_amount_by_statuses( $refunded_statuses, '', '', 'count', 0, $filtered_transactions );
            $refunded_transactions_amount = $better_payment_db_obj->get_transactions_amount_by_statuses( $refunded_statuses, '', '', 'amount', 0, $filtered_transactions );
        }

        $transaction_analytics['total_transactions_count']        = $total_transactions_count;
        $transaction_analytics['completed_transactions_count']    = $completed_transactions_count;
        $transaction_analytics['incomplete_transactions_count']   = $incomplete_transactions_count;
        $transaction_analytics['refunded_transactions_count']     = $refunded_transactions_count;
        
        $transaction_analytics['total_transactions_amount']        = $total_transactions_amount;
        $transaction_analytics['completed_transactions_amount']    = $completed_transactions_amount;
        $transaction_analytics['incomplete_transactions_amount']   = $incomplete_transactions_amount;
        $transaction_analytics['refunded_transactions_amount']     = $refunded_transactions_amount;

        // Recent transactions
        
        return $transaction_analytics;
    }

    /**
     * Get transaction details from better payment table.
     *
     * @param string $args
     * @return object 
     * @since 0.0.1
     */
    public static function get_transaction($id)
    {
        global $wpdb;

        $table = self::get_table_name();
        $transaction_id = (int) sanitize_text_field($id);
        
        $item = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE id = %d",
                $transaction_id
            )
        );

        return $item;
    }
    
    /**
     * Get transaction details from better payment table.
     *
     * @param string $args
     * @return object 
     * @since 0.0.1
     */
    public static function get_transactions_by_email($email)
    {
        global $wpdb;

        $table = self::get_table_name();
        $email = sanitize_text_field($email);
        
        $item = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE email = %s order by payment_date DESC",
                $email
            )
        );

        return $item;
    }

    /**
     * Delete a transaction
     *
     * @param  int $id
     *
     * @return int|boolean
     * @since 0.0.1
     */
    public static function delete_transaction($id)
    {
        global $wpdb;
        $table = self::get_table_name();

        return $wpdb->delete(
            $table,
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Allowed transaction statuses
     * 
     * @since 0.0.1
     */
    public function allowed_statuses($type = 'all', $version = 'v1'){
        $helperObj = new Helper();
        $statuses = $helperObj->get_statuses_by_transaction_type($type, $version);
        
        return $statuses;
    }

    /**
     * Allowed transaction statuses
     * 
     * @since 0.0.1
     */
    public function allowed_statuses_types($version = 'v1'){
        $helperObj = new Helper();
        $statuses = $helperObj->get_transaction_types($version);
        
        return $statuses;
    }

    /**
     * Get transactions amount
     *
     * @return array
     * @since 0.0.1
     */
    public function get_transactions_by_statuses($statuses = array(), $payment_date_from = '', $payment_date_to = '', $fetchNull=0) {
        global $wpdb;

        $table = DB::get_table_name();
        $allowed_statuses = $this->allowed_statuses('all', 'v2');
        
        $nullDataQuery = $fetchNull ? " OR status IS NULL " : '';

        $statuses = is_array($statuses) && count($statuses) ? $statuses : $allowed_statuses;

        #ToDo: Consider currency while calculating total amount
        $statuses = array_map(function($status) use ($allowed_statuses) {
            if(!in_array($status, $allowed_statuses)) {
                return false;
            }

            return "'" . esc_sql($status) . "'";
        }, $statuses);

        $statuses = implode(',', $statuses);

        if(!empty($payment_date_from) && !empty($payment_date_to)){
            $amount = $wpdb->get_results(
                "SELECT amount, payment_date, email FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . ") AND payment_date BETWEEN '" . esc_sql($payment_date_from) . "' AND '" . esc_sql($payment_date_to) . "'"
            );
        } else if(!empty($payment_date_from) && empty($payment_date_to)){
            $amount = $wpdb->get_results(
                "SELECT amount, payment_date, email FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . ") AND payment_date >= '" . esc_sql($payment_date_from) . "'"
            );
        } else {
            $amount = $wpdb->get_results(
                "SELECT amount, payment_date, email FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . " )" 
            );
        }

        return $amount;
    }

    /**
     * Get transactions amount
     *
     * @return array
     * @since 0.0.1
     */
    public function get_transactions_amount_by_statuses($statuses = array(), $payment_date_from = '', $payment_date_to = '', $count_or_amount = 'amount', $fetchNull=0, $filtered_transactions = array() ) {
        global $wpdb;

        $table = DB::get_table_name();
        $allowed_statuses = $this->allowed_statuses('all', 'v2');
        
        $nullDataQuery = $fetchNull ? " OR status IS NULL " : '';
        
        $statuses = is_array($statuses) && count($statuses) ? $statuses : $allowed_statuses;
        $statuses_original = $statuses;

        #ToDo: Consider currency while calculating total amount
        $statuses = array_map(function($status) use ($allowed_statuses) {
            if(!in_array($status, $allowed_statuses)) {
                return false;
            }

            return "'" . esc_sql($status) . "'";
        }, $statuses);

        $statuses = implode(',', $statuses);

        if( is_array($filtered_transactions) && count($filtered_transactions) ){
            $filtered_transactions_by_statuses = array();
            $filtered_transactions_amount = 0;
            
            foreach($filtered_transactions as $filtered_transaction) {
                if( in_array($filtered_transaction->status, $statuses_original) ) {
                    $filtered_transactions_by_statuses[] = $filtered_transaction->id;
                    $filtered_transactions_amount += ! empty( $filtered_transaction->amount ) ? floatval( $filtered_transaction->amount ) : 0;
                }
            } 

            if($count_or_amount == 'count'){
                $filtered_transactions_by_statuses_count = count($filtered_transactions_by_statuses);
                return $filtered_transactions_by_statuses_count;
            }
            
            if($count_or_amount == 'amount'){
                return $filtered_transactions_amount;
            }
        }

        if($count_or_amount == 'count') {
            $count = (int) $wpdb->get_var(
                "SELECT count(id) FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . " )"
            );

            return $count;
        }

        if(!empty($payment_date_from) && !empty($payment_date_to)){
            $amount = $wpdb->get_var(
                "SELECT sum(amount) FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . ") AND payment_date BETWEEN '" . esc_sql($payment_date_from) . "' AND '" . esc_sql($payment_date_to) . "'"
            );
        }else {
            $amount = $wpdb->get_var(
                // #TODO Amount based on filtered transactions
                "SELECT sum(amount) FROM $table WHERE ( status IN (" . $statuses . ") " . esc_sql($nullDataQuery) . ")"
            );
        }

        return $amount;
    }

    public static function search_transactions($items, $search_text, $offset, $per_page){
        $filtered_items = array();
        $search_text = str_replace('%', '', $search_text);
        
        if(count($items) > 0){
            $filtered_items = array_filter($items, function($item) use ($search_text){
                $form_fields_info = maybe_unserialize($item->form_fields_info);
                
                if(
                    isset($form_fields_info['first_name']) && stripos($form_fields_info['first_name'], $search_text) !== false
                    || isset($form_fields_info['last_name']) && stripos($form_fields_info['last_name'], $search_text) !== false
                    || isset($form_fields_info['primary_first_name']) && stripos($form_fields_info['primary_first_name'], $search_text) !== false
                    || isset($form_fields_info['primary_last_name']) && stripos($form_fields_info['primary_last_name'], $search_text) !== false
                    || isset($form_fields_info['email']) && stripos($form_fields_info['email'], $search_text) !== false
                    || isset($form_fields_info['primary_email']) && stripos($form_fields_info['primary_email'], $search_text) !== false
                ){
                    return true;
                }

            });

        }

        return $filtered_items;
    }

    public static function mark_as_completed($id)
    {
        global $wpdb;
        $table = self::get_table_name();

        $id = (int) sanitize_text_field($id);

        if ($id > 0) {
            return $wpdb->update($table, ['status' => 'Completed'], ['id' => $id]);
        }

        return false;
    }
}
