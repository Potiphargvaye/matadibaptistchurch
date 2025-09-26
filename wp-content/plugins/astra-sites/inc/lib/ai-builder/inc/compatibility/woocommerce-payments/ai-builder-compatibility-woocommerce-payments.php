<?php
/**
 * AI Builder Compatibility for 'WooCommerce Payments'
 *
 * @see  https://wordpress.org/plugins/woocommerce-payments/
 *
 * @package AI Builder
 * @since 1.2.37
 */

if ( ! class_exists( 'Ai_Builder_Compatibility_WooCommerce_Payments' ) ) {

	/**
	 * WooCommerce Payments Compatibility
	 *
	 * @since 1.2.37
	 */
	class Ai_Builder_Compatibility_WooCommerce_Payments {
		/**
		 * Instance
		 *
		 * @access private
		 * @var self Class object.
		 * @since 1.2.37
		 */
		private static $instance;

		/**
		 * Constructor
		 *
		 * @since 1.2.37
		 */
		public function __construct() {
			add_action( 'astra_sites_after_plugin_activation', array( $this, 'woo_payments_activate' ), 10, 2 );
			add_action( 'admin_notices', array( $this, 'woo_payments_admin_notice' ), 9 );
			add_action( 'wp_ajax_dismiss_woopayments_notice', array( $this, 'dismiss_woopayments_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_init', array( $this, 'maybe_append_woopayments_ref' ) );
			add_filter( 'cpsw_notices_add_args', array( $this, 'hide_cpsw_stripe_connect_notice' ), 10, 1 );
		}

		/**
		 * Initiator
		 *
		 * @since 1.2.37
		 * @return self Initialized object of the class.
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Retrieves the WooCommerce Payments reference ID.
		 *
		 * @since 1.2.37
		 * @return string The WooCommerce Payments reference ID.
		 */
		public static function get_woopayments_ref_id() {
			return 'bsf-2025';
		}

		/**
		 * Enqueue scripts and localize data.
		 *
		 * @since 1.2.37
		 * @return void
		 */
		public function enqueue_scripts() {
			// Check if WooPayments plugin is active.
			if ( ! is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ) {
				return;
			}

			// Get current screen.
			$screen = get_current_screen();

			// Check if we're on the dashboard, plugins page, or WooPayments connect page.
			// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only GET used safely here.
			$is_woo_payments_page = isset( $_GET['page'], $_GET['path'] ) && 'wc-admin' === sanitize_text_field( $_GET['page'] ) && '/payments/connect' === sanitize_text_field( rawurldecode( $_GET['path'] ) );

			if (
				! $screen ||
				( ! in_array( $screen->id, array( 'dashboard', 'plugins', 'plugins-network' ), true ) && ! $is_woo_payments_page )
			) {
				return;
			}

			wp_register_script(
				'ai-builder-woopayments',
				AI_BUILDER_URL . 'inc/assets/js/woopayments.js',
				array( 'jquery' ),
				AI_BUILDER_VER,
				true
			);

			wp_localize_script(
				'ai-builder-woopayments',
				'aiBuilderWooPayments',
				array(
					'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
					'ajaxNonce' => wp_create_nonce( 'woopayments_nonce' ),
				)
			);

			wp_enqueue_script( 'ai-builder-woopayments' );
		}

		/**
		 * Hide CPSW Stripe Connect Notice.
		 *
		 * @since 1.2.37
		 * @param array<string, mixed> $args Notice arguments.
		 * @return array<string, mixed>|null Modified notice arguments or null to hide.
		 */
		public function hide_cpsw_stripe_connect_notice( array $args ) {
			if (
				'connect_stripe_notice' === $args['id'] &&
				$this->can_show_payment_notice( false )
			) {
				return null; // Hide the notice.
			}
			return $args;
		}

		/**
		 * Dismiss WooCommerce Payments notice.
		 *
		 * @since 1.2.37
		 * @return void
		 */
		public function dismiss_woopayments_notice() {
			// Verify nonce.
			if ( ! isset( $_POST['_security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_security'] ) ), 'woopayments_nonce' ) ) {
				wp_send_json_error( 'Invalid nonce' );
			}

			// Get notice ID.
			$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( wp_unslash( $_POST['notice_id'] ) ) : '';

			if ( empty( $notice_id ) ) {
				wp_send_json_error( 'Notice ID is required' );
			}

			// Update option to dismiss notice.
			delete_option( 'astra_sites_show_woopayments_notice' );

			wp_send_json_success( 'Notice dismissed successfully' );
		}

		/**
		 * Returns true/false that need screen check required to retrieve the swatch types.
		 *
		 * @since 1.2.37
		 * @param bool $enable_screen_check screen check requried flag.
		 * @return bool
		 */
		public function can_show_payment_notice( $enable_screen_check = true ) {
			// Basic permission and plugin checks.
			if (
					! current_user_can( 'manage_options' ) ||
					! is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ||
					'yes' !== get_option( 'astra_sites_import_complete', 'no' ) ||
					! get_option( 'astra_sites_show_woopayments_notice' )
				) {
					return false;
			}

			// Skip screen check if disabled.
			if ( ! $enable_screen_check ) {
				return true;
			}

			// Screen check.
			$screen = get_current_screen();
			return $screen && in_array( $screen->id, array( 'dashboard', 'plugins', 'plugins-network' ), true );
		}

		/**
		 * Get Stripe Connect URL.
		 *
		 * @since 1.2.37
		 * @return string
		 */
		public function get_stripe_connect_url() {
			return admin_url(
				add_query_arg(
					array(
						'page'            => 'wc-admin',
						'path'            => '/payments/connect',
						'woopayments-ref' => self::get_woopayments_ref_id(),
					),
					'admin.php'
				)
			);
		}

		/**
		 * Disable redirect after installing and activating WooCommerce Payments.
		 *
		 * @param string               $plugin_init Plugin init file used for activation.
		 * @param array<string, mixed> $data        Data.
		 * @return void
		 */
		public function woo_payments_activate( $plugin_init, $data = array() ) {
			if ( 'woocommerce-payments/woocommerce-payments.php' === $plugin_init ) {
				// Prevent showing the banner if plugin was already active.
				if ( isset( $data['was_plugin_active'] ) && $data['was_plugin_active'] ) {
					return;
				}

				delete_option( 'wcpay_should_redirect_to_onboarding' );
				update_option( 'astra_sites_show_woopayments_notice', true );
			}
		}

		/**
		 * Display admin notice for WooCommerce Payments.
		 *
		 * @since 1.2.37
		 * @return void
		 */
		public function woo_payments_admin_notice() {
			if ( ! $this->can_show_payment_notice() ) {
				return;
			}

			$image_path = esc_url( AI_BUILDER_URL . 'inc/assets/images/payments-connect.png' );

			$output = sprintf(
				'
					<div class="woop-notice-content">
						<div class="woop-notice-heading">
							%2$s
						</div>
						<div class="woop-notice-description">
							%3$s
						</div>
						<div class="woop-notice-action-container">
							<a href="%4$s" class="woop-notice-btn" data-source="banner">
							%5$s
							</a>
						</div>
					</div>
					<div class="woop-notice-image" style="display: flex;">
						<img src="%1$s" class="notice-image" alt="Connect to Stripe" itemprop="logo">
					</div>
				',
				$image_path,
				esc_html__( 'You\'re Almost There! Connect Your WooPayments Account to Start Accepting Payments.', 'astra-sites' ),
				sprintf(
					/* translators: %1$s: Opening strong tag, %2$s: WooPayments for WooCommerce text, %3$s: Closing strong tag */
					__( 'Thank you for installing %1$s %2$s %3$s Let\'s quickly set up WooPayments so you can start accepting payments on your site.', 'astra-sites' ),
					'<strong>',
					__( 'WooPayments for WooCommerce!', 'astra-sites' ),
					'</strong>'
				),
				esc_url( $this->get_stripe_connect_url() ),
				esc_html__( 'Connect WooPayments', 'astra-sites' )
			);
			?>
			<style>
				#connect_woop_notice.notice-info.mega-notice.woop-dismissible-notice {
					display: flex;
					border: 1px solid #e6daf9;
					gap: 10%;
					align-items: center;
					padding: 34px !important;
					background: linear-gradient(90deg, #fff 23.33%, #F2EAFF80 47.42%);
					box-shadow: 0 2px 8px -2px #00000014;
				}
				#connect_woop_notice .woop-notice-content {
					font-size: 16px;
					font-weight: 400;
					line-height: 26px;
					text-align: left;
					margin-left: 0;
					display: flex;
					flex-direction: column;
					gap: 15px;
					width: 65%;
				}
				#connect_woop_notice .woop-notice-heading {
					font-size: 20px;
					font-weight: 600;
					line-height: 32px;
					text-align: left;
					margin: 0;
				}
				#connect_woop_notice .woop-notice-action-container {
					display: flex;
					align-items: center;
					column-gap: 20px;
					margin-top: 10px;
				}
				#connect_woop_notice .woop-notice-action-container a {
					background: #873EFF;
					box-shadow: 0 1px 2px 0 #0000000d;
					color: #fff !important;
					border-color: #873EFF;
					padding: 14px 16px 14px 16px;
					border-radius: 6px;
					font-size: 16px;
					font-weight: 500;
					line-height: 16px;
					text-align: center;
					text-decoration: none;
					transition: 0.2s ease;
				}
				#connect_woop_notice .woop-notice-action-container a:hover {
					background-color: #5f2db1;
				}
				#connect_woop_notice .woop-notice-image{
					width: 35%;
					display: flex;
					justify-content: center;
					align-items: center;
				}
				#connect_woop_notice .woop-notice-image img{
					width: 56%;
				}
			</style>
			<div id="connect_woop_notice" class="notice-info mega-notice woop-dismissible-notice is-dismissible woop-notice woop-custom-notice notice">
				<?php echo wp_kses_post( $output ); ?>
			</div>
			<?php
		}

		/**
		 * Maybe append WooCommerce Payments reference.
		 *
		 * @since 1.2.37
		 * @return void
		 */
		public function maybe_append_woopayments_ref() {
			// Check if the WooCommerce Payments reference option is not set.
			if ( ! class_exists( 'Astra_Sites_Page' ) || ! Astra_Sites_Page::get_instance()->get_setting( 'woopayments_ref' ) ) {
				return;
			}

			// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only GET used safely here.
			if (
				is_admin() &&
				isset( $_GET['page'], $_GET['path'] ) &&
				'wc-admin' === sanitize_text_field( $_GET['page'] ) &&
				'/payments/connect' === sanitize_text_field( rawurldecode( $_GET['path'] ) ) &&
				! isset( $_GET['woopayments-ref'] )
			) {
				// phpcs:enable WordPress.Security.NonceVerification.Recommended -- Read-only GET used safely here.
				$ref_value = self::get_woopayments_ref_id();

				// Preserve all existing query vars.
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Query string is being modified safely.
				$query_args                    = $_GET;
				$query_args['woopayments-ref'] = $ref_value;

				// Build the URL with updated args.
				$new_url = admin_url( 'admin.php' ) . '?' . http_build_query( $query_args );

				// Perform safe redirect and exit.
				wp_safe_redirect( esc_url_raw( $new_url ) );
				exit;
			}
		}
	}

	/**
	 * Kicking this off by calling 'instance()' method
	 */
	Ai_Builder_Compatibility_WooCommerce_Payments::instance();

}
