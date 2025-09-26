<?php

namespace Better_Payment\Lite;

use Better_Payment\Lite\Admin\Settings;
use Better_Payment\Lite\Admin\Elementor\Widget;
use Better_Payment\Lite\Admin\Menu;
use Better_Payment\Lite\Classes\Helper;
use Better_Payment\Lite\Classes\Export;
use Better_Payment\Lite\Classes\Import;
use PriyoMukul\WPNotice\Notices;
use PriyoMukul\WPNotice\Utils\CacheBank;
use PriyoMukul\WPNotice\Utils\NoticeRemover;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The admin class
 *
 * @since 0.0.1
 */
class Admin extends Controller{

    // Check if pro is enabled
    protected $pro_enabled;

    private static $cache_bank = null;

    /**
     * Initialize the class
     *
     * @since 0.0.1
     */
    public function __construct() {

    }

    public function init(){
        $this->pro_enabled = apply_filters('better_payment/pro_enabled', false);

        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            new Settings();
        }

        if ( ! did_action('elementor/loaded') ) {
            $notice = new Helper();
            add_action( 'admin_notices', array( $notice, 'elementor_not_loaded' ) );
        }

        if (did_action('elementor/loaded')) {
            add_filter('plugin_action_links', array($this, 'plugin_actions_links'), 10, 2);
        }

        if ( ! $this->pro_enabled ) {
            $this->admin_notice();
		    $this->start_plugin_tracking();
        }

