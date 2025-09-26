<?php

namespace Better_Payment\Lite\Admin;

use Better_Payment\Lite\Classes\Helper;
use Better_Payment\Lite\Controller;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The plugin settings class
 * 
 * @since 0.0.1
 */
class Settings extends Controller
{
	// Check if pro is enabled
    protected $pro_enabled;
	
	var $page_slug_prefix = '';
	
	/**
	 * @var int|string
	 */
	protected $file_version;

	/**
	 * Constructor
	 * 
	 * @since 0.0.1
	 */
	public function __construct($page_slug_prefix = 'better-payment')
	{
		$this->page_slug_prefix = $page_slug_prefix;
		$this->file_version = defined('WP_DEBUG') && WP_DEBUG ? time() : BETTER_PAYMENT_VERSION;
		
        $this->pro_enabled = apply_filters('better_payment/pro_enabled', false);

		add_action('admin_menu', [$this, 'register_menu']);
		add_action('wp_ajax_better_payment_settings_action', [$this, 'save_settings_ajax']);

		//Transactions page: actions
		add_action('wp_ajax_better-payment-delete-transaction', [$this, 'admin_transaction_delete']);
		add_action('wp_ajax_better-payment-filter-transaction', [$this, 'admin_transaction_filter']);
		add_action('wp_ajax_better-payment-view-transaction', [$this, 'admin_transaction_view']);
		add_action('wp_ajax_better-payment-mark-as-completed', [$this, 'admin_transaction_mark_as_completed']);
		//EL Editor Style
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'editor_enqueue_scripts']);
	}

	/**
	 * Enqueue editor scripts
	 * 
	 * @since 0.0.1
	 */
    public function editor_enqueue_scripts()
    {
        // bp icon font
        wp_enqueue_style(
            'bp-icon-admin-editor',
            BETTER_PAYMENT_ASSETS . '/icon/style.min.css',
            false
        );
    }

	/**
	 * Register menu
	 * 
	 * @since 0.0.1
	 */
	public function register_menu()
	{
		add_menu_page(
			__('Better Payment', 'better-payment'),
			__('Better Payment', 'better-payment'),
			'manage_options',
			$this->page_slug_prefix . '-settings',
			[$this, 'render_better_payment_admin_pages'],
			BETTER_PAYMENT_ASSETS . '/img/better-payment-icon-white-small.png',
			64
		);

		add_submenu_page(
			$this->page_slug_prefix . '-settings',
			__('Settings', 'better-payment'),
			__('Settings', 'better-payment'),
			'manage_options',
			$this->page_slug_prefix . '-settings',
			[$this, 'render_better_payment_admin_pages'],
			64
		);
		
        $analyticsObj = new Analytics();

		$settings = apply_filters( 'better_payment/admin/get_submenu_page_list', array(
            $this->page_slug_prefix . '-transactions'   => array(
                'title'      => __('Transactions', 'better-payment'),
                'capability' => 'manage_options',
                'callback'   => [$this, 'render_better_payment_admin_pages'],
			),
        ), $this->page_slug_prefix );

		if( ! $this->pro_enabled ){
			$settings[ $this->page_slug_prefix . '-analytics' ] = array(
				'title'      => __('Analytics', 'better-payment'),
				'capability' => 'manage_options',
				'callback'   => [$analyticsObj, 'render_page'],
			);
		}

		foreach( $settings as $slug => $setting ) {
            $cap  = isset( $setting['capability'] ) ? $setting['capability'] : 'manage_options';
            add_submenu_page( $this->page_slug_prefix . '-settings', $setting['title'], $setting['title'], $cap, $slug, $setting['callback'] );
        }
	}

	/**
	 * Admin pages
	 * 
	 * @since 0.0.1
	 */
	public function render_better_payment_admin_pages()
	{
		$bp_admin_saved_settings = DB::get_settings();
		$bp_admin_all_transactions_count = DB::get_transaction_count();
		$bp_admin_completed_transactions_count = DB::get_transaction_count('', 'v2', 0, 'completed');
		$bp_admin_incomplete_transactions_count = DB::get_transaction_count('', 'v2', 1, 'incomplete');

		$template = !empty($_GET['page']) ? sanitize_text_field($_GET['page']) : 'better-payment-settings';

		$menuObj = new Menu();
		$menu_slugs = $menuObj->better_payment_menu_slugs();
		$template = in_array($template, $menu_slugs) ? $template : 'better-payment-settings';

		$transaction_pagination_args = [];
		$bp_admin_all_transactions_paginations = '';
		$viewMode = 0;

		if ($template == 'better-payment-transactions') {
			//Transactions Page
			$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
			$id     = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$template = 'better-payment-transaction';

			switch ($action) {
				case 'edit':
					$template .= '-edit';
					break;

				case 'view':
					$viewMode = 1;
					$template .= '-view';
					break;

				default:
					$template .= '-list';
					break;
			}

			$ui_postfix = '';
			$template .= $ui_postfix;

			//Pagination
			$transaction_pagination_paged 		= isset($_GET['paged']) && intval($_GET['paged']) > 1 	? intval($_GET['paged']) : 1;
			$transaction_pagination_order 		= isset($_GET['order']) 		? sanitize_text_field($_GET['order']) 		: 'DESC';
			$transaction_pagination_orderby 	= isset($_GET['orderby']) 		? sanitize_text_field($_GET['orderby']) 	: 'id';
			$transaction_pagination_per_page 	= isset($_GET['per_page']) 		? intval($_GET['per_page']) 				: 20;
			$transaction_pagination_source 		= isset($_GET['source']) 		? sanitize_text_field($_GET['source']) 		: '';
			$transaction_pagination_status 		= isset($_GET['status']) 		? sanitize_text_field($_GET['status']) 		: 'all';
			$transaction_pagination_search_text = isset($_GET['search_text']) 	? sanitize_text_field($_GET['search_text']) : '';

			if ($transaction_pagination_paged) {
				$args = 
				$transaction_pagination_args = [
					'search_text' => $transaction_pagination_search_text,
					'per_page' => $transaction_pagination_per_page,
					'paged' => $transaction_pagination_paged,
					'number' => $transaction_pagination_per_page,
					'offset' => ($transaction_pagination_paged - 1) * $transaction_pagination_per_page,
					'orderby' => $transaction_pagination_orderby,
					'order' => $transaction_pagination_order,
					'status' => $transaction_pagination_status
				];
			}
			$bp_admin_filtered_all_transactions_count = DB::get_transaction_count( $transaction_pagination_args, 'v2', 1);
			$bp_admin_filtered_all_transactions_count = $transaction_pagination_search_text ? $bp_admin_filtered_all_transactions_count : $bp_admin_all_transactions_count;

			$bp_admin_all_transactions_paginations = self::bp_paginate_links( $transaction_pagination_args, $bp_admin_filtered_all_transactions_count );
			$paginations_showing_entities_html = self::bp_paginate_showing_entities_html( $transaction_pagination_args, $bp_admin_filtered_all_transactions_count );
		
			$fetchNullIfIncomplete = $transaction_pagination_status === 'incomplete' ? 1 : 0;
			$bp_admin_all_transactions = DB::get_transactions($transaction_pagination_args, 0, 'v2', $fetchNullIfIncomplete);	
		}
		
		$bp_admin_all_transactions_analytics = DB::get_transactions_analytics();

		//Transaction details/view page
		if($viewMode){
			$transaction_id = isset($_REQUEST['id']) ? (int) sanitize_text_field($_REQUEST['id']) : 0;
			if($transaction_id == 0){
				esc_html_e('Record not found!', 'better-payment');
				exit;
			}
			$bp_admin_transaction = DB::get_transaction($transaction_id);
			
			//Fetch referer content
			$better_payment_helper = new Helper();
			$bp_admin_transaction_form_fields = is_object($bp_admin_transaction) && isset($bp_admin_transaction) ? maybe_unserialize($bp_admin_transaction->form_fields_info) : [];
			$referer_page_id = !empty($bp_admin_transaction_form_fields['referer_page_id']) ? $bp_admin_transaction_form_fields['referer_page_id'] : '';
			$referer_widget_id = !empty($bp_admin_transaction_form_fields['referer_widget_id']) ? $bp_admin_transaction_form_fields['referer_widget_id'] : '';
			
			$bp_transaction_referer_content = '';
			
			if($referer_page_id != '' && $referer_widget_id != ''){
				$bp_transaction_referer_content = $better_payment_helper->get_better_payment_widget_settings($referer_page_id, $referer_widget_id);
			}
		}

		include_once BETTER_PAYMENT_ADMIN_VIEWS_PATH . '/' . "$template.php";
	}

	/**
	 * Settings save ajax
	 * 
	 * @since 0.0.1
	 */
	public static function save_settings_ajax()
	{
		/**
		 * Verify the Nonce
		 */
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'better_payment_admin_nonce')) {
			return;
		}

		if (!current_user_can('manage_options')) {
            return;
		}

		if (isset($_POST['form_data']) && $_POST['reset_button'] == 'true') {
			self::save_default_settings(1);
			wp_send_json_success("success");
		} else if (isset($_POST['form_data'])) {
			self::save_settings($_POST['form_data']);
			wp_send_json_success("success");
		} else {
			wp_send_json_error("error");
		}

		die;
	}

	/**
	 * This function is responsible for 
	 * save all settings data, including checking the disable field to prevent
	 * users manipulation.
	 *
	 * @param array $values
	 * @return void
	 * @since 0.0.1
	 */
	public static function save_settings($posted_fields = [])
	{
		$saved_settings = DB::get_settings();
		$default_settings = DB::default_settings();

		$saved_settings = wp_parse_args($default_settings, $saved_settings); // newly added default values to merge
		
		$data = [];
		$new_posted_fields = [];

		if (!empty($posted_fields)) {
			foreach ($posted_fields as $posted_field) {
				$new_posted_fields[$posted_field['name']] = $posted_field['value'];
			}
		}

		if (count($new_posted_fields)) {
			
			foreach ($saved_settings as $saved_settings_item_key => $saved_settings_item_value) {
				if (isset($new_posted_fields[$saved_settings_item_key]) && $new_posted_fields[$saved_settings_item_key] != '') {
					$data[$saved_settings_item_key] = self::sanitize_field($saved_settings_item_key, $new_posted_fields[$saved_settings_item_key]);
				} else {
					$data[$saved_settings_item_key] = $saved_settings_item_value;
				}
			}
		}

		if (count($data)) {
			DB::update_settings($data);
		}

		return;
	}

	/**
	 * Save default settings
	 *
	 * @since 0.0.1
	 */
	public static function save_default_settings($reset = 0)
	{
		$default_settings = DB::default_settings();
		$saved_settings = DB::get_settings();

		//Do nothing : if already saved && not reset
		if (!empty($saved_settings) && (!$reset)) {
			return false;
		}

		return DB::update_settings($default_settings);
	}

	/**
	 * transaction template
	 *
	 * @since 0.0.1
	 */
	public function admin_transaction_view()
	{
		$response = [];

		if (!wp_verify_nonce($_REQUEST['nonce'], 'better_payment_admin_nonce')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		if (!current_user_can('manage_options')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		$transaction_id = isset($_REQUEST['id']) ? (int) sanitize_text_field($_REQUEST['id']) : 0;

		$bp_admin_transaction = '';

		if($transaction_id == 0){
			return __('Record not found!', 'better-payment');
		}

		$bp_admin_transaction = DB::get_transaction($transaction_id);

		include_once BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/template-transaction-view.php";
		exit;
	}

	/**
	 * Transaction delete
	 *
	 * @since 0.0.1
	 */
	public function admin_transaction_delete()
	{
		$response = [];

		if (!wp_verify_nonce($_REQUEST['nonce'], 'better_payment_admin_nonce')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		if (!current_user_can('manage_options')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

		if (DB::delete_transaction($id)) {
			$response['message'] = __("Deleted Successfully!", 'better-payment');

			$current_page = sanitize_text_field($_REQUEST['currentPage']);
			$per_page = sanitize_text_field($_REQUEST['perPage']);
			$total_entry_count = sanitize_text_field($_REQUEST['totalEntryCount']);

			$args = [
				'paged' 	=> $current_page,
				'per_page' 	=> $per_page,
			];

			$response['pagination_showing_entities_html'] = self::bp_paginate_showing_entities_html($args, intval($total_entry_count) - 1);
			wp_send_json_success($response);
		} else {
			$response['message'] = __("Something went wrong!", 'better-payment');
			wp_send_json_error( $response );
		}
	}

	/**
	 * Transaction filter	
	 *
	 * @since 0.0.1
	 */
	public function admin_transaction_filter()
	{
		$response = [];

		if (!wp_verify_nonce($_REQUEST['nonce'], 'better_payment_admin_nonce')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		if (!current_user_can('manage_options')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}
		
		$filter_search_text = ( isset($_REQUEST['filterFormData']['search_text']) && !empty($_REQUEST['filterFormData']['search_text']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['search_text'] ) : '';
		$filter_payment_date_from = ( isset($_REQUEST['filterFormData']['payment_date_from']) && !empty($_REQUEST['filterFormData']['payment_date_from']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['payment_date_from'] ) : '';
		$filter_payment_date_to = ( isset($_REQUEST['filterFormData']['payment_date_to']) && !empty($_REQUEST['filterFormData']['payment_date_to']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['payment_date_to'] ) : '';
		$filter_order_by = ( isset($_REQUEST['filterFormData']['order_by']) && !empty($_REQUEST['filterFormData']['order_by']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['order_by'] ) : 'id';
		$filter_order = ( isset($_REQUEST['filterFormData']['order']) && !empty($_REQUEST['filterFormData']['order']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['order'] ) : 'DESC';
		$filter_status = ( isset($_REQUEST['filterFormData']['status']) && !empty($_REQUEST['filterFormData']['status']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['status'] ) : 'all';
		$filter_source = ( isset($_REQUEST['filterFormData']['source']) && !empty($_REQUEST['filterFormData']['source']) ) ? sanitize_text_field( $_REQUEST['filterFormData']['source'] ) : '';
		$filter_paged = ( isset($_REQUEST['filterFormData']['paged']) && !empty($_REQUEST['filterFormData']['paged']) ) ? intval( $_REQUEST['filterFormData']['paged'] ) : 1;
		$filter_per_page = ( isset($_REQUEST['filterFormData']['per_page']) && !empty($_REQUEST['filterFormData']['per_page']) ) ? intval( $_REQUEST['filterFormData']['per_page'] ) : 20;
		
		$args = array(
            'search_text' => $filter_search_text,
            'payment_date_from' => $filter_payment_date_from, 
            'payment_date_to' => $filter_payment_date_to, 
            'order_by' => $filter_order_by,
            'order' => $filter_order,
            'status' => $filter_status,
            'source' => $filter_source, 
            'paged' => $filter_paged, 
            'per_page' => $filter_per_page, 
        );

		if( !empty($args['payment_date_from']) ){
			$args['payment_date_from'] = date('Y-m-d H:i:s',strtotime($args['payment_date_from']));
		}
		if( !empty($args['payment_date_to']) ){
			$args['payment_date_to'] = date('Y-m-d H:i:s',strtotime($args['payment_date_to']));
		}

		$args['offset'] = ( $args['paged'] - 1) * $args['per_page']; 
		
		$fetchNullIfIncomplete = !empty($args['status']) && 'incomplete' === $args['status'] ? 1 : 0;
		$bp_admin_all_transactions = DB::get_transactions($args, 0, 'v2', $fetchNullIfIncomplete);
		
		//Pagination
		$fetchNull = !empty($args['status']) && 'incomplete' === $args['status'] ? 1 : 0;
		$only_incompleted_transactions = !empty($args['status']) && 'incomplete' === $args['status'] ? 1 : 0;
		$only_completed_transactions = !empty($args['status']) && 'completed' === $args['status'] ? 1 : 0;

		$bp_admin_all_transactions_count = DB::get_transaction_count($args, 'v2', $fetchNull);
		$bp_admin_all_transactions_paginations = self::bp_paginate_links($args, $bp_admin_all_transactions_count );
		$paginations_showing_entities_html = self::bp_paginate_showing_entities_html($args, $bp_admin_all_transactions_count );

		$bp_admin_completed_transactions_count = DB::get_transaction_count($args, 'v2', 0, 'completed');
		$bp_admin_incomplete_transactions_count = DB::get_transaction_count($args, 'v2', 1, 'incomplete');

		$bp_admin_completed_transactions_count = $only_incompleted_transactions ? 0 : $bp_admin_completed_transactions_count;
		$bp_admin_incomplete_transactions_count = $only_completed_transactions ? 0 : $bp_admin_incomplete_transactions_count;

		include_once BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/template-transaction-list.php";
		exit;
	}

	/**
	 * Paging links	
	 *
	 * @since 0.0.1
	 */
	public static function bp_paginate_links($args, $total_entry_count )
	{
		$pagination_html = '';

		$defaults = array(
            'search_text' => '',
            'per_page' => 20, 
            'paged' => 1, 
        );
		
		$args = wp_parse_args($args, $defaults);

		$current_page 		= intval($args['paged']);
		$per_page 			= intval($args['per_page']);
		$total_entry_count 	= intval($total_entry_count);
		$total_page_count 	= ceil($total_entry_count / $per_page);

		$previous_page 		= $current_page - 1;
		$next_page 			= $current_page + 1;

		$prev_button_disabled = (1 == $current_page) ? 'disabled' : '';
		$next_button_disabled = $total_page_count == $current_page ? 'disabled' : '';

		$prev_button_link = ($current_page > 1) ? self::get_pagination_link($args, $previous_page) : "#";
		$next_button_link = ($current_page < $total_page_count) ? self::get_pagination_link($args, $next_page) : "#";

		$showing_entry_from = $per_page * ($current_page - 1) + 1;
		
		$pagination_html .= "<ul class=''>";

		//Showing entries content
		if ($showing_entry_from > $total_entry_count) {
			return '';
		}

		if ( $total_page_count == 1 ) {
			return '';
		}
		
		//Previous button
		if($current_page > 1){
			$pagination_html .= "<li><a href='$prev_button_link' $prev_button_disabled  class='pagination-previous'><i class='bp-icon bp-caret-left'></i></a></li>";
		}

		//Pagination links 
		for ($i = 1; $i <= $total_page_count; $i++) {
			$is_current_class = ($i == $current_page) ? 'is-current active' : '';
			$page_link = self::get_pagination_link($args, $i);

			if ( $total_page_count > 5 ) {
				$first_page = 1;
				$last_page 	= $total_page_count;
				
				switch( $i ){
					case $first_page		:
					case $last_page			:
					case $current_page - 2 	:
					case $current_page - 1	:
					case $current_page		:
					case $current_page + 1	:
					case $current_page + 2	:
					case $i + 3 && $current_page < 6 && $i < 6:
					case $i + 4 && $current_page < 6 && $i < 6:
						$pagination_html .= "
							<li>
								<a href=\"$page_link\" class=\"pagination-link $is_current_class \" aria-label=\"Page $i\"> $i </a>
							</li>
						";
						break;
					case $current_page - 3	:
					case $current_page + 3	:
						$pagination_html .= "
							<li>
								...
							</li>
						";
						break;
					default:
						break;
				}

			} else {
				$pagination_html .= "
					<li>
						<a href=\"$page_link\" class=\"pagination-link $is_current_class \" aria-label=\"Page $i\"> $i </a>
					</li>
				";
			}
		}

		//Next button
		if($current_page < $total_page_count){
			$pagination_html .= "<li><a href='$next_button_link' class='pagination-next' $next_button_disabled><i class='bp-icon bp-caret-right'></i></a></li>";
		}

		$pagination_html .= "</ul>";

		return $pagination_html;
	}

	protected static function get_pagination_link( $args, $target_paged ){
		$per_page 			= ! empty( $args['per_page'] ) ? intval( $args['per_page'] ) : 20;
		$search_text 		= ! empty( $args['search_text'] ) ? sanitize_text_field( $args['search_text'] ) : '';
        $valid_search_text 	= $search_text != '' && strlen($search_text) >= 2;

		$button_link 		= admin_url("admin.php?page=better-payment-transactions&per_page={$per_page}&paged={$target_paged}");
		
		if($valid_search_text){
			$button_link = admin_url("admin.php?page=better-payment-transactions&per_page={$per_page}&paged={$target_paged}&search_text={$search_text}");
		}

		return $button_link;
	}

	/**
	 * Pagination showing entities html
	 *
	 * @since 0.0.1
	 */
	public static function bp_paginate_showing_entities_html($args, $total_entry_count)
	{
		$pagination_showing_entries_html = '';

		$defaults = array(
            'per_page' => 20, 
            'paged' => 1, 
        );
		
		$args = wp_parse_args($args, $defaults);

		$current_page 		= intval($args['paged']);
		$per_page 			= intval($args['per_page']);
		
		$total_entry_count 	= intval($total_entry_count);
		$total_page_count 	= ceil($total_entry_count / $per_page);

		$showing_entry_from = $per_page * ($current_page - 1) + 1;
		$showing_entry_to = $per_page * $current_page;

		//Showing entries content
		if ($total_page_count == 1 || $current_page == $total_page_count) {
			$showing_entry_to = $total_entry_count;
		}

		$pagination_showing_entries_html = __("Showing {$showing_entry_from}-{$showing_entry_to} of $total_entry_count entries", "better-payment");

		if ($showing_entry_from > $total_entry_count) {
			return '';
		}

		if ($total_page_count == 1) {
			return $pagination_showing_entries_html;
		}

		return $pagination_showing_entries_html;
	}

	/**
	 * This function is responsible for the data sanitization
	 *
	 * @param array $field
	 * @param string|array $value
	 * @return string|array
	 * @since 0.0.1
	 */
	public static function sanitize_field($field, $value)
	{
		if (isset($field['sanitize']) && !empty($field['sanitize'])) {
			if (function_exists($field['sanitize'])) {
				$value = call_user_func($field['sanitize'], $value);
			}
			return $value;
		}

		if (is_array($field) && isset($field['type'])) {
			switch ($field['type']) {
				case 'text':
					$value = sanitize_text_field($value);
					break;
				case 'textarea':
					$value = sanitize_textarea_field($value);
					break;
				case 'email':
					$value = sanitize_email($value);
					break;
				default:
					return $value;
					break;
			}
		} else {
			$value = sanitize_text_field($value);
		}

		return $value;
	}

	public function admin_transaction_mark_as_completed()
	{
		$response = [];

		if (!wp_verify_nonce($_REQUEST['nonce'], 'better_payment_admin_nonce')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}

		if (!current_user_can('manage_options')) {
			$response['message'] = __("Access Denied!", 'better-payment');
			wp_send_json_error($response);
		}
		
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		if ($id) {
			$updated = DB::mark_as_completed($id);

			if ($updated) {
				$response['message'] = __("Transaction marked as completed!", 'better-payment');
				wp_send_json_success($response);
			} else {
				$response['message'] = __("Failed to mark transaction as completed!", 'better-payment');
				wp_send_json_error($response);
			}
		}
	}
}
