<?php

namespace Better_Payment\Lite;

use Better_Payment\Lite\Admin\DB;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Database Handler class
 * All DB related classes must extend this class
 * 
 * @since 0.0.1
 */

class Model {

    /**
     * Plugin database table name
     * 
     * @since 0.0.1
     */
    public $table_name = '';

    /**
     * Initialize the class
     * 
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->table_name = DB::get_table_name();
    }
}
