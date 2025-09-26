<?php

namespace Better_Payment\Lite\Admin\Elementor;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Better_Payment\Lite\Classes\Helper as ClassesHelper;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;
use \Elementor\Controls_Manager as Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use \Elementor\Repeater;
use Elementor\Utils;
use \Elementor\Widget_Base as Widget_Base;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The elementor widget class
 *
 * @since 0.0.1
 */
class Better_Payment_Widget extends Widget_Base {

    use TraitsHelper;

    private $better_payment_global_settings = [];

    /**
	 * @var mixed|void
	 */
	protected $pro_enabled;

    /**
	 * Login_Register constructor.
	 * Initializing the Login_Register widget class.
	 * @inheritDoc
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->pro_enabled       = apply_filters( 'better_payment/pro_enabled', false );

	}

    public function get_name() {
        return 'better-payment';
    }

    public function get_title() {
        return esc_html__( 'Better Payment', 'better-payment' );
    }

    public function get_icon() {
        return 'bp-icon bp-logo';
    }

    public function get_keywords() {
        return [
            'payment', 'better-payment' ,'paypal', 'stripe', 'sell', 'donate', 'transaction', 'online-transaction', 'paystack'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://betterpayment.co/docs/';
    }

    public function get_style_depends() { 
        return apply_filters( 'better_payment/elementor/editor/get_style_depends', [ 'better-payment-el', 'bp-icon-front', 'better-payment-style', 'better-payment-common-style', 'better-payment-admin-style' ] );
    }
    
    public function get_script_depends() {
        return apply_filters( 'better_payment/elementor/editor/get_script_depends', [ 'better-payment-common-script', 'better-payment' ] );
    }

    protected function register_controls() {
        $this->better_payment_global_settings = DB::get_settings();

        $is_edit_mode = Plugin::instance()->editor->is_edit_mode();

        if ( $is_edit_mode && ( ! current_user_can('manage_options') ) ) {
            $this->better_payment_global_settings = [];
        }

        $this->start_controls_section(
            'better_payment_form_setting',
            [
                'label' => esc_html__( 'Payment Settings', 'better-payment' ),
            ]
        );
        
        do_action('better_payment/elementor/editor/layouts_payment_settings_section', $this);
        
        $this->add_control(
            'better_payment_form_layout',
            [
                'label'      => esc_html__( 'Form Layout', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'layout-1',
                'options'    => $this->better_payment_free_layouts(),
            ]
        );

        $this->add_control(
            'better_payment_form_payment_type',
            [
                'label'      => esc_html__( 'Payment Type', 'better-payment' ),
                'description' => esc_html__( 'Recurring and Split Payment is available for Stripe only at the moment!', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'one-time',
                'options'    => [
                    'one-time' => 'One Time',
                    'recurring' => 'Recurring',
                    'split-payment' => 'Split Payment',
                ],
                'condition'   => [
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_recurring_price_id',
            [
                'label'       => esc_html__( 'Default Price ID', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'description' => sprintf( 
                    __( '<p>Create a product from Stripe dashboard and <a href="%1$s" target="_blank">get the (default) price id.</a></p>', 'better-payment' ), 
                    esc_url('//betterpayment.co/docs/retrieve-price-id-for-recurring-payments/'), 
                ),
                'placeholder' => 'price_G0FvDp6vZvdwRZ',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition'   => [
                    'better_payment_form_payment_type' => [ 'recurring', 'split-payment' ],
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
                'separator' => 'before',
            ]
        );

        $repeater_split_payment = new Repeater();

        $repeater_split_payment->add_control(
            'better_payment_split_installment_name',
            [
                'label' => esc_html__( 'Installment Name', 'better-payment' ),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => 'Installment Name',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        
        $repeater_split_payment->add_control(
            'better_payment_split_installment_price_id',
            [
                'label' => esc_html__( 'Price ID', 'better-payment' ),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => 'price_G0FvDp6vZvdwRZ',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $repeater_split_payment->add_control(
            'better_payment_split_installment_iteration',
            [
                'label' => esc_html__( 'Iterations', 'better-payment' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 1,
                'max'   => 36,
            ]
        );
        
        $this->add_control(
            'better_payment_split_installment_price_ids_note',
            [
                'type'        => Controls_Manager::RAW_HTML,
                'raw' => sprintf( 
                    __( '<p>Now add more prices to the product from Stripe dashboard and <a href="%1$s" target="_blank">get the price id for each installment.</a></p>', 'better-payment' ), 
                    esc_url('//betterpayment.co/docs/configure-split-payment-into-subscriptions/'), 
                ),
                'content_classes' => 'elementor-control-alert elementor-panel-alert elementor-panel-alert-info',
                'ai' => [
                    'active' => false,
                ],
                'condition'   => [
                    'better_payment_form_payment_type' => 'split-payment',
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_split_installment_price_ids',
            [
                'label'       => esc_html__( 'Installments', 'better-payment' ),
                'type'        => Controls_Manager::REPEATER,
                'separator'   => 'after',
                'default'     => [
                    [
                        'better_payment_split_installment_name' => '3 Months',
                        'better_payment_split_installment_price_id' => '',
                        'better_payment_split_installment_iteration' => 3,
                    ],
                    [
                        'better_payment_split_installment_name' => '6 Months',
                        'better_payment_split_installment_price_id' => '',
                        'better_payment_split_installment_iteration' => 6,
                    ],
                    [
                        'better_payment_split_installment_name' => '9 Months',
                        'better_payment_split_installment_price_id' => '',
                        'better_payment_split_installment_iteration' => 9,
                    ],
                    [
                        'better_payment_split_installment_name' => '12 Months',
                        'better_payment_split_installment_price_id' => '',
                        'better_payment_split_installment_iteration' => 12,
                    ],
                ],
                'fields'      => $repeater_split_payment->get_controls(),
                'title_field' => '<i class="{{ better_payment_split_installment_name }}" aria-hidden="true"></i> {{{ better_payment_split_installment_name }}}',
                'condition'   => [
                    'better_payment_form_payment_type' => 'split-payment',
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
            ]
        );

        $repeater_recurring_payment = new Repeater();
 
        $repeater_recurring_payment->add_control(
            'better_payment_recurring_name',
            [
                'label' => esc_html__( 'Interval Name', 'better-payment' ),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => 'Interval Name',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );
         
        $repeater_recurring_payment->add_control(
            'better_payment_recurring_price_id',
            [
                'label' => esc_html__( 'Price ID', 'better-payment' ),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => 'price_G0FvDp6vZvdwRZ',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_recurring_price_ids',
            [
                'label'       => esc_html__( 'Intervals', 'better-payment' ),
                'type'        => Controls_Manager::REPEATER,
                'separator'   => 'after',
                'default'     => [
                    [
                        'better_payment_recurring_name' => 'Monthly',
                        'better_payment_recurring_price_id' => '',
                    ],
                    [
                        'better_payment_recurring_name' => 'Yearly',
                        'better_payment_recurring_price_id' => '',
                    ],
                ],
                'fields'      => $repeater_recurring_payment->get_controls(),
                'title_field' => '<i class="{{ better_payment_recurring_name }}" aria-hidden="true"></i> {{{ better_payment_recurring_name }}}',
                'condition'   => [
                    'better_payment_form_payment_type' => 'recurring',
                    'better_payment_form_layout' => 'layout-5-pro',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_recurring_webhook_stripe_secret',
            [
                'label'       => esc_html__( 'Webhook Secret', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'description' => sprintf( 
                    __( '<p>Create a webhook endpoint from Stripe dashboard and <a href="%1$s" target="_blank">get the webhook secret.</a></p>', 'better-payment' ), 
                    esc_url('//betterpayment.co/docs/add-webhook-endpoint-recurring-better-payment/'), 
                ),
                'placeholder' => 'whsec_...',
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition'   => [
                    'better_payment_form_payment_type' => [ 'recurring', 'split-payment' ],
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_recurring_webhook_stripe',
            [
                'type'        => Controls_Manager::RAW_HTML,
                'raw' => sprintf( 
                    __( '<p><a href="%1$s" target="_blank">Your webhook endpoint url »</a><br>%2$s</p>', 'better-payment' ), 
                    esc_url('//wpdeveloper.com/docs-category/better-payment/'), 
                    esc_url_raw( get_permalink( get_the_ID() ) . '?webhook-stripe=1' ) 
                ),
                'content_classes' => 'elementor-control-alert elementor-panel-alert elementor-panel-alert-info',
                'ai' => [
                    'active' => false,
                ],
                'condition'   => [
                    'better_payment_form_payment_type' => [ 'recurring', 'split-payment' ],
                    'better_payment_form_layout!' => ['layout-1', 'layout-2', 'layout-3', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_payment_source',
            [
                'label'      => esc_html__( 'Payment Source', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'manual',
                'options'    => [
                    'manual' => 'Manual',
                    'woocommerce' => 'WooCommerce',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control( 'better_payment_form_payment_source_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( __( '<a href="%1$s" target="_blank"><strong>WooCommerce</strong></a> is not installed/activated on your site. Please install and activate <a href="%1$s" target="_blank"><strong>WooCommerce</strong></a> first.', 'better-payment' ), esc_url('plugin-install.php?s=woocommerce&tab=search&type=term') ),
            'content_classes' => 'better-payment-warning',
            'conditions' => [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'better_payment_form_layout',
                        'operator' => '!=',
                        'value' => 'layout-4-pro',
                    ],
                    [
                        'name' => 'better_payment_form_layout',
                        'operator' => '!=',
                        'value' => 'layout-5-pro',
                    ],
                    [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'better_payment_form_payment_source',
                                'value' => 'woocommerce' . ( ! class_exists('woocommerce') ? '' : 'exist' ),
                            ],
                            [
                                'name' => 'better_payment_form_layout',
                                'value' => 'layout-6-pro' . ( ! class_exists('woocommerce') ? '' : 'exist' ),
                            ],
                        ],
                    ],
                ],
            ],
        ] );

        $this->add_control(
            'better_payment_form_woocommerce_product_section',
            [
                'label' => __( 'Product', 'better-payment' ),
                'type'  => Controls_Manager::HIDDEN,
                'condition' => [
                    'better_payment_form_payment_source' => 'woocommerce',
                ],
            ]
        );

        $this->add_control( "better_payment_form_woocommerce_product_id", [
            'label'       => __( 'Choose a Product', 'better-payment' ),
            'description' => __( 'Enter Product IDs separated by a comma', 'better-payment' ),
            'type'        => 'better-payment-select2',
            'label_block' => true,
            'multiple'    => false,
            'source_name' => 'post_type',
            'source_type' => 'product',
            'placeholder' => __( 'Search By', 'better-payment' ),
            'conditions' => $this->payment_source_woo_conditions(),
            'separator' => 'after',
            'dynamic'     => [
                'active' => false,
            ],
        ] );

        $this->add_control('better_payment_form_woocommerce_product_ids', [
            'label' => esc_html__('Select Products', 'better-payment'),
            'type'        => 'better-payment-select2',
            'label_block' => true,
            'multiple' => true,
            'source_name' => 'post_type',
            'source_type' => 'product',
            'condition' => [
                'better_payment_form_layout' => ['layout-6-pro'],
            ],
        ]);

        $this->add_control(
            'better_payment_form_paypal_enable',
            [
                'label'        => __( 'Enable PayPal', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_paypal']), //yes or no
                'separator'    => 'before',
                'condition' => [
                    'better_payment_form_payment_type!' => [ 'recurring', 'split-payment' ],
                ],
            ]
        );

        $this->add_control( 'better_payment_form_paypal_enable_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( __( 'Whoops! It seems like you haven\'t configured <b>PayPal (Business Email) Settings</b>. Make sure to configure these settings before you publish the form.', 'better-payment' ) ),
            'content_classes' => 'better-payment-warning',
            'condition'       => [
                'better_payment_form_paypal_enable' => 'yes',
                'better_payment_paypal_business_email' => '',
            ],
        ] );

        $this->add_control(
            'better_payment_form_stripe_enable',
            [
                'label'        => __( 'Enable Stripe', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_stripe']), //yes or no
            ]
        );

        $this->add_control( 'better_payment_form_stripe_enable_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( __( 'Whoops! It seems like you haven\'t configured <b>Stripe (Public and Secret Key) Settings</b>. Make sure to configure these settings before you publish the form.', 'better-payment' ) ),
            'content_classes' => 'better-payment-warning',
            'conditions' => [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'better_payment_form_stripe_enable',
                        'value' => 'yes',
                    ],
                    [
                        'name' => 'better_payment_stripe_live_mode',
                        'operator' => '!==',
                        'value' => 'yes',
                    ],
                    [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'better_payment_stripe_public_key',
                                'value' => '',
                            ],
                            [
                                'name' => 'better_payment_stripe_secret_key',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ] );

        $this->add_control( 'better_payment_form_stripe_enable_notice_live', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( __( 'Whoops! It seems like you haven\'t configured <b>Stripe (Public and Secret Key) Settings</b>. Make sure to configure these settings before you publish the form.', 'better-payment' ) ),
            'content_classes' => 'better-payment-warning',
            'conditions' => [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'better_payment_form_stripe_enable',
                        'value' => 'yes',
                    ],
                    [
                        'name' => 'better_payment_stripe_live_mode',
                        'value' => 'yes',
                    ],
                    [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'better_payment_stripe_public_key_live',
                                'value' => '',
                            ],
                            [
                                'name' => 'better_payment_stripe_secret_key_live',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ] );

        $this->add_control(
            'better_payment_form_paystack_enable',
            [
                'label'        => __( 'Enable Paystack', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => ! empty( $this->better_payment_global_settings['better_payment_settings_general_general_paystack'] ) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_general_paystack']) : 'no', //yes or no
                'condition' => [
                    'better_payment_form_payment_type!' => [ 'recurring', 'split-payment' ],
                ],
            ]
        );

        $this->add_control( 'better_payment_form_paystack_enable_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( __( 'Whoops! It seems like you haven\'t configured <b>Paystack (Public and Secret Key) Settings</b>. Make sure to configure these settings before you publish the form.', 'better-payment' ) ),
            'content_classes' => 'better-payment-warning',
            'conditions' => [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'better_payment_form_paystack_enable',
                        'value' => 'yes',
                    ],
                    [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'better_payment_paystack_public_key',
                                'value' => '',
                            ],
                            [
                                'name' => 'better_payment_paystack_secret_key',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ] );
        
        $this->add_control(
            'better_payment_form_email_enable',
            [
                'label'        => __( 'Enable Email Notification', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html($this->better_payment_global_settings['better_payment_settings_general_general_email']), //yes or no
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_show',
            [
                'label'        => __( 'Show Sidebar', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $better_payment_helper = new ClassesHelper();
        $better_payment_general_currency = $this->better_payment_global_settings['better_payment_settings_general_general_currency'];
        
        $better_payment_general_currency_woocommerce = $better_payment_general_currency;
        if( class_exists('woocommerce')  ) {
            $better_payment_general_currency_woocommerce = get_woocommerce_currency() ? get_woocommerce_currency() : $better_payment_general_currency_woocommerce;        
        }
        
        $this->add_control(
            'better_payment_form_currency_use_woocommerce',
            [
                'label'      => esc_html__( 'Use WooCommerce Currency?', 'better-payment' ),
                'type'       => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => '',
                'conditions' => $this->payment_source_woo_conditions(),
            ]
        );

        $this->add_control(
            'better_payment_form_currency',
            [
                'label'      => esc_html__( 'Currency', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => esc_html($better_payment_general_currency), //USD
                'options'    => $better_payment_helper->get_currency_list(),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_paypal_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_payment_source',
                                    'value' => 'manual',
                                ],
                            ],
                        ],

                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_stripe_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_payment_source',
                                    'value' => 'manual',
                                ],
                            ],
                        ],

                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_paystack_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_payment_source',
                                    'value' => 'manual',
                                ],
                            ],
                        ],

                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_paypal_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_currency_use_woocommerce',
                                    'value' => '',
                                ],
                            ],
                        ],

                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_stripe_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_currency_use_woocommerce',
                                    'value' => '',
                                ],
                            ],
                        ],

                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'better_payment_form_paystack_enable',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'better_payment_form_currency_use_woocommerce',
                                    'value' => '',
                                ],
                            ],
                        ],
                        
                    ],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_currency_woocommerce',
            [
                'label'      => esc_html__( 'WooCommerce Currency', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => $better_payment_general_currency_woocommerce,
                'options'    => [ 
                    $better_payment_general_currency_woocommerce => $better_payment_general_currency_woocommerce,
                ],
                'condition' => [
                    'better_payment_form_payment_source' => 'woocommerce',
                    'better_payment_form_currency_use_woocommerce' => 'yes',

                ]
            ]
        );

        $this->add_control(
            'better_payment_form_currency_notice',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( 'Supported by %1$sStripe%2$s (and/or %1$sPaystack%2$s) only', 'better-payment' ), '<strong>', '</strong>' ),
                'content_classes' => 'better-payment-warning',
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'better_payment_form_paypal_enable',
                            'value'    => 'yes',
                        ],
                        [
                            'relation' => 'or',
                            'terms'    => $this->get_paypal_unsupported_currencies_conditions(),
                        ]
                    ],
                ]
            ]
        );

        $this->add_control(
            'better_payment_form_currency_alignment',
            [
                'label' => esc_html__( 'Currency Alignment', 'better-payment' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'better-payment' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'better-payment' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    
        $this->form_element_settings();
        $this->paypal_form_setting();
        $this->stripe_form_setting();
        $this->paystack_form_setting();
        $this->email_element_settings();

        $this->success_message_setting();
        $this->error_message_setting();

        $this->form_style();
    }

    public function payment_source_woo_conditions(){
        $conditions = [
            'relation' => 'and',
            'terms' => [
                [
                    'name' => 'better_payment_form_payment_source',
                    'value' => 'woocommerce',
                ],
                [
                    'name' => 'better_payment_form_layout',
                    'operator' => '!=',
                    'value' => 'layout-4-pro',
                ],
                [
                    'name' => 'better_payment_form_layout',
                    'operator' => '!=',
                    'value' => 'layout-5-pro',
                ],
                // [
                //     'name' => 'better_payment_form_layout',
                //     'operator' => '!=',
                //     'value' => 'layout-6-pro',
                // ],
            ],
        ];

        return $conditions;
    }

    public function form_element_settings() {
        $this->start_controls_section(
            'better_payment_form_element',
            [
                'label'      => esc_html__( 'Form Settings', 'better-payment' ),
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'  => 'better_payment_form_paypal_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_stripe_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_paystack_enable',
                            'value' => 'yes',
                        ],
                    ]
                ]
            ]
        );

        $this->add_control(
            'better_payment_form_title',
            [
                'label'       => __( 'Form Name', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => __( 'Better Payment', 'better-payment' ),
                'ai'     => [
                    'active' => false,
                ],
            ]
        );

        do_action('better_payment/elementor/editor/layouts_form_settings_section', $this);

        $form_fields_repeater = new Repeater();

        $form_fields_repeater->add_control(
            'better_payment_field_name_heading',
            [
                'label' => __( 'Field Name', 'better-payment' ),
                'type'  => Controls_Manager::TEXT,
                'default'     => __( 'Field Name', 'better-payment' ),
                'label_block' => false,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_placeholder',
            [
                'label'       => __( 'Placeholder Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Field Name', 'better-payment' ),
                'label_block' => false,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_type',
            [
                'label'       => __( 'Field Type', 'better-payment' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'text',
                'options'     => [
                    'text'       => __( 'Text', 'better-payment' ),
                    'email'      => __( 'Email', 'better-payment' ),
                    'number'     => __( 'Number', 'better-payment' ),
                    // 'password'   => __( 'Password', 'better-payment' ),
                    // 'textarea'   => __( 'Textarea', 'better-payment' ),
                    // 'select'     => __( 'Select', 'better-payment' ),
                    // 'checkbox'   => __( 'Checkbox', 'better-payment' ),
                    // 'radio'      => __( 'Radio', 'better-payment' ),
                    // 'date'       => __( 'Date', 'better-payment' ),
                    // 'time'       => __( 'Time', 'better-payment' ),
                    // 'datetime'   => __( 'Date Time', 'better-payment' ),
                    // 'file'       => __( 'File', 'better-payment' ),
                    // 'hidden'     => __( 'Hidden', 'better-payment' ),
                ],
                'label_block' => false,
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_primary_field_type',
            [
                'label'       => __( 'Primary Field Type', 'better-payment' ),
                'description'       => __( 'If this is a primary field (first name, last name, email etc), then please select one.', 'better-payment' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'primary_none',
                'options'     => [
                    'primary_first_name'       => __( 'First Name', 'better-payment' ),
                    'primary_last_name'      => __( 'Last Name', 'better-payment' ),
                    'primary_email'     => __( 'Email', 'better-payment' ),
                    'primary_payment_amount'     => __( 'Payment Amount', 'better-payment' ),
                    'primary_none'     => __( 'None', 'better-payment' ),
                ],
                'label_block' => false,
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_icon',
            [
                'label' => esc_html__( 'Icon', 'better-payment' ),
                'description' => esc_html__( 'Select an icon for this field (not applicable for primary field - Payment Amount and layout 4, 5, 6).', 'better-payment' ),
                'type'  => Controls_Manager::ICONS,

            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_show',
            [
                'label'        => __( 'Show', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',

            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_show_notice',
            [
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					esc_html__( 'Field is hidden if payment source is WooCommerce or payment type is recurring/split payment or field dynamic value is enabled.', 'better-payment' ),
				),
				'content_classes' => 'elementor-descriptor',
                'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_required',
            [
                'label'        => __( 'Required', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'better_payment_field_name_show' => 'yes'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_min',
            [
                'label'        => __( 'Min. Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_max',
            [
                'label'        => __( 'Max. Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_default',
            [
                'label'        => __( 'Default Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_default_dynamic_enable',
            [
                'label'        => __( 'Dynamic Value', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'description'  => __( 'It will override default value!', 'better-payment' ),
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_default_dynamic',
            [
                'type'        => Controls_Manager::RAW_HTML,
                'raw' => sprintf( 
                    __( '<p class="better-payment-dynamic-value-info" style="word-break: break-word;"><a href="%1$s" target="_blank">Sample url »</a><br>%1$s</p>', 'better-payment' ), 
                    esc_url_raw( get_permalink( get_the_ID() ) . '?payment_amount=100' ) 
                ),
                'content_classes' => 'elementor-control-alert elementor-panel-alert elementor-panel-alert-info',
                'ai' => [
                    'active' => false,
                ],
                'condition'   => [
                    'better_payment_primary_field_type' => 'primary_payment_amount',
                    'better_payment_field_name_default_dynamic_enable' => 'yes'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_default_fixed',
            [
                'label'        => __( 'Readonly', 'better-payment' ),
				'type' => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
				'condition'    => [
                    'better_payment_primary_field_type' => 'primary_payment_amount'
                ],
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_display_inline',
            [
                'label'        => __( 'Display Inline?', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'inline-block',
                'default'      => 'block',
                'condition'    => [
                    'better_payment_field_name_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-payment-form-layout form {{CURRENT_ITEM}}.bp-form__group' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .better-payment-form {{CURRENT_ITEM}}.better-payment-field-advanced-layout' => 'display: {{VALUE}};',

                ]
            ]
        );

        $form_fields_repeater->add_control(
            'better_payment_field_name_display_inline_width',
            [
                'label'        => __( 'Column Width', 'better-payment' ),
                'type'         => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'em' ],
                'range'      => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 1500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 80,
                    ],
                ],
                'condition'    => [
                    'better_payment_field_name_show' => 'yes',
                    'better_payment_field_name_display_inline' => 'inline-block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-payment-form-layout form {{CURRENT_ITEM}}.bp-form__group' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment-form {{CURRENT_ITEM}}.better-payment-field-advanced-layout' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'description' => __( 'Set the width of the column. Use less than 50% to make fields inline', 'better-payment' ),
            ]
        );

        $form_fields_first_name = [
            'better_payment_field_name_heading' => esc_html__( 'First Name', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'First Name', 'better-payment' ),
            'better_payment_field_type' => 'text',
            'better_payment_primary_field_type' => 'primary_first_name',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'no',
        ];

        $form_fields_last_name = [
            'better_payment_field_name_heading' => esc_html__( 'Last Name', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Last Name', 'better-payment' ),
            'better_payment_field_type' => 'text',
            'better_payment_primary_field_type' => 'primary_last_name',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'no',
        ];

        $form_field_email = [
            'better_payment_field_name_heading' => esc_html__( 'Email Address', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Email Address', 'better-payment' ),
            'better_payment_field_type' => 'email',
            'better_payment_primary_field_type' => 'primary_email',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'yes',
        ];

        $form_field_payment_amount = [
            'better_payment_field_name_heading' => esc_html__( 'Payment Amount', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Payment Amount', 'better-payment' ),
            'better_payment_field_type' => 'number',
            'better_payment_primary_field_type' => 'primary_payment_amount',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'yes',
        ];
        
        $form_fields_first_name_layout_4_5_6 = [
            'better_payment_field_name_heading' => esc_html__( 'First name', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'First name', 'better-payment' ),
            'better_payment_field_type' => 'text',
            'better_payment_primary_field_type' => 'primary_first_name',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'no',
            'better_payment_field_name_display_inline' => 'inline-block',
            'better_payment_field_name_display_inline_width' => [
                'size' => '48',
                'unit' => '%',
            ],
        ];

        $form_fields_last_name_layout_4_5_6 = [
            'better_payment_field_name_heading' => esc_html__( 'Last name', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Last name', 'better-payment' ),
            'better_payment_field_type' => 'text',
            'better_payment_primary_field_type' => 'primary_last_name',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'no',
            'better_payment_field_name_display_inline' => 'inline-block',
            'better_payment_field_name_display_inline_width' => [
                'size' => '48',
                'unit' => '%',
            ],
        ];

        $form_field_email_layout_4_5_6 = [
            'better_payment_field_name_heading' => esc_html__( 'Email', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Enter your email', 'better-payment' ),
            'better_payment_field_type' => 'email',
            'better_payment_primary_field_type' => 'primary_email',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'yes',
        ];

        $form_field_payment_amount_layout_4_5_6 = [
            'better_payment_field_name_heading' => esc_html__( 'Amount', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Other amount', 'better-payment' ),
            'better_payment_field_type' => 'number',
            'better_payment_primary_field_type' => 'primary_payment_amount',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'yes',
        ];

        $form_field_payment_amount_layout_4_5_6_desc = [
            'better_payment_field_name_heading' => esc_html__( 'Amount to pay', 'better-payment' ),
            'better_payment_field_name_placeholder' => esc_html__( 'Other amount', 'better-payment' ),
            'better_payment_field_type' => 'number',
            'better_payment_primary_field_type' => 'primary_payment_amount',
            'better_payment_field_name_show' => 'yes',
            'better_payment_field_name_required' => 'yes',
        ];

        $form_fields_defaults = [
            $form_fields_first_name,
            $form_fields_last_name,
            $form_field_email,
            $form_field_payment_amount,
        ];

        $form_fields_defaults_layout_4_5_6 = [
            $form_fields_first_name_layout_4_5_6,
            $form_fields_last_name_layout_4_5_6,
            $form_field_email_layout_4_5_6,
            $form_field_payment_amount_layout_4_5_6,
        ];

        $form_fields_defaults_layout_4_5_6_desc = [
            $form_field_payment_amount_layout_4_5_6_desc,
            $form_fields_first_name_layout_4_5_6,
            $form_fields_last_name_layout_4_5_6,
            $form_field_email_layout_4_5_6,
        ];

        $form_fields_defaults_layout_4_5_6_woo = [
            $form_fields_first_name_layout_4_5_6,
            $form_fields_last_name_layout_4_5_6,
            $form_field_email_layout_4_5_6,
        ];

        $this->add_control(
			'better_payment_form_fields',
			[
				'label' => esc_html__( 'Form Fields', 'better-payment' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $form_fields_repeater->get_controls(),
				'default' => $form_fields_defaults,
				'title_field' => '{{{ better_payment_field_name_heading }}}',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
			]
		);
        
        $this->add_control(
			'better_payment_form_fields_layout_4_5_6',
			[
				'label' => esc_html__( 'Form Fields', 'better-payment' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $form_fields_repeater->get_controls(),
				'default' => $form_fields_defaults_layout_4_5_6,
				'title_field' => '{{{ better_payment_field_name_heading }}}',
                'condition' => [
                    'better_payment_form_layout' => ['layout-4-pro'],
                ],
			]
		);
        
        $this->add_control(
			'better_payment_form_fields_layout_4_5_6_desc',
			[
				'label' => esc_html__( 'Form Fields', 'better-payment' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $form_fields_repeater->get_controls(),
				'default' => $form_fields_defaults_layout_4_5_6_desc,
				'title_field' => '{{{ better_payment_field_name_heading }}}',
                'condition' => [
                    'better_payment_form_layout' => ['layout-5-pro'],
                ],
			]
		);
        
        $this->add_control(
			'better_payment_form_fields_layout_4_5_6_woo',
			[
				'label' => esc_html__( 'Form Fields', 'better-payment' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $form_fields_repeater->get_controls(),
				'default' => $form_fields_defaults_layout_4_5_6_woo,
				'title_field' => '{{{ better_payment_field_name_heading }}}',
                'condition' => [
                    'better_payment_form_layout' => ['layout-6-pro'],
                ],
			]
		);

        $repeater = new Repeater();


        $repeater->add_control(
            'better_payment_amount_val',
            [
                'label' => esc_html__( 'Payment Amount', 'better-payment' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 1,
            ]
        );

        $this->add_control(
            'better_payment_show_amount_list',
            [
                'label'        => __( 'Show Amount List', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition' => [
                    'better_payment_form_payment_source!' => 'woocommerce',
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_show_amount_list_layout_4_5_6',
            [
                'label'        => __( 'Show Amount List', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => [
                    'better_payment_form_layout' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                    'better_payment_form_payment_type!' => [ 'recurring', 'split-payment' ],
                ],
            ]
        );

        $this->add_control(
            'better_payment_amount',
            [
                'label'       => esc_html__( 'Amount List', 'better-payment' ),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'better_payment_amount_val' => 5
                    ],
                    [
                        'better_payment_amount_val' => 10
                    ],
                    [
                        'better_payment_amount_val' => 15
                    ],
                    [
                        'better_payment_amount_val' => 20
                    ],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '<i class="{{ better_payment_amount_val }}" aria-hidden="true"></i> {{{ better_payment_amount_val }}}',
                'condition'   => [
                    'better_payment_show_amount_list' => 'yes',
                    'better_payment_form_payment_source!' => 'woocommerce',
                    'better_payment_form_payment_type!' => [ 'recurring', 'split-payment' ],
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_amount_layout_4_5_6',
            [
                'label'       => esc_html__( 'Amount List', 'better-payment' ),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'better_payment_amount_val' => 10
                    ],
                    [
                        'better_payment_amount_val' => 30
                    ],
                    [
                        'better_payment_amount_val' => 50
                    ],
                    [
                        'better_payment_amount_val' => 100
                    ],
                    [
                        'better_payment_amount_val' => 500
                    ],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '<i class="{{ better_payment_amount_val }}" aria-hidden="true"></i> {{{ better_payment_amount_val }}}',
                'condition'   => [
                    'better_payment_show_amount_list_layout_4_5_6' => 'yes',
                    'better_payment_form_payment_type!' => [ 'recurring', 'split-payment' ],
                    'better_payment_form_layout' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        do_action('better_payment/elementor/editor/layouts_form_settings_amount_list_section', $this);

        $this->add_control(
            'better_payment_form_transaction_details_section',
            [
                'label'       => esc_html__( 'Transaction Details', 'better-payment' ),
                'type'        => Controls_Manager::HEADING,
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'better_payment_form_transaction_details_heading',
            [
                'label'       => esc_html__( 'Heading Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Transaction Details', 'better-payment' ),
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_transaction_details_sub_heading',
            [
                'label'       => esc_html__( 'Sub Heading Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Total payment of your product in the following:', 'better-payment' ),
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_transaction_details_product_title',
            [
                'label'       => esc_html__( 'Product Title Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Title:', 'better-payment' ),
                'condition' => [
                    'better_payment_form_payment_source' => 'woocommerce',
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_transaction_details_amount_text',
            [
                'label'       => esc_html__( 'Amount Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Amount:', 'better-payment' ),
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-6-pro'],
                ],
            ]
        );

        do_action('better_payment/elementor/editor/layouts_form_settings_transaction_details_section', $this);

        $this->add_control(
            'better_payment_form_form_buttons_section',
            [
                'label'       => esc_html__( 'Form Custom Text', 'better-payment' ),
                'type'        => Controls_Manager::HEADING,
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'better_payment_form_form_title_text',
            [
                'label'       => esc_html__( 'Form Title', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_form_layout' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_form_sub_title_text',
            [
                'label'       => esc_html__( 'Form Sub-Title', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_form_layout' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_form_buttons_paypal_button_text',
            [
                'label'       => esc_html__( 'PayPal Button Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_form_buttons_stripe_button_text',
            [
                'label'       => esc_html__( 'Stripe Button Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_form_buttons_paystack_button_text',
            [
                'label'       => esc_html__( 'Paystack Button Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function paypal_form_setting() {
        $this->start_controls_section(
            'better_payment_form_paypal_settings',
            [
                'label'     => esc_html__( 'PayPal Settings', 'better-payment' ),
                'condition' => [
                    'better_payment_form_paypal_enable' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'better_payment_paypal_business_email',
            [
                'label'       => __( 'Business Email', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html($this->better_payment_global_settings['better_payment_settings_payment_paypal_email']),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_paypal_button_type',
            [
                'label'   => esc_html__( 'Button Type', 'better-payment' ),
                'type'    => Controls_Manager::HIDDEN,
                'default' => '_xclick',
                'options' => [
                    '_xclick'    => 'XCLICK',
                    '_cart'      => 'CART',
                    '_donations' => 'DONATIONS'
                ]
            ]
        );

        $this->add_control(
            'better_payment_paypal_live_mode',
            [
                'label'        => __( 'Live Mode', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html($this->better_payment_global_settings['better_payment_settings_payment_paypal_live_mode']), //yes or no
            ]
        );

        $this->end_controls_section();
    }

    public function stripe_form_setting() {
        $this->start_controls_section(
            'better_payment_form_stripe_settings',
            [
                'label'     => esc_html__( 'Stripe Settings', 'better-payment' ),
                'condition' => [
                    'better_payment_form_stripe_enable' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'better_payment_stripe_public_key',
            [
                'label'       => __( 'Test Public Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html( $this->better_payment_global_settings['better_payment_settings_payment_stripe_test_public'] ),
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_stripe_live_mode!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'better_payment_stripe_secret_key',
            [
                'label'       => __( 'Test Secret Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'input_type'  => 'password',
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html( $this->better_payment_global_settings['better_payment_settings_payment_stripe_test_secret'] ),
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_stripe_live_mode!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'better_payment_stripe_public_key_live',
            [
                'label'       => __( 'Live Public Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html( $this->better_payment_global_settings['better_payment_settings_payment_stripe_live_public'] ),
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_stripe_live_mode' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'better_payment_stripe_secret_key_live',
            [
                'label'       => __( 'Live Secret Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'input_type'  => 'password',
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html( $this->better_payment_global_settings['better_payment_settings_payment_stripe_live_secret'] ),
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_stripe_live_mode' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'better_payment_stripe_live_mode',
            [
                'label'        => __( 'Live Mode', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => esc_html( $this->better_payment_global_settings['better_payment_settings_payment_stripe_live_mode'] ), //yes or no
            ]
        );

        $this->end_controls_section();
    }

    public function paystack_form_setting() {
        $this->start_controls_section(
            'better_payment_form_paystack_settings',
            [
                'label'     => esc_html__( 'Paystack Settings', 'better-payment' ),
                'condition' => [
                    'better_payment_form_paystack_enable' => 'yes'
                ]
            ]
        );
        
        $better_payment_is_paystack_live = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode'] ) && $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode'] == 'yes' ? 1 : 0;
        $bp_settings_paystack_live_public = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_public'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_public'] : '';
        $bp_settings_paystack_test_public = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_public'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_public'] : '';
        
        $this->add_control(
            'better_payment_paystack_public_key',
            [
                'label'       => __( 'Public Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_paystack_live ? esc_html( $bp_settings_paystack_live_public ) : esc_html( $bp_settings_paystack_test_public ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $bp_settings_paystack_live_secret = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_secret'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_secret'] : '';
        $bp_settings_paystack_test_secret = ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_secret'] ) ? $this->better_payment_global_settings['better_payment_settings_payment_paystack_test_secret'] : '';
        
        $this->add_control(
            'better_payment_paystack_secret_key',
            [
                'label'       => __( 'Secret Key', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'input_type'  => 'password',
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => $better_payment_is_paystack_live ? esc_html( $bp_settings_paystack_live_secret ) : esc_html( $bp_settings_paystack_test_secret ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_paystack_live_mode',
            [
                'label'        => __( 'Live Mode', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => ! empty( $this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode'] ) ? esc_html($this->better_payment_global_settings['better_payment_settings_payment_paystack_live_mode']) : 'no', //yes or no
            ]
        );

        $this->end_controls_section();
    }

    public function email_element_settings() {
        $this->start_controls_section(
            'better_payment_email_element',
            [
                'label'      => esc_html__( 'Email Settings', 'better-payment' ),
                'condition' => [
                    'better_payment_form_email_enable' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'better_payment_form_email_logo',
            [
                'label' => __('Choose Logo', 'better-payment'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->start_controls_tabs( 'better_payment_email_tabs' );

        //Admin Email Notification
        $this->start_controls_tab( 'better_payment_email_admin_tab', [
			'label' => __( 'Admin', 'better-payment' ),
		] );

        $this->add_control('better_payment_email_to', [
            'label' => __('To', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_to']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_to']) : esc_html(get_option('admin_email')),
            'placeholder' => get_option('admin_email'),
            'label_block' => true,
            'title' => __('Email address to notify site admin after each successful transaction', 'better-payment'),
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $default_subject = !empty($this->better_payment_global_settings['better_payment_settings_general_email_subject']) ? esc_html__($this->better_payment_global_settings['better_payment_settings_general_email_subject'], 'better-payment') : sprintf(__('Better Payment transaction on %s', 'better-payment'), esc_html(get_option('blogname')));

        $this->add_control('better_payment_email_subject', [
            'label' => __('Subject', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html($default_subject),
            'placeholder' => esc_html($default_subject),
            'label_block' => true,
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_content', [
            'label' => __('Message', 'better-payment'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_message_admin']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_message_admin']) : '',
            'placeholder' => '',
            'render_type' => 'none',
        ]);

        $this->add_control(
            'better_payment_email_content_greeting',
            [
                'label'        => __( 'Show Greeting Text', 'better-payment' ),
                'type'         => Controls_Manager::HIDDEN,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        
        $this->add_control(
            'better_payment_email_content_heading',
            [
                'label'        => __( 'Show Header Text', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'better_payment_email_content_from_section',
            [
                'label'        => __( 'Show From Section', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'better_payment_email_content_to_section',
            [
                'label'        => __( 'Show To Section', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'better_payment_email_content_transaction_summary',
            [
                'label'        => __( 'Show Transaction Summary', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'better_payment_email_content_footer_text',
            [
                'label'        => __( 'Show Footer Text', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        
        $site_url_parsed = wp_parse_url(get_site_url());
        $better_domain = $site_url_parsed['host'] ? esc_html($site_url_parsed['host']) : 'example.com';
         
        $this->add_control('better_payment_email_from', [
            'label' => __('From email', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_from_email']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_from_email']) : "wordpress@$better_domain",
            'render_type' => 'none',
            'separator' => 'before',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_from_name', [
            'label' => __('From name', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_from_name']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_from_name']) : esc_html(get_bloginfo('name')),
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_reply_to', [
            'label' => __('Reply-To', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_reply_to']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_reply_to']) : "wordpress@$better_domain",
            'placeholder' => "wordpress@$better_domain",
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_cc', [
            'label' => __('Cc', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_cc']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_cc']) : '',
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_bcc', [
            'label' => __('Bcc', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_bcc']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_bcc']) : '',
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_content_type', [
            'label' => __('Send as', 'better-payment'),
            'type' => Controls_Manager::SELECT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_send_as']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_send_as']) : 'html', //html or plain
            'render_type' => 'none',
            'options' => [
                'html' => __('HTML', 'better-payment'),
                'plain' => __('Plain', 'better-payment')
            ]
        ]);

		$this->end_controls_tab();

        //Customer Email Notification
        $this->start_controls_tab( 'better_payment_email_customer_tab', [
			'label' => __( 'Customer', 'better-payment' ),
		] );

        $default_subject = !empty($this->better_payment_global_settings['better_payment_settings_general_email_subject_customer']) ? esc_html__($this->better_payment_global_settings['better_payment_settings_general_email_subject_customer'], 'better-payment') : sprintf(__('Better Payment transaction on %s', 'better-payment'), esc_html(get_option('blogname')));

        $this->add_control('better_payment_email_subject_customer', [
            'label' => __('Subject', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html($default_subject),
            'placeholder' => esc_html($default_subject),
            'label_block' => true,
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_content_customer', [
            'label' => __('Message', 'better-payment'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_message_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_message_customer']) : '',
            'placeholder' => '',
            'render_type' => 'none'
        ]);

        $this->add_control(
			'better_payment_form_email_attachment_pdf_show',
			[
				'label'        => __('PDF Attachment?', 'better-payment'),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __('Yes', 'better-payment'),
				'label_off'    => __('No', 'better-payment'),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
            'better_payment_form_email_attachment',
            [
                'label' => __('Attachment', 'better-payment'),
                'description' => __('Allowed file types: jpg, jpeg, png', 'better-payment'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_form_email_attachment_pdf_show!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_email_attachment_pdf',
            [
                'label' => __('PDF', 'better-payment'),
                'description' => __('Allowed file types: pdf', 'better-payment'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => [
                    'application/pdf'
				],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
                'condition' => [
                    'better_payment_form_email_attachment_pdf_show' => 'yes',
                ],
            ]
        );
        
        $site_url_parsed = wp_parse_url(get_site_url());
        $better_domain = $site_url_parsed['host'] ? esc_html($site_url_parsed['host']) : 'example.com';
         
        $this->add_control('better_payment_email_from_customer', [
            'label' => __('From email', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_from_email_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_from_email_customer']) : "wordpress@$better_domain",
            'render_type' => 'none',
            'separator' => 'before',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_from_name_customer', [
            'label' => __('From name', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_from_name_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_from_name_customer']) : get_bloginfo('name'),
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_reply_to_customer', [
            'label' => __('Reply-To', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_reply_to_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_reply_to_customer']) : "wordpress@$better_domain",
            'placeholder' => "wordpress@$better_domain",
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_cc_customer', [
            'label' => __('Cc', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_cc_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_cc_customer']) : '',
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_bcc_customer', [
            'label' => __('Bcc', 'better-payment'),
            'type' => Controls_Manager::TEXT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_bcc_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_bcc_customer']) : '',
            'render_type' => 'none',
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->add_control('better_payment_email_content_type_customer', [
            'label' => __('Send as', 'better-payment'),
            'type' => Controls_Manager::SELECT,
            'default' => !empty($this->better_payment_global_settings['better_payment_settings_general_email_send_as_customer']) ? esc_html($this->better_payment_global_settings['better_payment_settings_general_email_send_as_customer']) : 'html', //html or plain
            'render_type' => 'none',
            'options' => [
                'html' => __('HTML', 'better-payment'),
                'plain' => __('Plain', 'better-payment')
            ]
        ]);

		$this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function success_message_setting() {
        $this->start_controls_section(
            'better_payment_form_success_message_settings',
            [
                'label'      => esc_html__( 'Success Message', 'better-payment' ),
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'  => 'better_payment_form_stripe_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_paypal_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_paystack_enable',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_icon',
            [
                'label' => esc_html__( 'Icon', 'better-payment' ),
                'type'  => Controls_Manager::ICONS,

            ]
        );

        $this->add_control(
            'better_payment_form_success_message_content',
            [
                'label'       => esc_html__( 'Content', 'better-payment' ),
                'type'        => Controls_Manager::HEADING,
                'separator'    => 'before',
            ]
        );

        $this->add_control('better_payment_form_success_message_heading', [
            'label' => __('Heading', 'better-payment'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('You paid', 'better-payment') . ' [currency_symbol][amount] ' . __('to', 'better-payment') . ' [store_name]',
            'placeholder' => __('You paid', 'better-payment') . ' [currency_symbol][amount] ' . __('to', 'better-payment') . ' [store_name]',
            'description' => __('Use shortcode like', 'better-payment') . ' [currency_symbol], [amount], [store_name] ' . __('to customize your message.', 'better-payment') . ' ' . __('eg:', 'better-payment') . ' ' . __('You paid', 'better-payment') . ' [currency_symbol][amount] ' . __('to', 'better-payment') . ' [store_name] ' . __('for your order.', 'better-payment'),
            'render_type' => 'none',
        ]);

        $this->add_control('better_payment_form_success_message_sub_heading', [
            'label' => __('Sub Heading', 'better-payment'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('Payment confirmation email will be sent to', 'better-payment') . ' [customer_email]',
            'placeholder' => __('Payment confirmation email will be sent to', 'better-payment') . ' [customer_email]',
            'description' =>  __('Payment confirmation email will be sent to', 'better-payment') . ' [customer_email]',
            'render_type' => 'none',
        ]);

        $this->add_control(
            'better_payment_form_success_message_transaction',
            [
                'label'       => __( 'Transaction', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Transaction ID', 'better-payment' ),
                'placeholder' => __( 'Transaction ID', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_thanks',
            [
                'label'       => __( 'Thank You', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Thank you for your payment', 'better-payment' ),
                'placeholder' => __( 'Thank you for your payment', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_amount_text',
            [
                'label'       => __( 'Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Amount', 'better-payment' ),
                'placeholder' => __( 'Amount', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_currency_text',
            [
                'label'       => __( 'Currency', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Currency', 'better-payment' ),
                'placeholder' => __( 'Currency', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_pay_method_text',
            [
                'label'       => __( 'Payment Method', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Payment Method', 'better-payment' ),
                'placeholder' => __( 'Payment Method', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_pay_type_text',
            [
                'label'       => __( 'Payment Type', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Payment Type', 'better-payment' ),
                'placeholder' => __( 'Payment Type', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_merchant_details_text',
            [
                'label'       => __( 'Merchant Details', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Merchant Details', 'better-payment' ),
                'placeholder' => __( 'Merchant Details', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_paid_amount_text',
            [
                'label'       => __( 'Paid Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Paid Amount', 'better-payment' ),
                'placeholder' => __( 'Paid Amount', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_purchase_details_text',
            [
                'label'       => __( 'Purchase Details', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Purchase Details', 'better-payment' ),
                'placeholder' => __( 'Purchase Details', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_print_btn_text',
            [
                'label'       => __( 'Print', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Print', 'better-payment' ),
                'placeholder' => __( 'Print', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_success_message_view_details_btn_text',
            [
                'label'       => __( 'View Details', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'View Details', 'better-payment' ),
                'placeholder' => __( 'View Details', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control( 'better_payment_form_success_page_view_details_url', [
			'type'          => Controls_Manager::URL,
			'label'         => __( 'User Dashboard URL', 'better-payment' ),
			'show_external' => false,
			'placeholder'   => __( 'eg. https://example.com/custom-page/', 'better-payment' ),
			'description'   => __( 'Please enter the page url where <strong>User Dashboard</strong> widget is used.', 'better-payment' ),
		] );

        $this->add_control( 'better_payment_form_success_page_url', [
			'type'          => Controls_Manager::URL,
			'label'         => __( 'Custom Redirect URL', 'better-payment' ),
			'show_external' => false,
			'placeholder'   => __( 'eg. https://example.com/custom-page/', 'better-payment' ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'better-payment' ),
		] );

        $this->end_controls_section();
    }

    public function error_message_setting() {
        $this->start_controls_section(
            'better_payment_form_error_message_settings',
            [
                'label'      => esc_html__( 'Error Message', 'better-payment' ),
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'  => 'better_payment_form_stripe_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_paypal_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name'  => 'better_payment_form_paystack_enable',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'better_payment_form_error_message_icon',
            [
                'label' => esc_html__( 'Icon', 'better-payment' ),
                'type'  => Controls_Manager::ICONS,

            ]
        );

        $this->add_control(
            'better_payment_form_error_message_heading',
            [
                'label'       => __( 'Heading Message Text', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Payment Failed', 'better-payment' ),
                'dynamic'     => [
                    'active' => true,
                ],
                'label_block' => true,
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control( 'better_payment_form_error_page_url', [
			'type'          => Controls_Manager::URL,
			'label'         => __( 'Custom Redirect URL', 'better-payment' ),
			'show_external' => false,
			'placeholder'   => __( 'eg. https://example.com/custom-page/', 'better-payment' ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'better-payment' ),
		] );

        $this->end_controls_section();
    }

    public function form_style() {
        $this->form_sidebar_style();
        $this->form_sidebar_text_style();
        $this->form_container_style();
        $this->form_fields_style();
        $this->form_fields_amount_style();
        $this->form_button_style();

    }

    public function form_sidebar_style() {
        $this->start_controls_section(
            'better_payment_form_sidebar_style',
            [
                'label' => esc_html__( 'Form Sidebar Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'better_payment_form_sidebar_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .better-payment .dynamic-amount-section, {{WRAPPER}} .better-payment .transaction-details-wrap, {{WRAPPER}} .better-payment .donation-image-wrap, {{WRAPPER}} .better-payment .order-details-wrap',
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .dynamic-amount-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .transaction-details-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-image-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .order-details-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .dynamic-amount-section-inner.p-6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .transaction-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .donation-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .order-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .dynamic-amount-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .transaction-details-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .order-details-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'better_payment_form_sidebar_border',
                'selector' => '{{WRAPPER}} .better-payment .dynamic-amount-section, {{WRAPPER}} .better-payment .transaction-details-wrap, {{WRAPPER}} .better-payment .donation-image-wrap, {{WRAPPER}} .better-payment .order-details-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'better_payment_form_sidebar_box_shadow',
                'selector' => '{{WRAPPER}} .better-payment .dynamic-amount-section, {{WRAPPER}} .better-payment .transaction-details-wrap, {{WRAPPER}} .better-payment .donation-image-wrap, {{WRAPPER}} .better-payment .order-details-wrap',
            ]
        );

        $this->end_controls_section();
    }

    public function form_sidebar_text_style() {
        $this->start_controls_section(
            'better_payment_form_sidebar_text_style',
            [
                'label' => esc_html__( 'Sidebar Text Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );
        
        $this->add_control(
            'better_payment_form_sidebar_text_title_style',
            [
                'label'     => __( 'Title Text', 'better-payment' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_text_title_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .order-details-wrap .title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_sidebar_text_title_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-title, {{WRAPPER}} .transaction-details-wrap .transaction-details-header-title, {{WRAPPER}} .order-details-wrap .title',
                'separator' => 'after',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_title_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .order-details-wrap .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_title_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .order-details-wrap .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_text_sub_title_style',
            [
                'label'     => __( 'Sub-Title Text', 'better-payment' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro', 'layout-6-pro'],
                ],
            ],
        );

        $this->add_control(
            'better_payment_form_sidebar_text_sub_title_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-sub-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-paragraph' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_sidebar_text_sub_title_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-sub-title, {{WRAPPER}} .transaction-details-wrap .transaction-details-header-paragraph',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_sub_title_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-paragraph' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_sub_title_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .transaction-details-wrap .transaction-details-header-paragraph' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_text_amount_style',
            [
                'label'     => __( 'Amount Text', 'better-payment' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ],
        );

        $this->add_control(
            'better_payment_form_sidebar_text_amount_title_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .order-details-wrap .total-amount-text' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_sidebar_text_amount_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount, {{WRAPPER}} .transaction-details-wrap .total-amount-text, {{WRAPPER}} .order-details-wrap .total-amount-text',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_amount_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .order-details-wrap .total-amount-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_amount_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .order-details-wrap .total-amount-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_text_amount_summary_style',
            [
                'label'     => __( 'Amount Summary', 'better-payment' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ],
        );

        $this->add_control(
            'better_payment_form_sidebar_text_amount_summary_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount-summary' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-number' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-number span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .order-details-wrap .total-amount-number' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_sidebar_text_amount_summary_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount-summary, {{WRAPPER}} .transaction-details-wrap .total-amount-number, {{WRAPPER}} .transaction-details-wrap .total-amount-number span, {{WRAPPER}} .order-details-wrap .total-amount-number',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_amount_summary_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount-summary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .order-details-wrap .total-amount-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_amount_summary_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dynamic-amount-section-inner .bp-dynamic-amount-section-amount-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .transaction-details-wrap .total-amount-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .order-details-wrap .total-amount-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_sidebar_text_icon_style',
            [
                'label'     => __( 'Icon', 'better-payment' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ],
        );

        $this->add_control(
            'better_payment_form_sidebar_text_icon_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .bp-dynamic-amount-section-icon:before' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-1', 'layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_icon_font_size',
            [
                'label'      => esc_html__( 'Font Size', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bp-dynamic-amount-section-icon:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-1', 'layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_icon_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .bp-dynamic-amount-section-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-1', 'layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_sidebar_text_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .bp-dynamic-amount-section-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-1', 'layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function form_container_style() {
        $this->start_controls_section(
            'better_payment_form_container_style',
            [
                'label' => esc_html__( 'Form Container Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'better_payment_form_container_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .better-payment .form-content-section, {{WRAPPER}} .better-payment .general-form-wrap, {{WRAPPER}} .better-payment .donation-form-wrap, {{WRAPPER}} .better-payment .woo-form-wrap',
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_container_margin',
            [
                'label'      => esc_html__( 'Margin', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_container_padding',
            [
                'label'      => esc_html__( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section-inner.p-6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .general-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .donation-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .better-payment .woo-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_container_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'better_payment_form_container_border',
                'selector' => '{{WRAPPER}} .better-payment .form-content-section, {{WRAPPER}} .better-payment .general-form-wrap, {{WRAPPER}} .better-payment .donation-form-wrap, {{WRAPPER}} .better-payment .woo-form-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'better_payment_form_container_box_shadow',
                'selector' => '{{WRAPPER}} .better-payment .form-content-section, {{WRAPPER}} .better-payment .general-form-wrap, {{WRAPPER}} .better-payment .donation-form-wrap, {{WRAPPER}} .better-payment .woo-form-wrap',
            ]
        );

        $this->end_controls_section();
    }

    public function form_fields_style() {
        $this->start_controls_section(
            'better_payment_form_fields_style',
            [
                'label' => esc_html__( 'Form Fields Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'better_payment_form_fields_input_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .form-content-section input' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_fields_input_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .form-content-section input' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .form-content-section .payment-method-checkbox' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'better_payment_form_fields_input_placeholder_color',
            [
                'label'     => __( 'Placeholder Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .form-content-section input::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_spacing',
            [
                'label'      => __( 'Spacing', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => '0',
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section input' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_padding',
            [
                'label'      => __( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_text_indent',
            [
                'label'      => __( 'Text Indent', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .form-content-section input' => 'text-indent: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .general-form-wrap input' => 'text-indent: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'text-indent: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_width',
            [
                'label'      => __( 'Input Width', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'description' => __( 'Set width for all input fields. Not applicable if the field is set to display inline (<b>Content => Form Settings => Form Fields (Repeater) => Display Inline?</b>)', 'better-payment' ),
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section input[type="email"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section input[type="number"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="email"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="number"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="email"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="number"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="email"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="number"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_height',
            [
                'label'      => __( 'Input Height', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section input[type="text"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section input[type="email"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section input[type="number"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="text"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="email"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap input[type="number"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="text"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="email"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input[type="number"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="text"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="email"]' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input[type="number"]' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'better_payment_form_fields_input_border',
                'label'       => __( 'Border', 'better-payment' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .better-payment .form-content-section input, {{WRAPPER}} .better-payment .general-form-wrap input, {{WRAPPER}} .better-payment .donation-form-wrap input, {{WRAPPER}} .better-payment .woo-form-wrap input',
            ]
        );

        $this->add_control(
            'better_payment_form_fields_input_radius',
            [
                'label'      => __( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_fields_input_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .better-payment .form-content-section input, {{WRAPPER}} .better-payment .general-form-wrap input, {{WRAPPER}} .better-payment .donation-form-wrap input, {{WRAPPER}} .better-payment .woo-form-wrap input',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'better_payment_form_fields_input_box_shadow',
                'selector'  => '{{WRAPPER}} .better-payment .form-content-section input, {{WRAPPER}} .better-payment .general-form-wrap input, {{WRAPPER}} .better-payment .donation-form-wrap input, {{WRAPPER}} .better-payment .woo-form-wrap input',
            ]
        );

        $this->add_control(
			'better_payment_form_fields_payment_method_label',
			[
				'label'     => esc_html__( 'Payment Method', 'better-payment' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
			]
		);

        $this->start_controls_tabs( 'better_payment_form_fields_payment_amount_border_tabs' );

        $this->start_controls_tab(
            'better_payment_form_fields_payment_method_border_tab_active',
            [
                'label' => __( 'Active', 'better-payment' ),
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'better_payment_form_fields_payment_method_border_active',
                'label'       => __( 'Border', 'better-payment' ),
                'selector'    => '{{WRAPPER}} .better-payment .payment-form-layout-3 .payment-method-checkbox.active, {{WRAPPER}} .better-payment .payment-form-layout-1 .payment-method-checkbox.single-item.active, {{WRAPPER}} .better-payment .payment-form-layout-2 .payment-method-checkbox.single-item.active, {{WRAPPER}} .better-payment .general-form-wrap .payment-method-items input:checked + .payment-method-image-wrap, {{WRAPPER}} .better-payment .woo-form-wrap .payment-method-items input:checked + .payment-option',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'better_payment_form_fields_payment_method_border_tab_inactive',
            [
                'label' => __( 'Inactive', 'better-payment' ),
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'better_payment_form_fields_payment_method_border_inactive',
                'label'       => __( 'Border', 'better-payment' ),
                'selector'    => '{{WRAPPER}} .better-payment .payment-form-layout-3 .payment-method-checkbox, {{WRAPPER}} .better-payment .general-form-wrap .payment-method-items .payment-method-image-wrap, {{WRAPPER}} .better-payment .woo-form-wrap .payment-method-items .payment-option',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-5-pro'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
			'better_payment_form_fields_input_icon_label',
			[
				'label'     => esc_html__( 'Input Icon', 'better-payment' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
			]
		);

        $this->add_control(
            'better_payment_form_fields_input_icon_color',
            [
                'label'     => __( 'Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-left .icon'               => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-right .icon'              => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-left .icon i::before'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-right .icon i::before'    => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control .bp-currency-symbol'                => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_icon_size',
            [
                'label'      => __( 'Size', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-left .icon'   => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-right .icon'  => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control .bp-currency-symbol'    => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_icon_width',
            [
                'label'      => __( 'Width', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-left .icon'   => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-right .icon'  => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_input_icon_height',
            [
                'label'      => __( 'Height', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-left .icon'   => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .form-content-section .control.has-icons-right .icon'  => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'better_payment_form_layout!' => ['layout-4-pro', 'layout-5-pro', 'layout-6-pro'],
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function form_fields_amount_style() {

        $this->start_controls_section(
            'better_payment_form_fields_amount_style',
            [
                'label' => __( 'Amount Fields Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_amount_width',
            [
                'label'      => __( 'Input Width', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_amount_height',
            [
                'label'      => __( 'Input Height', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_fields_amount_spacing',
            [
                'label'      => __( 'Spacing', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => '0',
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'better_payment_form_amount_tabs_button_style' );

        $this->start_controls_tab(
            'better_payment_form_fields_amount_normal',
            [
                'label' => __( 'Normal', 'better-payment' ),
            ]
        );

        $this->add_control(
            'better_payment_form_fields_amount_normal_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_fields_amount_normal_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'better_payment_form_fields_amount_selected',
            [
                'label' => __( 'Selected', 'better-payment' ),
            ]
        );

        $this->add_control(
            'better_payment_form_fields_amount_selected_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group input[type="radio"].bp-form__control:checked ~ label' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount input:checked + .text' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount input:checked + .text' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount input:checked + .text' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_fields_amount_selected_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group input[type="radio"].bp-form__control:checked ~ label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount input:checked + .text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount input:checked + .text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount input:checked + .text' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'better_payment_form_fields_amount_border',
                'label'       => __( 'Border', 'better-payment' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label, {{WRAPPER}} .better-payment .general-form-wrap .payment-amount input:checked + .text, {{WRAPPER}} .better-payment .donation-form-wrap .payment-amount input:checked + .text, {{WRAPPER}} .better-payment .woo-form-wrap .payment-amount input:checked + .text',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'better_payment_form_fields_amount_border_radius',
            [
                'label'      => __( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_fields_amount_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label, {{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text, {{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text, {{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'better_payment_form_fields_amount_box_shadow',
                'selector'  => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label, {{WRAPPER}} .better-payment .general-form-wrap .payment-amount .text, {{WRAPPER}} .better-payment .donation-form-wrap .payment-amount .text, {{WRAPPER}} .better-payment .woo-form-wrap .payment-amount .text',
            ]
        );

        $this->end_controls_section();
    }

    public function form_button_style() {
        $this->start_controls_section(
            'better_payment_form_button_style',
            [
                'label' => __( 'Form Button Style', 'better-payment' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_button_width',
            [
                'label'      => __( 'Width', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 700,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'flex: none;width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'flex: none;width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'flex: none;width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'flex: none;width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'better_payment_form_button_tabs_style' );

        $this->start_controls_tab(
            'better_payment_form_button_normal',
            [
                'label' => __( 'Normal', 'better-payment' ),
            ]
        );

        $this->add_control(
            'better_payment_form_button_normal_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_button_normal_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'better_payment_form_button_normal_border',
                'label'    => __( 'Border', 'better-payment' ),
                'default'  => '1px',
                'selector' => '{{WRAPPER}} .better-payment .payment-form-layout .button, {{WRAPPER}} .better-payment .general-form-wrap .button, {{WRAPPER}} .better-payment .donation-form-wrap .button, {{WRAPPER}} .better-payment .woo-form-wrap .button',
            ]
        );

        $this->add_control(
            'better_payment_form_button_normal_border_radius',
            [
                'label'      => __( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_button_normal_padding',
            [
                'label'      => __( 'Padding', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'better_payment_form_button_normal_margin',
            [
                'label'      => __( 'Margin Top', 'better-payment' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button' => 'margin-top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button' => 'margin-top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button' => 'margin-top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_button_normal_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .better-payment .payment-form-layout .button, {{WRAPPER}} .better-payment .general-form-wrap .button, {{WRAPPER}} .better-payment .donation-form-wrap .button, {{WRAPPER}} .better-payment .woo-form-wrap .button',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'better_payment_form_button_normal_box_shadow',
                'selector'  => '{{WRAPPER}} .better-payment .payment-form-layout .button, {{WRAPPER}} .better-payment .general-form-wrap .button, {{WRAPPER}} .better-payment .donation-form-wrap .button, {{WRAPPER}} .better-payment .woo-form-wrap .button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'better_payment_form_button_hover',
            [
                'label' => __( 'Hover', 'better-payment' ),
            ]
        );

        $this->add_control(
            'better_payment_form_button_hover_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .payment-form-layout.payment-form-layout-3 .button:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button:hover' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_button_hover_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .payment-form-layout.payment-form-layout-3 .button:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button:hover' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button:hover' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'better_payment_form_button_hover_border',
            [
                'label'     => __( 'Border Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .better-payment .payment-form-layout .button:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .general-form-wrap .button:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .donation-form-wrap .button:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .better-payment .woo-form-wrap .button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     */
    protected function render() {
        $is_edit_mode = Plugin::instance()->editor->is_edit_mode();

        if ( $is_edit_mode && ( ! current_user_can('manage_options') ) ) {
            return;
        }
        
        $settings = $this->get_settings_for_display();

        if ( $settings[ 'better_payment_form_paypal_enable' ] != 'yes' && $settings[ 'better_payment_form_stripe_enable' ] != 'yes' && $settings[ 'better_payment_form_paystack_enable' ] != 'yes' ) {
            return false;
        }

        $payment_field = "number" ;

        $response = Handler::manage_response( $settings, $this->get_id() );
        if ( $response ) {
            return false;
        }

        do_action('better_payment/elementor/editor/manage_response_webhook', $this, $settings );

        wp_enqueue_script( 'better-payment' );

        if( $this->pro_enabled ){
            wp_enqueue_script( 'better-payment-pro-common-script' );
            wp_enqueue_style( 'better-payment-pro-common-style' );
        }

        $action       = esc_url( admin_url( 'admin-post.php' ) );
        $setting_meta = wp_json_encode( [
            'page_id'   => get_the_ID(),
            'widget_id' => esc_attr( $this->get_id() ),
        ] );
        
        $better_payment_placeholder_class = '';
        if ( !empty($settings[ 'better_payment_placeholder_switch' ]) && $settings[ 'better_payment_placeholder_switch' ] != 'yes' ) {
            $better_payment_placeholder_class = 'better-payment-hide-placeholder';
        }        

        $data = array(
            'payment_field' => $payment_field,
            'action'       => $action,
            'setting_meta' => $setting_meta,
            'better_payment_placeholder_class' => $better_payment_placeholder_class,
        );

        $widgetObj = $this;
        $extraDatas = $data;

        $better_payment_form_layout = sanitize_text_field($settings[ 'better_payment_form_layout' ]);
        $better_payment_form_layout = in_array($better_payment_form_layout, array_keys( $this->better_payment_free_layouts() ) ) ? $better_payment_form_layout : 'layout-1';

        $template_file = BETTER_PAYMENT_ADMIN_VIEWS_PATH . '/elementor/layouts/' . $better_payment_form_layout . '.php';
        $is_pro_layout = str_contains($better_payment_form_layout, '-pro');
        
        if ( $this->pro_enabled && $is_pro_layout ){
            $template_file = BETTER_PAYMENT_PRO_ADMIN_VIEWS_PATH . '/elementor/layouts/' . $better_payment_form_layout . '.php';
        }

        if ( ( ! $this->pro_enabled ) && $is_pro_layout ){
            $template_file = BETTER_PAYMENT_ADMIN_VIEWS_PATH . '/partials/layout-pro-banners.php';
        }

        $better_payment_form_content = '';
        
        if ( file_exists($template_file) ) {
            ob_start();
            include $template_file;
            $better_payment_form_content = ob_get_contents();
            ob_end_clean();
        }

        $better_payment_form_content = apply_filters( 'better_payment/elementor/editor/get_layout_content', $better_payment_form_content, $settings, $this, $data );

        echo $better_payment_form_content;
    }

