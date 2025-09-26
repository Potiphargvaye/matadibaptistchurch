<?php

namespace Better_Payment\Lite\Classes;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The plugin migrator class
 * 
 * @since 0.0.2
 */
class Migrator
{

    /**
     * Initialize the plugin
     * 
     * @since 0.0.2
     */
    public static function migrator() {
        self::update_tables();
    }

    /**
     * Update the plugin tables
     * 
     * @since 0.0.2
     */
    public static function update_tables() {
        global $wpdb;
        $wpdb->hide_errors();
        $table_name = "{$wpdb->prefix}better_payment";

        //Add column
        $column_name = sanitize_text_field("refund_info");
        $column_type = sanitize_text_field("longtext");
        $row = $wpdb->get_results(  "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table_name' AND column_name = '$column_name'"  );

        if(empty($row)){
            $wpdb->query($wpdb->prepare("ALTER TABLE $table_name ADD $column_name $column_type"));
        }
    }
}
