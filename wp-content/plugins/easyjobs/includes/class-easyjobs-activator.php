<?php

/**
 * Fired during plugin activation
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Easyjobs
 * @subpackage Easyjobs/includes
 * @author     EasyJobs <support@easy.jobs>
 */
class Easyjobs_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate( $network_wide ) {
        flush_rewrite_rules();

        if ( is_multisite() && $network_wide ) {
            return;
        }
        set_transient( 'easyjobs_activation_redirect', true, MINUTE_IN_SECONDS );
	}

}