        add_action('in_admin_header', array( $this, 'hide_admin_notices' ));
    }

    /**
     * Add settings page link on plugins page
     *
     * @param array $links
     * @param string $file
     *
     * @return array
     * @since 0.0.1
     */
    public function plugin_actions_links($links, $file) {
        $better_payment_plugin = plugin_basename(BETTER_PAYMENT_FILE);

        if ($file == $better_payment_plugin && current_user_can('manage_options')) {
            $links[] = sprintf('<a href="%s">%s</a>', admin_url("admin.php?page=better-payment-settings"), __('Settings', 'better-payment'));

            if ( ! $this->pro_enabled ) {
                $links[] = sprintf('<a href="https://wpdeveloper.com/in/upgrade-better-payment-pro" target="_blank" style="color: #7561F8; font-weight: bold;">' . __('Go Pro', 'better-payment') . '</a>');
            }
        }

        return $links;
    }

    public function admin_notice() {
        require_once BETTER_PAYMENT_PATH . '/vendor/autoload.php';

        self::$cache_bank = CacheBank::get_instance();

        NoticeRemover::get_instance('1.0.0');
        NoticeRemover::get_instance('1.0.0', '\WPDeveloper\BetterDocs\Dependencies\PriyoMukul\WPNotice\Notices');

        $notices = new Notices( [
			'id'             => 'better-payment',
			'storage_key'    => 'notices',
			'lifetime'       => 3,
			'stylesheet_url' => esc_url_raw( BETTER_PAYMENT_URL . '/assets/css/admin.min.css' ),
			'styles' => esc_url_raw( BETTER_PAYMENT_URL . '/assets/css/admin.min.css' ),
			'priority'       => 9
		] );

        $review_notice = 'We hope you\'re enjoying Better Payment! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?';
		$_review_notice = [
			'thumbnail' => plugins_url( 'assets/img/logo.svg', BETTER_PAYMENT_BASENAME ),
			'html'      => '<p>' . $review_notice . '</p>',
			'links'     => [
				'later'            => array(
					'link'       => '//wordpress.org/support/plugin/better-payment/reviews/#new-post',
					'target'     => '_blank',
					'label'      => 'Ok, you deserve it!',
					'icon_class' => 'dashicons dashicons-external',
                    'attributes' => [
						'target'     => '_blank',
					],
				),
				'allready'         => array(
					'label'      => 'I already did',
					'icon_class' => 'dashicons dashicons-smiley',
					'attributes' => [
						'data-dismiss' => true
					],
				),
				'maybe_later'      => array(
					'label'      => 'Maybe Later',
					'icon_class' => 'dashicons dashicons-calendar-alt',
					'attributes' => [
						'data-later' => true
					],
				),
				'support'          => array(
					'link'       => 'https://wpdeveloper.com/support',
					'label'      => 'I need help',
					'icon_class' => 'dashicons dashicons-sos',
                    'attributes' => [
						'target'     => '_blank',
					],
				),
				'never_show_again' => array(
					'label'      => 'Never show again',
					'icon_class' => 'dashicons dashicons-dismiss',
					'attributes' => [
						'data-dismiss' => true
					],
				)
			]
		];

	    $notices->add(
			'review',
			$_review_notice,
			[
				'start'       => $notices->strtotime( '+7 day' ),
				'recurrence'  => 30,
				'refresh'     => BETTER_PAYMENT_VERSION,
				'dismissible' => true,
			]
		);

        /**
		 * Halloween Notice
		 */
        // $crown_icon = BETTER_PAYMENT_URL . '/assets/img/crown.svg';
		// $halloween_notice_message = "<p>🎃 Manage Payment forms better with Better Payment PRO and grab a <strong>flat 25% OFF</strong> this Halloween.</p><a style='display: inline-flex;column-gap:5px;' class='button button-primary' href='https://betterpayment.co/#pricing-plan' target='_blank'><img style='width:15px;' src='{$crown_icon}'/>Upgrade to PRO</a>";

		// $halloween_notice = [
		// 	'thumbnail' => plugins_url( 'assets/img/logo.svg', BETTER_PAYMENT_BASENAME ),
		// 	'html'      => $halloween_notice_message,
		// ];

        // $notices->add(
		// 	'halloween_notice',
		// 	$halloween_notice,
		// 	[
		// 		'start'       => $notices->time(),
		// 		// 'start'       => $notices->strtotime( '+7 day' ),
		// 		// 'recurrence'  => 30,
		// 		'expire'       => $notices->strtotime( '+10 days' ),
		// 		'refresh'     => BETTER_PAYMENT_VERSION,
		// 		'dismissible' => true,
		// 		'screens' => [ 'dashboard' ],
		// 	]
		// );

        /**
		 * Black Friday Notice
		 */
        // $crown_icon = BETTER_PAYMENT_URL . '/assets/img/crown.svg';
		// $black_friday_notice_message = "<p>🛍️ Manage Payment forms better with Better Payment PRO & grab <strong>up to 40% OFF</strong> this Black Friday.</p><a style='display: inline-flex;column-gap:5px;' class='button button-primary' href='https://betterpayment.co/bfcm2024-pricing' target='_blank'><img style='width:15px;' src='{$crown_icon}'/>Upgrade to PRO</a>";

		// $black_friday_notice = [
		// 	'thumbnail' => plugins_url( 'assets/img/logo.svg', BETTER_PAYMENT_BASENAME ),
		// 	'html'      => $black_friday_notice_message,
		// ];

        // $notices->add(
		// 	'black_friday_notice1',
		// 	$black_friday_notice,
		// 	[
		// 		'start'       => $notices->time(),
		// 		// 'start'       => $notices->strtotime( '+7 day' ),
		// 		// 'recurrence'  => 30,
		// 		// 'expire'       => $notices->strtotime( '+10 days' ),
		// 		'expire'       => strtotime( '11:59:59pm 5th December, 2024' ),
		// 		'refresh'     => BETTER_PAYMENT_VERSION,
		// 		'dismissible' => true,
		// 		'screens' => [ 'dashboard' ],
		// 	]
		// );

		/**
		 * Holiday Notice
		 */
		$crown_icon = BETTER_PAYMENT_URL . '/assets/img/crown.svg';
		$holiday_notice_message = "<p>🎁 <strong>SAVE 25% now</strong> to unlock analytics, split payment & more to manage payments in Elementor effortlessly in 2025.</p>
									<div class='bp-notice-action-button'>
										<a style='display: inline-flex;column-gap:5px;' class='button button-primary' href='https://betterpayment.co/holiday24-admin-notice' target='_blank'>
											<img style='width:15px;' src='{$crown_icon}'/>GET PRO Lifetime Access
										</a>
										<a class='bp-notice-action-dismiss dismiss-btn' data-dismiss='true' href='#'>
											<img style='width:15px;' src='{$crown_icon}'/>No, I'll Pay Full Price Later
										</a>
									</div>
									";
		
		$holiday_notice = [
			'thumbnail' => plugins_url( 'assets/img/logo.svg', BETTER_PAYMENT_BASENAME ),
			'html'      => $holiday_notice_message,
		];

        $notices->add(
			'holiday_notice',
			$holiday_notice,
			[
				'start'       => $notices->time(),
				// 'start'       => $notices->strtotime( '+7 day' ),
				// 'expire'       => $notices->strtotime( '+10 days' ),
				'expire'       => strtotime( '11:59:59pm 10th January, 2025' ),
				'refresh'     => BETTER_PAYMENT_VERSION,
				// 'recurrence'  => 30,
				'recurrence'  => false,
				'dismissible' => true,
				'screens' => [ 'dashboard' ],
			]
		);

	    self::$cache_bank->create_account( $notices );
	    self::$cache_bank->calculate_deposits( $notices );
    }

    public function hide_admin_notices(){
        $current_screen = get_current_screen();
    
        if ($current_screen && str_contains( $current_screen->id, 'better-payment' ) ) {
            remove_all_actions('user_admin_notices');
            remove_all_actions('admin_notices');
        }
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     * @since 0.0.1
     */
    public static function dispatch_actions( ) {
        $menuObj = new Menu();
        $menuObj->init();

        $bpElementorWidgetObj = new Widget();

        $bpImportObj = new Import();
        
        $bpExportObj = new Export();

        // Handle select2 ajax search
        add_action('wp_ajax_better_payment_select2_search_post', [$bpElementorWidgetObj, 'select2_ajax_posts_filter_autocomplete']);

        add_action('wp_ajax_better_payment_select2_get_title', [$bpElementorWidgetObj, 'select2_ajax_get_posts_value_titles']);

        // Elements
        add_action('elementor/controls/controls_registered',[$bpElementorWidgetObj, 'register_controls']);

        //Page: Transactions
        add_action('admin_post_better-payment-transactions-import',[$bpImportObj, 'import_transactions']);
        add_action('wp_ajax_better-payment-transactions-export',[$bpExportObj, 'export_transactions']);
    }
}
