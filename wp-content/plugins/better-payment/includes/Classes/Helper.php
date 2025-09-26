<?php

namespace Better_Payment\Lite\Classes;

use Better_Payment\Lite\Controller;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;

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
 */
class Helper extends Controller
{
    use TraitsHelper;

    /**
     * Check if elementor plugin is activated
     *
     * @since 0.0.2
     */
    public function elementor_not_loaded()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (isset($_GET['page']) && $_GET['page'] == 'better-payment-setup-wizard') {
            return;
        }

        if(wp_doing_ajax()){
            return;
        }

        $elementor = 'elementor/elementor.php';

        if ($this->is_plugin_installed($elementor)) {
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);

            $message = sprintf(__('%1$sBetter Payment%2$s requires %1$sElementor%2$s plugin to be active. Please activate Elementor to continue.', 'better-payment'), "<strong>", "</strong>");

            $button_text = __('Activate Elementor', 'better-payment');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

            $message = sprintf(__('%1$sBetter Payment%2$s requires %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.', 'better-payment'), '<strong>', '</strong>');
            $button_text = __('Install Elementor', 'better-payment');
        }

        $button = '<p><a href="' . esc_url_raw($activation_url) . '" class="button-primary">' . $button_text . '</a></p>';

        printf('<div class="error"><p>%1$s</p>%2$s</div>', wp_kses( __( $message, 'better-payment' ), $this->bp_allowed_tags() ), wp_kses( $button, $this->bp_allowed_tags() ) );
    }

    /**
     * Check if a plugin is installed
     *
     * @since 0.0.2
     */
    public function is_plugin_installed($basename)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        return isset($plugins[$basename]);
    }

    /**
	 * check is plugin active or not
	 *
	 * @param $plugin
	 * @return bool
     * @since 0.0.2
	 */
    public function is_plugin_active($plugin) {
	    if ( !function_exists( 'is_plugin_active' ) ){
		    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

	    return is_plugin_active( $plugin );
    }

    public static function sanitize_bp_field($key, $arr, $default_value='', $type='text'){
        $value = $default_value;
        
        if( !empty( $arr[ $key ] ) ) {
            $value = $arr[ $key ];

            switch($type){
                case 'text':
                    $value = sanitize_text_field( $value );
                    break;
                case 'email':
                    $value = sanitize_email( $value );
                    break;
                case 'textarea':
                    $value = sanitize_textarea_field( $value );
                    break;
                default: 
                    $value = sanitize_text_field( $value );
                    break;
            }
        }

        return $value;
    }

    /**
     * Get widget settings
     *
     * @since 0.0.1
     */
    public function get_better_payment_widget_settings( $page_id, $widget_id ) {
        $document = \Elementor\Plugin::$instance->documents->get( $page_id );
        $settings = [];
        if ( $document ) {
            $elements    = \Elementor\Plugin::instance()->documents->get( $page_id )->get_elements_data();
            $widget_data = $this->find_element_recursive( $elements, $widget_id );
            if ( $widget_data ) {
                $widget      = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $widget_data );
                if ( $widget ) {
                    $settings = $widget->get_settings_for_display();
                }
            }
            
        }
        return $settings;
    }

    /**
     * Find element recursively
     *
     * @since 0.0.1
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

    public function titleToSnake($text, $divider = '_') {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n_a';
        }

        return $text;
    }

    public function arrayToString($array, $separator = ', ', $extraString = ''){
        $string = rtrim( implode($separator, $array ), $separator ) ;
        if($extraString) {
            $string .= $extraString;
        }

        return $string;
    }

    public function transaction_statuses_with_type(){
        $statuses = [
            'completed' => [
                'statuses' => [
                    'paid',
                    'Completed',
                    'completed',
                ],
                'color' => '#0ECA86',
            ], 
            'processing' => [
                'statuses' => [
                    'pending',
                    'Pending',
                    NULL,
                ],
                'color' => '#FFDA15',
            ],
            'refunded' => [
                'statuses' => [
                    'refunded',
                ],
                'color' => '#FF0202',
            ],
            'unpaid' => [
                'statuses' => [
                    'unpaid',
                ],
                'color' => '#999',
            ],
            'all' => [
                'statuses' => [
                    'paid',
                    'Completed',
                    'completed',
                    'unpaid',
                    'refunded',
                    'pending',
                    'Pending',
                    NULL,
                ],
                'color' => '#999',
            ],
        ];

        return $statuses;
    }

    public function transaction_statuses_with_type_v2(){
        $statuses = [
            'completed' => [
                'statuses' => [
                    'paid',
                    'Completed',
                    'completed',
                    'success',
                ],
                'color' => '#0ECA86',
            ], 
            'incomplete' => [
                'statuses' => [
                    'pending',
                    'Pending',
                    'unpaid',
                    'incomplete',
                    'Incomplete',
                    NULL,
                ],
                'color' => '#FFDA15',
            ],
            'refunded' => [
                'statuses' => [
                    'refunded',
                ],
                'color' => '#FF0202',
            ],
            'all' => [
                'statuses' => [
                    'paid',
                    'Completed',
                    'completed',
                    'unpaid',
                    'refunded',
                    'pending',
                    'Pending',
                    'success',
                    'incomplete',
                    'Incomplete',
                    NULL,
                ],
                'color' => '#999',
            ],
        ];

        return $statuses;
    }

    public function get_type_by_transaction_status($status = '', $version = 'v1'){
        if('v1' === $version){
            $statuses = $this->transaction_statuses_with_type();
        } else {
            $statuses = $this->transaction_statuses_with_type_v2();
        }

        $status_type = '';
        if(!empty($status)){
            foreach($statuses as $type => $statuses_and_color){
                if(in_array($status, $statuses_and_color['statuses'])){
                    $status_type = $type;
                    break;
                }
            }
        }

        if($status === '' || $status === NULL){
            $status_type = 'incomplete';
        }

        return $status_type;
    }

    public function get_color_by_transaction_status($status = '', $version = 'v1'){
        if('v1' === $version){
            $statuses = $this->transaction_statuses_with_type();
        } else {
            $statuses = $this->transaction_statuses_with_type_v2();
        }

        $color = '#999';
        if(!empty($status)){
            foreach($statuses as $type => $statuses_and_color){
                if(in_array($status, $statuses_and_color['statuses'])){
                    $color = $statuses_and_color['color'];
                    break;
                }
            }
        }

        if($status === '' || $status === NULL){
            $color = '#FFDA15'; //incomplete
        }

        return $color;
        
    }

    public function get_color_by_transaction_type($status_type = '', $version = 'v1'){
        if('v1' === $version){
            $statuses = $this->transaction_statuses_with_type();
        } else {
            $statuses = $this->transaction_statuses_with_type_v2();
        }

        $color = '';
        if(!empty($status_type)){
            foreach($statuses as $type => $statuses_and_color){
                if($type == $status_type){
                    $color = $statuses_and_color['color'];
                    break;
                }
            }
        }

        return $color;
    }

    public function get_statuses_by_transaction_type($status_type = 'all', $version = 'v1'){
        if('v1' === $version){
            $statuses = $this->transaction_statuses_with_type();
        } else {
            $statuses = $this->transaction_statuses_with_type_v2();
        }

        $transaction_statuses = [];
        if(!empty($status_type)){
            foreach($statuses as $type => $statuses_and_color){
                if($type == $status_type){
                    $transaction_statuses = $statuses_and_color['statuses'];
                    break;
                }
            }
        }

        return $transaction_statuses;
    }
    
    public function get_transaction_types($version = 'v1'){
        if('v1' === $version){
            $statuses = $this->transaction_statuses_with_type();
        } else {
            $statuses = $this->transaction_statuses_with_type_v2();
        }

        $statuses_types = array_keys($statuses);
        
        return $statuses_types;
    }

    /**
     * Get interval text for recurring and split payment
     *
     * @since 1.3.0
     */
    public function get_interval_text( $interval = '' ){
        $interval_text = '';

        switch( $interval ) {
            case 'day':
                $interval_text = 'Daily';
                break;

            case 'month': 
            case 'year':
                $interval_text = $interval . 'ly';
                break;

            default :
                $interval_text = ! empty( $interval ) ? "Per " . esc_html( $interval ) : "";
                break;
        }

        return esc_html( ucfirst( $interval_text ) );
    }
}


//Helper Functions
if( !function_exists('better_payment_dd') ){
    function better_payment_dd($data, $show_query = 0, $print_only = 0) {
        global $wpdb;

        if(1 === $show_query){
            // Print last SQL query string
            echo wp_kses_post( $wpdb->last_query );

            //Print last SQL query result
            echo wp_kses_post( $wpdb->last_result );

            //Print last SQL query Error
            echo wp_kses_post( $wpdb->last_error );
        }
        
        echo "<pre>";
        print_r($data);
        if(!$print_only){
            wp_die('Printed!');
        }
    }
}