    /**
     * Render amount element
     *
     * @since 0.0.1
     */
    public function render_amount_element( $settings, $args = [] ) {
        $defauls = [
            'version' => 'v1',
            'items' => $settings['better_payment_amount'],
        ];

        if ( ! empty( $args['version'] ) ) {
            $defauls['version'] = 'v2';
            $defauls['items'] = $settings['better_payment_amount_layout_4_5_6'];
        }

        $layout_form_currency_symbol    = $this->get_currency_symbol( esc_html($settings[ 'better_payment_form_currency' ]) );
        $currency_alignment             = ! empty ( $settings['better_payment_form_currency_alignment'] ) ? $settings['better_payment_form_currency_alignment'] : 'left';
        $layout_form_currency_left      = 'left'    === $currency_alignment ? $layout_form_currency_symbol : '';
        $layout_form_currency_right     = 'right'   === $currency_alignment ? $layout_form_currency_symbol : '' ;
        
        $id = esc_attr( $this->get_id() );
        ?>
        <!-- <div class="pt-5"> -->
        <?php
        foreach ( $defauls['items'] as $item ) {
            $uid = uniqid();
            if ( 'v1' === $defauls['version'] ) :
            ?>
                <div class="bp-form__group pt-5">
                    <input type="radio" value="<?php echo floatval( $item[ 'better_payment_amount_val' ] ); ?>"
                        id="bp_payment_amount-<?php echo esc_attr($uid); ?>" class="bp-form__control bp-form_pay-radio "
                        name="primary_payment_amount_radio">
                    <label for="bp_payment_amount-<?php echo esc_attr($uid); ?>"><?php printf( "%s%s%s", esc_html( $layout_form_currency_left ), floatval( $item[ 'better_payment_amount_val' ] ), esc_html( $layout_form_currency_right ) ); ?></label>
                </div>
            
            <?php
            elseif ( 'v2' === $defauls['version'] ) :
            ?>
                <label class="payment-amount">
                    <input type="radio" value="<?php echo floatval( $item[ 'better_payment_amount_val' ] ); ?>"
                        id="bp_payment_amount-<?php echo esc_attr($uid); ?>" class="bp-form__control bp-form_pay-radio "
                        name="primary_payment_amount_radio">
                    <span class="text"><?php printf( "%s%s%s", esc_html( $layout_form_currency_left ), floatval( $item[ 'better_payment_amount_val' ] ), esc_html( $layout_form_currency_right ) ); ?></span>
                </label>

            <?php
            endif;
        }
        ?>
        <!-- </div> -->
        <?php
    }

