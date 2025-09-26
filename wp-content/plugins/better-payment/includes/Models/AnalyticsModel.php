<?php

namespace Better_Payment\Lite\Models;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Model;

/**
 * The Analytics handler database class
 * 
 * @since 0.0.1
 */
class AnalyticsModel extends Model {

    /**
     * Initialize the class
     * 
     * @since 0.0.1
     */
    public function __construct( ) {
        parent::__construct();
    }

    /**
     * Allowed transaction statuses
     * 
     * @since 0.0.1
     */
    public function allowed_statuses($type = 'all', $version = 'v2' ){
        $better_payment_db_obj = new DB();
        $statuses = $better_payment_db_obj->allowed_statuses($type, $version);
        return $statuses;
    }
    
} 