<?php

namespace Better_Payment\Lite;

use Better_Payment\Lite\Traits\Helper;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax handler class
 * 
 * @since 0.0.1
 */
class Ajax extends Controller{

    use Helper;
    /**
     * Class constructor
     * 
     * @since 0.0.1
     */
    public function __construct() {
        
    }
}
