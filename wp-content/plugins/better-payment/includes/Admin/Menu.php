<?php

namespace Better_Payment\Lite\Admin;

use Better_Payment\Lite\Controller;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The Menu handler class
 * 
 * @since 0.0.1
 */
class Menu extends Controller{

    protected $pro_enabled;

    /**
     * Initialize the class
     * 
     * @since 0.0.1
     */
    function __construct( ) {
        
    }

    public function init($end = 'backend'){
        $this->pro_enabled = apply_filters('better_payment/pro_enabled', false);

		if('backend' === $end) {
			add_action('admin_enqueue_scripts', array($this, 'handle_scripts_and_styles'));
		}else if('frontend' === $end){
			add_action('wp_enqueue_scripts', array($this, 'handle_scripts_and_styles_frontend'));
		}
    }

    /**
	 * Enqueue scripts and styles
	 * 
	 * @since 0.0.1
	 */
	public function handle_scripts_and_styles() {
		$menu_slugs = $this->better_payment_menu_slugs();

		if (!empty($_REQUEST['page']) && in_array($_REQUEST['page'], $menu_slugs)) {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}
	}

	/**
	 * Enqueue scripts and styles
	 * 
	 * @since 0.0.1
	 */
	public function handle_scripts_and_styles_frontend() {
		wp_enqueue_style('toastr-css');

		wp_enqueue_script('toastr-js');
		wp_enqueue_script('better-payment-script');
        wp_enqueue_script('bp-admin-settings');
        wp_enqueue_script('better-payment-common-script');
        wp_enqueue_script('better-payment-select2-script');
		wp_enqueue_script('jquery-ui-datepicker');
	}

	/**
	 * Enqueue scripts
	 * 
	 * @since 0.0.1
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('bp-admin-settings');
		wp_enqueue_script('toastr-js-admin');
		wp_enqueue_script('sweetalert2-js');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('better-payment-common-script');
	}

	/**
	 * Enqueue styles
	 * 
	 * @since 0.0.1
	 */
	public function enqueue_styles() {
    	wp_enqueue_style('jquery-ui');  
    	wp_enqueue_style('better-payment-common-style');  
    	wp_enqueue_style('bp-settings-style');  
    	wp_enqueue_style('bp-icon-admin');  
    	wp_enqueue_style('toastr-css-admin');  
    	wp_enqueue_style('sweetalert2-css');  
    	wp_enqueue_style('better-payment-admin-style');  
	}

    /**
	 * Menu slugs
	 * 
	 * @since 0.0.1
	 */
	public function better_payment_menu_slugs() {
		$menu_slugs = array(
			'better-payment-settings', 
			'better-payment-transactions',
		);

		if( ! $this->pro_enabled ){
			$menu_slugs[] = 'better-payment-analytics';
		}

		return apply_filters( 'better_payment/admin/get_page_menu_slug_list', $menu_slugs );
	}

}