    public function render_attribute_default_text( $settings ) {
        $render_attribute_default_text = '';
        
        $items = [];
        $layout = ! empty( $settings["better_payment_form_layout"] ) ? $settings["better_payment_form_layout"] : '';
        
        switch( $layout ) {
            case 'layout-1':
            case 'layout-2':
            case 'layout-3':
                $items = ! empty( $settings['better_payment_form_fields'] ) ? $settings['better_payment_form_fields'] : [];
                break;
            
            case 'layout-4-pro':
                $items = ! empty( $settings['better_payment_form_fields_layout_4_5_6'] ) ? $settings['better_payment_form_fields_layout_4_5_6'] : [];
                break;
            case 'layout-5-pro':
                $items = ! empty( $settings['better_payment_form_fields_layout_4_5_6_desc'] ) ? $settings['better_payment_form_fields_layout_4_5_6_desc'] : [];
                break;
            case 'layout-6-pro':
                $items = ! empty( $settings['better_payment_form_fields_layout_4_5_6_woo'] ) ? $settings['better_payment_form_fields_layout_4_5_6_woo'] : [];
                break;

            default:
                break;
        }
        
        if (!empty($items)) :
            foreach ($items as $item) :
                $item_primary_field_type = !empty($item["better_payment_primary_field_type"]) ? $item["better_payment_primary_field_type"] : "";
                $is_payment_amount_field = 'primary_payment_amount' === $item_primary_field_type ? 1 : 0;
                
                if ( $is_payment_amount_field ) {
                    $render_attribute_default_dynamic = ! empty( $item["better_payment_field_name_default_dynamic_enable"] ) && 'yes' ===  $item["better_payment_field_name_default_dynamic_enable"] ? 1 : 0;
                    $render_attribute_default_text = $render_attribute_default_dynamic && ! empty( $_GET['payment_amount'] ) ? floatval($_GET['payment_amount']) : '';
                }
            endforeach;
        endif;

        return $render_attribute_default_text;
    }

    public function better_payment_free_layouts(){
        $layouts = apply_filters('better_payment/elementor/widget/layouts', [
            'layout-1' => 'Layout 1',
            'layout-2' => 'Layout 2',
            'layout-3' => 'Layout 3'
        ]);

        if ( ! $this->pro_enabled ) {
            $pro_banners = [
                'layout-4-pro' => 'Layout 4 (Pro)',
                'layout-5-pro' => 'Layout 5 (Pro)',
                'layout-6-pro' => 'Layout 6 (Pro)',
            ];

            $layouts = array_merge( $layouts, $pro_banners );
        }

        return $layouts;
    }

    public function get_paypal_unsupported_currencies_conditions() {
        $paypal_unsupported_currencies = $this->bp_unsupported_currencies('paypal');
        $conditions = [];
        foreach ($paypal_unsupported_currencies as $currency) {
            $conditions[] = [
                'name'     => 'better_payment_form_currency',
                'value'    => $currency,
            ];
        }
        return $conditions;
    }
}
