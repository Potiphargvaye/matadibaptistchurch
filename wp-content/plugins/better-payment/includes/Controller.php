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
 * All classes must extend this class
 * 
 * @since 0.0.1
 */
class Controller {

    use Helper;

    public function __construct() {
        
    }
    
}