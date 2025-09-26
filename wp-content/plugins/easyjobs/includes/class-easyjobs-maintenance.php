<?php

class Easyjobs_Maintenance {

	public static $instance;

	private $version_db_key = 'easyjobs_version';

	public function __construct(){
		add_action('admin_init', [$this, 'check_for_update'], 5);
		$this->init();
	}

	public static function get_instance( ...$args ) {
		if ( ! isset( self::$instance[static::class] ) ) {
			self::$instance[static::class] = ! empty( $args ) ? new static( ...$args ) : new static;
		}

		return self::$instance[static::class];
	}

	public function check_for_update(){
		$version = get_option($this->version_db_key);
		if(!empty($version)){
			return;
		}
		if(empty(Easyjobs_Helper::get_wp_pages())){
			Easyjobs_Helper::update_wp_pages(Easyjobs_Helper::get_job_pages_by_meta());
		}

		$this->update_version();
	}

	/**
	 * Update WC version to current.
	 */
	private function update_version() {
		update_option( $this->version_db_key, EASYJOBS_VERSION );
	}

	/**
	 * Init Maintenance
	 *
	 * @since 2.4.2
	 * @return void
	 */
	public function init( ) {
		register_activation_hook( EASYJOBS_BASENAME, [__CLASS__, 'activation'] );
		register_deactivation_hook( EASYJOBS_BASENAME, [__CLASS__, 'deactivation'] );
		register_uninstall_hook( EASYJOBS_BASENAME, [__CLASS__, 'uninstall'] );
	}

	/**
	 * Runs on activation
	 *
	 * @since 2.4.2
	 * @return void
	 */
	public static function activation( $network_wide ) {
		require_once plugin_dir_path( __FILE__ ) . 'class-easyjobs-activator.php';
		Easyjobs_Activator::activate( $network_wide );
	}

	/**
	 * Runs on deactivation
	 *
	 * @since 2.4.2
	 * @return void
	 */
	public static function deactivation() {
		require_once plugin_dir_path( __FILE__ ) . 'class-easyjobs-deactivator.php';
		Easyjobs_Deactivator::deactivate();
	}

	/**
	 * Runs on uninstallation.
	 *
	 * @since 2.4.2
	 * @return void
	 */
	public static function uninstall() {
		Easyjobs_Helper::after_disconnect_api();
	}
}
