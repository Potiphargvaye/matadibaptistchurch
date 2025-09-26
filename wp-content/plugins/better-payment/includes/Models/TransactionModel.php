<?php

namespace Better_Payment\Lite\Models;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Model;

/**
 * The Transaction handler database class
 * 
 * @since 0.0.4
 */
class TransactionModel extends Model {

    /**
     * Initialize the class
     * 
     * @since 0.0.4
     */
    public function __construct( ) {
        parent::__construct();
    }

    public function get_transactions($per_page = '999999999') {
        $all_transactions = DB::get_transactions(['per_page' => $per_page]);
        return $all_transactions;
    }
} 