<?php

namespace Better_Payment\Lite\Admin;

use Better_Payment\Lite\Classes\Helper;
use Better_Payment\Lite\Contracts\Pageable;
use Better_Payment\Lite\Controller;

/**
 * The Analytics handler class
 * 
 * @since 0.0.1
 */
class Analytics extends Controller implements Pageable{

    // Check if pro is enabled
    protected $pro_enabled;

    /**
     * Initialize the class
     * 
     * @since 0.0.1
     */
    public function __construct( ) {
        $this->pro_enabled = apply_filters('better_payment/pro_enabled', false);
    }

    /**
     * Render the analytics page
     *
     * @return void
     * @since 0.0.1
     */
    public function render_page() {
        $better_payment_helper_obj = new Helper();

        if( ! $this->pro_enabled ){
            $better_payment_helper_obj->bp_template_render( BETTER_PAYMENT_ADMIN_VIEWS_PATH . '/page-analytics.php');
        }
    }
}