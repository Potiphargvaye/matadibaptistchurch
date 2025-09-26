<?php

namespace Better_Payment\Lite\Admin;

use Better_Payment\Lite\Classes\Plugin_Usage_Tracker;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

/**
 * Setup Wizard
 *
 * @since 0.0.2
 */
class Setup_Wizard
{
    use TraitsHelper;

    protected $file_version;

    /**
     * Setup Wizard constructor.
     *
     * @since 0.0.2
     */
    public function __construct()
    {
		$this->file_version = defined('WP_DEBUG') && WP_DEBUG ? time() : BETTER_PAYMENT_VERSION;
        add_action('admin_enqueue_scripts', array($this, 'setup_wizard_scripts'));
        add_action('admin_menu', array($this, 'admin_menu'));
		add_action('wp_ajax_save_setup_wizard_data', [$this, 'save_setup_wizard_data']);
        add_action('in_admin_header', [$this, 'remove_notice'], 1000);
    }

    /**
     * Remove all notice in setup wizard page
     * 
     * @since 0.0.2
     */
    public function remove_notice()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'better-payment-setup-wizard') {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }

    /**
     * setup_wizard_scripts
     * @param $hook
     * @return array
     * 
     * @since 0.0.2
     */
    public function setup_wizard_scripts($hook)
    {
        if (isset($hook) && $hook == 'admin_page_better-payment-setup-wizard') {
            //Styles
            wp_register_style('jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css');
            wp_enqueue_style('jquery-ui');

            wp_enqueue_style('bp-setup-wizard-style', BETTER_PAYMENT_ASSETS . '/css/setup-wizard.min.css', null, $this->file_version);
            wp_enqueue_style('bp-settings-style', BETTER_PAYMENT_ASSETS . '/css/style.min.css', null, $this->file_version);
		    wp_enqueue_style('bp-icon-admin', BETTER_PAYMENT_ASSETS . '/icon/style.min.css');
            wp_enqueue_style('sweetalert2-css', BETTER_PAYMENT_ASSETS . '/vendor/sweetalert2/css/sweetalert2.min.css');

            //Scripts
            wp_enqueue_script('bp-setup-wizard-js', BETTER_PAYMENT_ASSETS . '/js/setup-wizard.min.js', null, $this->file_version);
            
            wp_localize_script('bp-setup-wizard-js', 'betterPaymentObjWizard', array(
                'nonce'  => wp_create_nonce('better_payment_admin_nonce'),
                'success_image' => BETTER_PAYMENT_ASSETS . '/img/success.gif',
                'alerts' => [
                    'confirm' => esc_html__('Are you sure?', 'better-payment'),
                    'confirm_description' => esc_html__("You won't be able to revert this!", 'better-payment'),
                    'yes' => esc_html__('Yes, delete it!', 'better-payment'),
                    'no' => esc_html__('No, cancel!', 'better-payment'),
                ],
                'messages' => [
                    'success' => esc_html__('Changes saved successfully!', 'better-payment'),
                    'error' => esc_html__('Opps! something went wrong!', 'better-payment'),
                    'no_action_taken' => esc_html__('No action taken!', 'better-payment'),
                ]
            ));

            wp_enqueue_script('bp-setup-wizard-localize');

            wp_enqueue_script('sweetalert2-js', BETTER_PAYMENT_ASSETS . '/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery'), BETTER_PAYMENT_VERSION, true);
        }
        return [];
    }

    /**
     * Create admin menu for setup wizard
     * 
     * @since 0.0.2
     */
    public function admin_menu()
    {

        add_submenu_page(
            '',
            esc_html__('Better Payment ', 'better-payment'),
            esc_html__('Better Payment ', 'better-payment'),
            'manage_options',
            'better-payment-setup-wizard',
            [$this, 'render_wizard']
        );
    }

    /**
     * Render tab step
     * 
     * @since 0.0.2
     */
    public function tab_step()
    {
        $wizard_column = 'four';
    ?>
        <ul class="better-payment-setup-wizard <?php echo esc_attr( $wizard_column ); ?>" data-step="0">
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <g>
                            <path class="st0" d="M50,25c0-1.9-1.3-3.8-3-4.4c-1.6-0.6-3.2-2-3.7-3.1c-0.5-1.1-0.3-3.3,0.4-4.9c0.8-1.6,0.3-3.9-1-5.2
                                            c-1.3-1.3-3.7-1.8-5.2-1c-1.6,0.8-3.7,0.9-4.9,0.4C31.5,6.2,30,4.6,29.4,3c-0.6-1.7-2.6-3-4.4-3c-1.9,0-3.8,1.3-4.4,3
                                            c-0.6,1.7-2,3.3-3.1,3.7c-1.1,0.5-3.3,0.3-4.9-0.4C11,5.5,8.6,6,7.3,7.3C6,8.6,5.5,11,6.3,12.6c0.8,1.6,0.9,3.7,0.4,4.9
                                            C6.2,18.6,4.6,20,3,20.6c-1.7,0.6-3,2.6-3,4.4c0,1.9,1.3,3.8,3,4.4c1.7,0.6,3.2,2,3.7,3.1c0.5,1.1,0.3,3.3-0.4,4.9
                                            c-0.8,1.6-0.3,3.9,1,5.2c1.3,1.3,3.7,1.8,5.2,1c1.6-0.8,3.7-0.9,4.9-0.4c1.1,0.5,2.6,2.1,3.1,3.7c0.6,1.7,2.6,3,4.4,3
                                            c1.9,0,3.8-1.3,4.4-3c0.6-1.6,2-3.3,3.1-3.7c1.1-0.5,3.3-0.3,4.9,0.4c1.6,0.8,3.9,0.3,5.2-1c1.3-1.3,1.8-3.7,1-5.2
                                            c-0.8-1.6-0.9-3.7-0.4-4.9c0.5-1.1,2.1-2.6,3.7-3.1C48.7,28.8,50,26.9,50,25L50,25z M25,34.2c-5.1,0-9.2-4.1-9.2-9.2
                                            c0-5.1,4.1-9.2,9.2-9.2c5.1,0,9.2,4.1,9.2,9.2C34.2,30.1,30.1,34.2,25,34.2L25,34.2z M25,34.2" />
                        </g>
                    </svg>
                </div>
                <div class="name"><?php esc_html_e('Getting Started', 'better-payment'); ?></div>
            </li>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <path class="st0" d="M48.9,16.6c-0.7-0.7-1.6-1.1-2.6-1.1c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8L24.7,15.1l8.8-8.8c0.7-0.7,1.1-1.6,1.1-2.6
c0-1-0.4-1.9-1.1-2.6C32.7,0.4,31.8,0,30.8,0c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8l-5.6-5.6c-0.3-0.3-0.6-0.4-1-0.4c-0.4,0-0.7,0.2-1,0.4
c-8,8.8-3.7,28-3.2,29.9l-8.1,8.1c-0.3,0.2-0.4,0.6-0.4,0.9c0,0.4,0.1,0.7,0.4,0.9L5.5,49c0.3,0.3,0.6,0.4,0.9,0.4
c0.3,0,0.7-0.1,0.9-0.4l8.1-8.1c3.9,1,8.8,1.6,13.2,1.6c5.2,0,12.3-0.8,16.7-4.8c0.3-0.2,0.4-0.6,0.4-1c0-0.4-0.1-0.7-0.4-1
l-5.2-5.2l8.8-8.8C50.4,20.4,50.4,18,48.9,16.6z" />
                    </svg>
                </div>
                <div class="name"><?php esc_html_e('PayPal Configuration', 'better-payment'); ?></div>
            </li>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <path class="st0" d="M48.9,16.6c-0.7-0.7-1.6-1.1-2.6-1.1c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8L24.7,15.1l8.8-8.8c0.7-0.7,1.1-1.6,1.1-2.6
c0-1-0.4-1.9-1.1-2.6C32.7,0.4,31.8,0,30.8,0c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8l-5.6-5.6c-0.3-0.3-0.6-0.4-1-0.4c-0.4,0-0.7,0.2-1,0.4
c-8,8.8-3.7,28-3.2,29.9l-8.1,8.1c-0.3,0.2-0.4,0.6-0.4,0.9c0,0.4,0.1,0.7,0.4,0.9L5.5,49c0.3,0.3,0.6,0.4,0.9,0.4
c0.3,0,0.7-0.1,0.9-0.4l8.1-8.1c3.9,1,8.8,1.6,13.2,1.6c5.2,0,12.3-0.8,16.7-4.8c0.3-0.2,0.4-0.6,0.4-1c0-0.4-0.1-0.7-0.4-1
l-5.2-5.2l8.8-8.8C50.4,20.4,50.4,18,48.9,16.6z" />
                    </svg>
                </div>
                <div class="name"><?php esc_html_e('Stripe Configuration', 'better-payment'); ?></div>
            </li>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <path class="st0" d="M48.5,3.1l-0.3-0.3c-0.9-0.9-2.1-1.4-3.3-1.3c-1.2,0-2.4,0.6-3.3,1.5L16.1,30.9l-0.5,0.2l-0.5-0.2l-6.3-7.4
c-0.9-1.1-2.2-1.7-3.6-1.8c-1.4-0.1-2.8,0.5-3.8,1.5c-1.6,1.6-1.8,4.1-0.5,5.9l13.1,18.3c0.7,1,1.9,1.7,3.2,1.7h1.1
c2.2,0,4.2-1.1,5.4-2.8L49.1,9.5C50.5,7.5,50.2,4.8,48.5,3.1z" />
                    </svg>
                </div>
                <div class="name"><?php esc_html_e('Finalize', 'better-payment'); ?></div>
            </li>
        </ul>
    <?php
    }

    /**
     * Tav view content
     * 
     * @since 0.0.2
     */
    public function tab_content($bp_admin_saved_settings)
    {
        $bp_admin_saved_settings = DB::get_settings();
        $currency_list = $this->get_currency_list();
    ?>
        <div class="better-payment-setup-body better-payment">
            <form method="post" id="better-payment-admin-settings-form" class="better-payment-setup-wizard-form" action="#">

                <div id="configuration" class="setup-content active">
                    <div id="general" class="sidebar__tab__content show payment__options better-payment-box">
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('PayPal', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Enable PayPal if you want to make transaction using PayPal.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <label class="bp-switch">
                                    <input type="hidden" name="better_payment_settings_general_general_paypal" value="no">
                                    <input type="checkbox" name="better_payment_settings_general_general_paypal" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_paypal']) && $bp_admin_saved_settings['better_payment_settings_general_general_paypal'] == 'yes' ? ' checked' : '' ?>>
                                    <span class="switch__btn"></span>
                                </label>
                            </div>
                        </div>
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Stripe', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Enable Stripe if you want to accept payment via Stripe.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <label class="bp-switch">
                                    <input type="hidden" name="better_payment_settings_general_general_stripe" value="no">
                                    <input type="checkbox" name="better_payment_settings_general_general_stripe" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_stripe']) && $bp_admin_saved_settings['better_payment_settings_general_general_stripe'] == 'yes' ? ' checked' : '' ?>>
                                    <span class="switch__btn"></span>
                                </label>
                            </div>
                        </div>
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Email Notification', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Enable email notification for each transaction. It sends notification to the website admin and customer (who makes the payment). You can modify email settings as per your need.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <label class="bp-switch">
                                    <input type="hidden" name="better_payment_settings_general_general_email" value="no">
                                    <input type="checkbox" name="better_payment_settings_general_general_email" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_email']) && $bp_admin_saved_settings['better_payment_settings_general_general_email'] == 'yes' ? ' checked' : '' ?>>
                                    <span class="switch__btn"></span>
                                </label>
                            </div>
                        </div>
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Currency', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Select default currency for each transaction. You can also overwrite this setting from each widget control on elementor page builder.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <div class="bp-select">
                                    <select name="better_payment_settings_general_general_currency">
                                        <option value="" disabled> <?php esc_html_e('Select Currency', 'better-payment'); ?> </option>
                                        <?php foreach ($currency_list as $key => $value){
                                            $selected = isset($bp_admin_saved_settings['better_payment_settings_general_general_currency']) && $bp_admin_saved_settings['better_payment_settings_general_general_currency'] == $key?'selected':'';
                                            printf( '<option value="%s" %s >%s</option>', esc_attr( $key ), esc_attr( $selected ), esc_html__( $key, 'better-payment' ) );
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="quick-setup-paypal" class="setup-content quick-setup-paypal better-payment-box">
                    <div class="payment__options ">
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Live Mode', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Live mode allows you to process real transactions. It just requires PayPal business email to accept real payments.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <label class="bp-switch">
                                    <input type="hidden" name="better_payment_settings_payment_paypal_live_mode" value="no">
                                    <input type="checkbox" name="better_payment_settings_payment_paypal_live_mode" value="yes" data-target="better-payment-settings-payment-paypal-live" <?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode'] == 'yes' ? ' checked' : '' ?>>
                                    <span class="switch__btn"></span>
                                </label>
                            </div>
                        </div>
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Business Email', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Your PayPal account email address to accept payment via PayPal.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status input__wrap">
                                <input type="email" class="form__control" name="better_payment_settings_payment_paypal_email" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_email']) ? esc_html( $bp_admin_saved_settings['better_payment_settings_payment_paypal_email'] ) : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="quick-setup-stripe" class="setup-content quick-setup-stripe">
                    <div class="payment__options">
                        <div class="payment__option">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Live Mode', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Live mode allows you to process real transactions. It just requires live Stripe keys (public and secret keys) to accept real payments.', 'better-payment'); ?></p>
                            </div>
                            <div class="active__status">
                                <label class="bp-switch">
                                    <input type="hidden" name="better_payment_settings_payment_stripe_live_mode" value="no">
                                    <input type="checkbox" name="better_payment_settings_payment_stripe_live_mode" value="yes" data-targettest="bp-stripe-test-key" data-targetlive="bp-stripe-live-key" <?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode'] == 'yes' ? ' checked' : '' ?>>
                                    <span class="switch__btn"></span>
                                </label>
                            </div>
                        </div>

                        <?php $better_payment_settings_payment_stripe_live = isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode'] == 'yes'; ?>

                        <div class="payment__option bp-stripe-key bp-stripe-live-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-block' : 'bp-d-none' ?>">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Live Public Key', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Stripe live public key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a>.</p>
                            </div>
                            <div class="active__status input__wrap">
                                <input type="text" class="form__control mt15" name="better_payment_settings_payment_stripe_live_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_public'] ) : '' ?>" type="text" placeholder="<?php //esc_html_e('Live public key', 'better-payment'); ?>">
                            </div>
                        </div>
                        <div class="payment__option bp-stripe-key bp-stripe-live-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-block' : 'bp-d-none' ?>">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Live Secret Key', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Stripe live secret key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a>.</p>
                            </div>
                            <div class="active__status input__wrap">
                                <input type="password" class="form__control mt15" name="better_payment_settings_payment_stripe_live_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_secret'] ) : '' ?>">
                            </div>
                        </div>
                        <div class="payment__option bp-stripe-key bp-stripe-test-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Test Public Key', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Stripe test public key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a>.</p>
                            </div>
                            <div class="active__status input__wrap">
                                <input type="text" class="form__control mt15" name="better_payment_settings_payment_stripe_test_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_test_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_test_public'] ) : '' ?>">
                            </div>
                        </div>
                        <div class="payment__option bp-stripe-key bp-stripe-test-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                            <div class="payment__option__content">
                                <h4><?php esc_html_e('Test Secret Key', 'better-payment'); ?></h4>
                                <p><?php esc_html_e('Stripe test secret key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a>.</p>
                            </div>
                            <div class="active__status input__wrap">
                                <input type="password" class="form__control mt15" name="better_payment_settings_payment_stripe_test_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_test_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_test_secret'] ) : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="finalize" class="setup-content better-payment-box">
                    <header class="pb31 text-center">
                        <div class="bp-container">
                            <div class="bp-row">
                                <div class="bp-col">
                                    <div class="logo">
                                        <a href="javascript:void(0)"><img src="<?php echo esc_url( BETTER_PAYMENT_ASSETS . '/img/logo.svg' ); ?>" alt="Better Payment logo"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <h2 class="text-center"><?php esc_html_e('Great Job! Your Configuration is Complete ', 'better-payment'); ?>🎉</h2>
                    <p> <input class="better_payment_settings_opt_in" type="checkbox" name="better_payment_settings_opt_in" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_opt_in']) && $bp_admin_saved_settings['better_payment_settings_opt_in'] == 'yes' ? ' checked' : '' ?>>
                        <label for="better_payment_settings_opt_in"><?php esc_html_e('Share non-sensitive diagnostic data and plugin usage information.', 'better-payment'); ?></label>
                    </p>
                    <p><?php esc_html_e('What do we collect? We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.', 'better-payment'); ?></p>
                </div>
            </form>
        </div>
    <?php
    }

    /**
     * Footer content
     * 
     * @since 0.0.1
     */
    public function setup_wizard_footer()
    {
    ?>
        <div class="better-payment better-payment-setup-footer">
            <button id="better-payment-prev" class="button button__active better-payment-admin-settings-button"><?php esc_html_e('< Previous', 'better-payment') ?></button>
            <button id="better-payment-next" class="button button__active better-payment-admin-settings-button"><?php esc_html_e('Next >', 'better-payment') ?></button>
            <button id="better-payment-save" style="display: none" class="button button__active better-payment-admin-settings-button better-payment-setup-wizard-save"><?php esc_html_e('Finish', 'better-payment') ?></button>
        </div>
    <?php
    }

    /**
     * render_wizard
     * 
     * @since 0.0.1
     */
    public function render_wizard()
    {
    ?>
        <div class="better-payment-setup-wizard-wrap">
            <?php
    		$bp_admin_saved_settings = DB::get_settings();
            
            $this->change_site_title();
            $this->tab_step();
            $this->tab_content($bp_admin_saved_settings);
            $this->setup_wizard_footer();
            ?>
        </div>
    <?php
    }

    /**
     * Save setup wizard data
     * 
     * @since 0.0.1
     */
    public static function save_setup_wizard_data()
	{
		/**
		 * Verify the Nonce
		 */
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'better_payment_admin_nonce')) {
			return;
		}

        if (!current_user_can('manage_options')) {
            return;
		}
		
        try{
            Settings::save_default_settings(1);
            Settings::save_settings($_POST['form_data']);
            
            $posted_field_opt_in_val = '';
            foreach ($_POST['form_data'] as $posted_field) {
				 $posted_field_name = sanitize_key($posted_field['name']); 
				 $posted_field_value = sanitize_key($posted_field['value']);
                 
                 if('better_payment_settings_opt_in' === $posted_field_name){
                    $posted_field_opt_in_val = $posted_field_value;
                    break;
                 } 
			}

            if ( 'yes' === $posted_field_opt_in_val ) {
                self::wpins_process();
            }

            update_option( 'better_payment_setup_wizard', 'complete' );

            if (!did_action('elementor/loaded')) {
                wp_send_json_success( [ 'redirect_url' => admin_url() ] );
            }else {
                wp_send_json_success( [ 'redirect_url' => admin_url( 'admin.php?page=better-payment-settings' ) ] );
            }
        }catch (\Exception $e) {
            wp_send_json_error($e->getMessage());            
        }
        
		die;
	}

    /**
     * WPIns process
     * 
     * @since 0.0.1
     */
    public static function wpins_process(){
        $plugin_name = basename( BETTER_PAYMENT_FILE, '.php' );

        if ( class_exists( '\Better_Payment\Lite\Classes\Plugin_Usage_Tracker' ) ){
            $tracker = Plugin_Usage_Tracker::get_instance( BETTER_PAYMENT_FILE, [
                'opt_in'       => true,
                'goodbye_form' => true,
                'item_id'      => '64e1f724b5e14edb343e'
            ] );
            $tracker->set_is_tracking_allowed( true );
            $tracker->do_tracking( true );
        }
    }

    /**
     * Redirect
     * 
     * @since 0.0.1
     */
    public static function redirect()
    {
        update_option('better_payment_setup_wizard', 'init');
        wp_redirect(admin_url('admin.php?page=better-payment-setup-wizard'));
    }

    /**
     * Site title
     * 
     * @since 0.0.1
     */
    public function change_site_title()
    {
    ?>
        <script>
            document.title = "<?php esc_html_e('Quick Setup Wizard - Better Payment', 'better-payment'); ?>"
        </script>
    <?php
    }
}