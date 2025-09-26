<?php

namespace Better_Payment\Lite;

use Better_Payment\Lite\Admin\Menu;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend handler class
 * 
 * @since 0.0.1
 */
class Frontend extends Controller{

    /**
     * Initialize the class
     * 
     * @since 0.0.1
     */
    public function __construct() {
        // new Frontend\Shortcode();
        $menuObj = new Menu();
        $menuObj->init('frontend');
    }
}
