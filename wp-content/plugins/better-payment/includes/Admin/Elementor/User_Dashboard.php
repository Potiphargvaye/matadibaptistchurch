<?php

namespace Better_Payment\Lite\Admin\Elementor;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Better_Payment\Lite\Classes\Helper as ClassesHelper;
use Better_Payment\Lite\Traits\Helper;
use \Elementor\Controls_Manager as Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
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
class User_Dashboard extends Widget_Base {

    use Helper;

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
        return 'better-payment-user-dashboard';
    }

    public function get_title() {
        return esc_html__( 'User Dashboard', 'better-payment' );
    }

    public function get_icon() {
        return 'bp-icon bp-logo';
    }

    public function get_keywords() {
        return [
            'payment', 'better-payment' ,'paypal', 'stripe', 'sell', 'donate', 'transaction', 'online-transaction', 'paystack', 'user-dashboard', 'recurring-payments', 'subscriptions', 'better payment'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://betterpayment.co/docs/user-dashboard-using-better-payment/';
    }

    public function get_style_depends() { 
        return apply_filters( 'better_payment/elementor/user_dashboard/editor/get_style_depends', [ 'better-payment-el', 'bp-icon-front', 'better-payment-style', 'better-payment-common-style', 'better-payment-admin-style' ] );
    }
    
    public function get_script_depends() {
        return apply_filters( 'better_payment/elementor/user_dashboard/editor/get_script_depends', [ 'better-payment-common-script', 'better-payment' ] );
    }

    protected function register_controls() {
        $this->better_payment_global_settings = DB::get_settings();

        $better_payment_helper = new ClassesHelper();
        $better_payment_general_currency = $this->better_payment_global_settings['better_payment_settings_general_general_currency'];
        
        $better_payment_general_currency_woocommerce = $better_payment_general_currency;
        if( class_exists('woocommerce')  ) {
            $better_payment_general_currency_woocommerce = get_woocommerce_currency() ? get_woocommerce_currency() : $better_payment_general_currency_woocommerce;        
        }

        $this->start_controls_section(
            'better_payment_user_dashboard_layout_settings',
            [
                'label' => esc_html__( 'Layout', 'better-payment' ),
            ]
        );

        $this->add_control(
            'better_payment_user_dashboard_layout',
            [
                'label'      => esc_html__( 'Layout', 'better-payment' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'layout-1',
                'options'    => $this->better_payment_free_layouts(),
            ]
        );

        $this->add_control(
			'better_payment_user_dashboard_sidebar_show',
			[
				'label'        => __( 'Sidebar', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

        $this->add_control(
			'better_payment_user_dashboard_avatar_show',
			[
				'label'        => __( 'Avatar', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
                'condition'   => [
                    'better_payment_user_dashboard_sidebar_show' => 'yes'
                ],
			]
		);

        $this->add_control(
			'better_payment_user_dashboard_username_show',
			[
				'label'        => __( 'Username', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
                'condition'   => [
                    'better_payment_user_dashboard_sidebar_show' => 'yes'
                ],
			]
		);

        $this->add_control(
			'better_payment_user_dashboard_dashboard_show',
			[
				'label'        => __( 'Dashboard', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$this->add_control(
			'better_payment_user_dashboard_transactions_show',
			[
				'label'        => __( 'Transactions', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$this->add_control(
			'better_payment_user_dashboard_subscriptions_show',
			[
				'label'        => __( 'Subscriptions', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

        $this->add_control(
			'better_payment_user_dashboard_header_show',
			[
				'label'        => __( 'Header', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$this->get_dashboard_layout_settings( $this );
		
		$this->get_transaction_layout_settings( $this );

        do_action('better_payment/elementor/user_dashboard/layout_settings_header_after', $this);

        $this->end_controls_section();

        $this->start_controls_section(
            'better_payment_user_dashboard_content_settings',
            [
                'label' => esc_html__( 'Content', 'better-payment' ),
            ]
        );

        $this->add_control(
			'better_payment_user_dashboard_content_label',
			[
				'label' => esc_html__( 'Label', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

        $this->add_control( 
			'better_payment_user_dashboard_dashboard_label', 
			[
				'label'       => esc_html__( 'Dashboard', 'better-payment' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => esc_html__( 'Dashboard', 'better-payment' ),
				'ai' => [
					'active' => false,
				],
			] 
		);
		
		$this->add_control( 'better_payment_user_dashboard_transaction_label', [
			'label'       => esc_html__( 'Transactions', 'better-payment' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Transactions', 'better-payment' ),
			'ai' => [
				'active' => false,
			],
		] );
		
		$this->add_control( 'better_payment_user_dashboard_subscription_label', [
			'label'       => esc_html__( 'Subscriptions', 'better-payment' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Subscriptions', 'better-payment' ),
			'ai' => [
				'active' => false,
			],
		] );
        
        $this->add_control( 'better_payment_user_dashboard_refresh_stats_label', [
			'label'       => esc_html__( 'Refresh Stats', 'better-payment' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Refresh Stats', 'better-payment' ),
			'ai' => [
				'active' => false,
			],
		] );
		
		$this->add_control( 'better_payment_user_dashboard_no_items_label', [
			'label'       => esc_html__( 'No Items', 'better-payment' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'No records found!', 'better-payment' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->get_dashboard_content_settings( $this );

		$this->get_transaction_content_settings( $this );

        do_action('better_payment/elementor/user_dashboard/content_settings_no_items_after', $this);

        $this->end_controls_section();

        $this->user_dashboard_style();
    }

    public function user_dashboard_style() {
        $this->container_style();
        $this->sidebar_style();
        $this->header_style();
        $this->table_style();
    }

    public function container_style() {
        $this->start_controls_section(
			'better_payment_user_dashboard_container_style',
			[
				'label' => esc_html__( 'Container', 'better-payment' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_container_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				// 'default'   => [
				// 	'size' => 10,
				// ],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_container_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_container_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_container_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_container_normal_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-container',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_container_normal_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-container',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_container_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_container_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-container:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_container_hover_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-container:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_container_hover_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-container:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
    }

    public function sidebar_style() {
        $this->start_controls_section(
			'better_payment_user_dashboard_sidebar_style',
			[
				'label' => esc_html__( 'Sidebar', 'better-payment' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_payment_user_dashboard_sidebar_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_sidebar_container_label',
			[
				'label' => esc_html__( 'Sidebar Container', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_sidebar_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-sidebar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_sidebar_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-sidebar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_sidebar_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-sidebar' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_sidebar_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-sidebar' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_sidebar_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-sidebar',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_sidebar_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-sidebar',
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_sidebar_avatar_label',
			[
				'label' => esc_html__( 'Avatar', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_sidebar_avatar_size',
			[
				'label'      => __( 'Size', 'better-payment' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .bp--author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'better_payment_user_dashboard_sidebar_avatar_typography',
				'selector'  => '{{WRAPPER}} .bp--author h5',
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_sidebar_avatar_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_sidebar_avatar_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_sidebar_avatar_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--author h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_sidebar_avatar_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_sidebar_avatar_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--author h5:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'better_payment_user_dashboard_sidebar_menu_label',
			[
				'label' => esc_html__( 'Menu', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'better_payment_user_dashboard_sidebar_menu_typography',
				'selector'  => '{{WRAPPER}} .bp--sidebar-nav.active .bp--nav-text',
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_sidebar_menu_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_sidebar_menu_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_sidebar_menu_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--sidebar-nav.active .bp--nav-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_sidebar_menu_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_sidebar_menu_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--sidebar-nav.active .bp--nav-text:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'better_payment_user_dashboard_sidebar_icon_label',
			[
				'label' => esc_html__( 'Icon', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_sidebar_icon_size',
			[
				'label'      => __( 'Size', 'better-payment' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} span.bp--nav-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_sidebar_icon_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--sidebar-nav.active span.bp--nav-icon svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
    }
    
    public function header_style() {
        $this->start_controls_section(
			'better_payment_user_dashboard_header_style',
			[
				'label' => esc_html__( 'Header', 'better-payment' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_header_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_header_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-header' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'better_payment_user_dashboard_sidebar_header_typography',
				'selector'  => '{{WRAPPER}} .better-payment-user-dashboard-header h2',
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_header_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_header_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_header_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-header h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_header_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-header' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_header_normal_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-header',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_header_normal_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-header',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_header_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_header_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-header h2:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_header_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-header:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_header_hover_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-header:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_header_hover_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-header:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
    }
    
    public function table_style() {
        // Table
        $this->start_controls_section(
			'better_payment_user_dashboard_table_style',
			[
				'label' => esc_html__( 'Table', 'better-payment' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        // Table Container
        $this->add_control(
			'better_payment_user_dashboard_table_container_label',
			[
				'label' => esc_html__( 'Table Container', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_table_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_table_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_normal_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_normal_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_hover_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_hover_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

        // Table Header
        $this->add_control(
			'better_payment_user_dashboard_table_header_label',
			[
				'label' => esc_html__( 'Table Header', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'better_payment_user_dashboard_table_header_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_table_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_header_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'better_payment_user_dashboard_table_header_typography',
				'selector'  => '{{WRAPPER}} .bp--table-header .th, {{WRAPPER}} .bp--table-header .th h5',
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_header_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_header_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_header_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--table-header .th' 	=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-header .th h5' 	=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_header_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_header_normal_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_header_normal_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_header_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_header_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--table-header .th:hover' 	=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-header .th h5:hover' 	=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_header_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_header_hover_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_header_hover_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-header:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

        // Table Body
        $this->add_control(
			'better_payment_user_dashboard_table_body_label',
			[
				'label' => esc_html__( 'Table Body', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'better_payment_user_dashboard_table_body_margin',
			[
				'label'      => esc_html__( 'Margin', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_payment_user_dashboard_table_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'better-payment' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'better-payment' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'better_payment_user_dashboard_table_body_typography',
				'selector'  => '{{WRAPPER}} .bp--table-body .td h5, {{WRAPPER}} .bp--table-body .td h5 span, {{WRAPPER}} .bp--table-body .td p, {{WRAPPER}} button.active, {{WRAPPER}} button.cancel, {{WRAPPER}} .bp--table-body .td span, {{WRAPPER}} button.inactive',
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_body_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--table-body .td h5' 		=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-body .td h5 span'	=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-body .td p'			=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-body .td span'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_body_normal_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_body_normal_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bp--table-body .td h5:hover' 		=> 'color: {{VALUE}};',
					'{{WRAPPER}} .bp--table-body .td h5 span:hover'	=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_body_hover_border',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'better_payment_user_dashboard_table_body_hover_box_shadow',
				'selector' => '{{WRAPPER}} .better-payment-user-dashboard-table .better-payment-user-dashboard-table-body:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_label',
			[
				'label' => esc_html__( 'Table Body » Buttons', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);
		
		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_active_label',
			[
				'label' => esc_html__( 'Active Button', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_body_buttons_active_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_active_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_active_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.active'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_active_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.active' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_active_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_active_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.active:hover'	=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_active_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.active:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_inactive_label',
			[
				'label' => esc_html__( 'Inactive Button', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_body_buttons_inactive_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_inactive_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_inactive_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.inactive'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_inactive_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.inactive' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_inactive_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_inactive_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.inactive:hover'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_inactive_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.inactive:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_cancel_label',
			[
				'label' => esc_html__( 'Cancel Button', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'better_payment_user_dashboard_table_body_buttons_cancel_controls_tabs' );

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_cancel_control_normal', [
			'label' => esc_html__( 'Normal', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_cancel_normal_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.cancel'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_cancel_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.cancel' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'better_payment_user_dashboard_table_body_buttons_cancel_control_hover', [
			'label' => esc_html__( 'Hover', 'better-payment' ),
		] );

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_cancel_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.cancel:hover'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'better_payment_user_dashboard_table_body_buttons_cancel_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'better-payment' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.cancel:hover' => 'background: {{VALUE}};',
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

        $settings = $this->get_settings_for_display();
        $bp_settings = $this->get_bp_settings( $settings );

        $payment_field = "number" ;

        $response = Handler::manage_response( $settings, $this->get_id() );
        if ( $response ) {
            return false;
        }

        do_action('better_payment/elementor/editor/manage_response_webhook', $this, $settings );

        wp_enqueue_script( 'better-payment' );

        if( $this->pro_enabled ){
            wp_enqueue_script( 'better-payment-common-script' );
            wp_enqueue_style( 'better-payment-common-style' );
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

        $better_payment_form_layout = sanitize_text_field($settings[ 'better_payment_user_dashboard_layout' ]);
        $better_payment_form_layout = in_array($better_payment_form_layout, array_keys( $this->better_payment_free_layouts() ) ) ? $better_payment_form_layout : 'layout-1';

        $template_file = BETTER_PAYMENT_ADMIN_VIEWS_PATH . '/elementor/user-dashboard/' . $better_payment_form_layout . '.php';
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

    public function better_payment_free_layouts(){
        $layouts = apply_filters('better_payment/elementor/widget/user-dashboard/layouts', [
            'layout-1' => 'Layout 1',
            // 'layout-2' => 'Layout 2',
            // 'layout-3' => 'Layout 3'
        ]);

        if ( ! $this->pro_enabled ) {
            $pro_banners = [
                // 'layout-4-pro' => 'Layout 4 (Pro)',
                // 'layout-5-pro' => 'Layout 5 (Pro)',
                // 'layout-6-pro' => 'Layout 6 (Pro)',
            ];

            $layouts = array_merge( $layouts, $pro_banners );
        }

        return $layouts;
    }

    public function get_bp_settings( $settings = [] ) {
        $bp_settings = [];

        $bp_settings['sidebar_show']        = ! empty( $settings['better_payment_user_dashboard_sidebar_show']) && 'yes' === $settings['better_payment_user_dashboard_sidebar_show'];
        $bp_settings['avatar_show']         = $bp_settings['sidebar_show'] && ( ! empty( $settings['better_payment_user_dashboard_avatar_show']) && 'yes' === $settings['better_payment_user_dashboard_avatar_show'] );
        $bp_settings['username_show']       = $bp_settings['sidebar_show'] && ( ! empty( $settings['better_payment_user_dashboard_username_show']) && 'yes' === $settings['better_payment_user_dashboard_username_show'] );
        $bp_settings['dashboard_show']  	= ! empty( $settings['better_payment_user_dashboard_dashboard_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_show'];
        $bp_settings['transactions_show']  	= ! empty( $settings['better_payment_user_dashboard_transactions_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_show'];
        $bp_settings['subscriptions_show']  = ! empty( $settings['better_payment_user_dashboard_subscriptions_show']) && 'yes' === $settings['better_payment_user_dashboard_subscriptions_show'];
        $bp_settings['header_show']         = ! empty( $settings['better_payment_user_dashboard_header_show']) && 'yes' === $settings['better_payment_user_dashboard_header_show'];
        
        $bp_settings['dashboard_label']  	= ! empty( $settings['better_payment_user_dashboard_dashboard_label']) ? $settings['better_payment_user_dashboard_dashboard_label'] : 'Dashboard';
        $bp_settings['transaction_label']  	= ! empty( $settings['better_payment_user_dashboard_transaction_label']) ? $settings['better_payment_user_dashboard_transaction_label'] : 'Transactions';
        $bp_settings['subscription_label']  = ! empty( $settings['better_payment_user_dashboard_subscription_label']) ? $settings['better_payment_user_dashboard_subscription_label'] : 'Subscriptions';
        $bp_settings['refresh_stats_label'] = ! empty( $settings['better_payment_user_dashboard_refresh_stats_label']) ? $settings['better_payment_user_dashboard_refresh_stats_label'] : 'Refresh Stats';
        $bp_settings['no_items_label']  	= ! empty( $settings['better_payment_user_dashboard_no_items_label']) ? $settings['better_payment_user_dashboard_no_items_label'] : 'No subscriptions found!';
        
		// Dashboard
        $bp_settings['dashboard_transaction_summary_show'] = ! empty( $settings['better_payment_user_dashboard_dashboard_transaction_summary_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_transaction_summary_show'];
        $bp_settings['dashboard_analytics_report_show'] = ! empty( $settings['better_payment_user_dashboard_dashboard_analytics_report_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_analytics_report_show'];
        $bp_settings['dashboard_recent_transactions_show'] = ! empty( $settings['better_payment_user_dashboard_dashboard_recent_transactions_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_recent_transactions_show'];
        $bp_settings['dashboard_recurring_subscription_show'] = ! empty( $settings['better_payment_user_dashboard_dashboard_recurring_subscription_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_recurring_subscription_show'];
        $bp_settings['dashboard_split_subscription_show'] = ! empty( $settings['better_payment_user_dashboard_dashboard_split_subscription_show']) && 'yes' === $settings['better_payment_user_dashboard_dashboard_split_subscription_show'];
        
        $bp_settings['dashboard_total_amount_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_total_amount_label']) ? $settings['better_payment_user_dashboard_dashboard_total_amount_label'] : 'Total Amount';
        $bp_settings['dashboard_completed_amount_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_completed_amount_label']) ? $settings['better_payment_user_dashboard_dashboard_completed_amount_label'] : 'Completed Amount';
        $bp_settings['dashboard_incomplete_amount_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_incomplete_amount_label']) ? $settings['better_payment_user_dashboard_dashboard_incomplete_amount_label'] : 'Incomplete Amount';
        $bp_settings['dashboard_refunded_amount_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_refunded_amount_label']) ? $settings['better_payment_user_dashboard_dashboard_refunded_amount_label'] : 'Refunded Amount';
        $bp_settings['dashboard_view_all_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_view_all_label']) ? $settings['better_payment_user_dashboard_dashboard_view_all_label'] : 'View All';
        $bp_settings['dashboard_analytics_reports_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_analytics_reports_label']) ? $settings['better_payment_user_dashboard_dashboard_analytics_reports_label'] : 'Analytics Reports';
        $bp_settings['dashboard_recent_transactions_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_recent_transactions_label']) ? $settings['better_payment_user_dashboard_dashboard_recent_transactions_label'] : 'Recent Transactions';
        $bp_settings['dashboard_recurring_subscriptions_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_recurring_subscriptions_label']) ? $settings['better_payment_user_dashboard_dashboard_recurring_subscriptions_label'] : 'Recurring Subscriptions';
        $bp_settings['dashboard_split_subscriptions_label']  = ! empty( $settings['better_payment_user_dashboard_dashboard_split_subscriptions_label']) ? $settings['better_payment_user_dashboard_dashboard_split_subscriptions_label'] : 'Split Subscriptions';
        
		// Transaction
        $bp_settings['transaction_table_name_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_name_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_name_show'];
        $bp_settings['transaction_table_email_address_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_email_address_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_email_address_show'];
        $bp_settings['transaction_table_amount_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_amount_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_amount_show'];
		$bp_settings['transaction_table_payment_type_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_payment_type_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_payment_type_show'];
        $bp_settings['transaction_table_transaction_id_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_transaction_id_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_transaction_id_show'];
        $bp_settings['transaction_table_source_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_source_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_source_show'];
        $bp_settings['transaction_table_status_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_status_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_status_show'];
        $bp_settings['transaction_table_date_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_date_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_date_show'];
        $bp_settings['transaction_table_action_show'] = ! empty( $settings['better_payment_user_dashboard_transactions_list_action_show']) && 'yes' === $settings['better_payment_user_dashboard_transactions_list_action_show'];
        
        $bp_settings['transaction_table_name_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_name_label']) ? $settings['better_payment_user_dashboard_transactions_list_name_label'] : 'Name';
        $bp_settings['transaction_table_email_address_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_email_address_label']) ? $settings['better_payment_user_dashboard_transactions_list_email_address_label'] : 'Email Address';
        $bp_settings['transaction_table_amount_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_amount_label']) ? $settings['better_payment_user_dashboard_transactions_list_amount_label'] : 'Amount';
		$bp_settings['transaction_table_payment_type_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_payment_type_label']) ? $settings['better_payment_user_dashboard_transactions_list_payment_type_label'] : 'Payment Type';
        $bp_settings['transaction_table_transaction_id_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_transaction_id_label']) ? $settings['better_payment_user_dashboard_transactions_list_transaction_id_label'] : 'Transaction ID';
		$bp_settings['transaction_table_source_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_source_label']) ? $settings['better_payment_user_dashboard_transactions_list_source_label'] : 'Source';
        $bp_settings['transaction_table_status_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_status_label']) ? $settings['better_payment_user_dashboard_transactions_list_status_label'] : 'Status';
        $bp_settings['transaction_table_date_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_date_label']) ? $settings['better_payment_user_dashboard_transactions_list_date_label'] : 'Date';
        $bp_settings['transaction_table_action_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_action_label']) ? $settings['better_payment_user_dashboard_transactions_list_action_label'] : 'Action';
        // $bp_settings['transaction_table_status_active_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_status_active_label']) ? $settings['better_payment_user_dashboard_transactions_list_status_active_label'] : 'Active';
        // $bp_settings['transaction_table_status_inactive_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_status_inactive_label']) ? $settings['better_payment_user_dashboard_transactions_list_status_inactive_label'] : 'Inactive';
        // $bp_settings['transaction_table_action_cancel_label']  = ! empty( $settings['better_payment_user_dashboard_transactions_list_action_cancel_label']) ? $settings['better_payment_user_dashboard_transactions_list_action_cancel_label'] : 'Cancel';
		
        $bp_settings = apply_filters( 'better_payment/elementor/user_dashboard/bp_settings', $bp_settings, $settings );

        return $bp_settings;
    }

	public function get_dashboard_layout_settings( $widgetObj ) {
		$widgetObj->add_control(
			'better_payment_user_dashboard_layout_dashboard_label',
			[
				'label' => esc_html__( 'Dashboard', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        $widgetObj->add_control(
			'better_payment_user_dashboard_dashboard_transaction_summary_show',
			[
				'label'        => __( 'Transaction Summary', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$widgetObj->add_control(
			'better_payment_user_dashboard_dashboard_analytics_report_show',
			[
				'label'        => __( 'Analytics Report', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$widgetObj->add_control(
			'better_payment_user_dashboard_dashboard_recent_transactions_show',
			[
				'label'        => __( 'Recent Transactions', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widgetObj->add_control(
			'better_payment_user_dashboard_dashboard_recurring_subscription_show',
			[
				'label'        => __( 'Recurring Subscription', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$widgetObj->add_control(
			'better_payment_user_dashboard_dashboard_split_subscription_show',
			[
				'label'        => __( 'Split Subscription', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);	
	}

	public function get_dashboard_content_settings( $widgetObj ) {
		$widgetObj->add_control(
			'better_payment_user_dashboard_content_dashboard_label',
			[
				'label' => esc_html__( 'Dashboard', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_total_amount_label', 
            [
                'label'       => esc_html__( 'Total Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Total Amount', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_completed_amount_label', 
            [
                'label'       => esc_html__( 'Completed Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Completed Amount', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_incomplete_amount_label', 
            [
                'label'       => esc_html__( 'Incomplete Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Incomplete Amount', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_refunded_amount_label', 
            [
                'label'       => esc_html__( 'Refunded Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Refunded Amount', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_view_all_label', 
            [
                'label'       => esc_html__( 'View All', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'View All', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

        $widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_analytics_reports_label', 
            [
                'label'       => esc_html__( 'Analytics Reports', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Analytics Reports', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_recent_transactions_label', 
            [
                'label'       => esc_html__( 'Recent Transactions', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Recent Transactions', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_recurring_subscriptions_label', 
            [
                'label'       => esc_html__( 'Recurring Subscriptions', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Recurring Subscriptions', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
		
		$widgetObj->add_control( 
            'better_payment_user_dashboard_dashboard_split_subscriptions_label', 
            [
                'label'       => esc_html__( 'Split Subscriptions', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Split Subscriptions', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
	}
	
	public function get_transaction_layout_settings( $widgetObj ) {
		$widgetObj->add_control(
			'better_payment_user_dashboard_layout_transactions_list_label',
			[
				'label' => esc_html__( 'Transactions List', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        $widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_name_show',
			[
				'label'        => __( 'Name', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		
		$widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_email_address_show',
			[
				'label'        => __( 'Email Address', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_amount_show',
			[
				'label'        => __( 'Amount', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_payment_type_show',
			[
				'label'        => __( 'Payment Type', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_transaction_id_show',
			[
				'label'        => __( 'Transaction ID', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

        $widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_source_show',
			[
				'label'        => __( 'Source', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_status_show',
			[
				'label'        => __( 'Status', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

        $widgetObj->add_control(
			'better_payment_user_dashboard_transactions_list_date_show',
			[
				'label'        => __( 'Date', 'better-payment' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'better-payment' ),
				'label_off'    => __( 'Hide', 'better-payment' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

        // $widgetObj->add_control(
		// 	'better_payment_user_dashboard_transactions_list_action_show',
		// 	[
		// 		'label'        => __( 'Action', 'better-payment' ),
		// 		'type'         => Controls_Manager::SWITCHER,
		// 		'label_on'     => __( 'Show', 'better-payment' ),
		// 		'label_off'    => __( 'Hide', 'better-payment' ),
		// 		'return_value' => 'yes',
		// 		'default'      => 'yes',
		// 	]
		// );
	}

	public function get_transaction_content_settings( $widgetObj ) {
		$widgetObj->add_control(
			'better_payment_user_dashboard_content_transactions_list_label',
			[
				'label' => esc_html__( 'Transactions List', 'better-payment' ),
				'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        $widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_name_label', 
            [
                'label'       => esc_html__( 'Name', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Name', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

        $widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_email_address_label', 
            [
                'label'       => esc_html__( 'Email Address', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Email Address', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

		$widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_amount_label', 
            [
                'label'       => esc_html__( 'Amount', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Amount', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

		$widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_payment_type_label', 
            [
                'label'       => esc_html__( 'Payment Type', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Payment Type', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

		$widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_transaction_id_label', 
            [
                'label'       => esc_html__( 'Transaction ID', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Transaction ID', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

        $widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_source_label', 
            [
                'label'       => esc_html__( 'Source', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Source', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );

		$widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_status_label', 
            [
                'label'       => esc_html__( 'Status', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Status', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
        
        $widgetObj->add_control( 
            'better_payment_user_dashboard_transactions_list_date_label', 
            [
                'label'       => esc_html__( 'Date', 'better-payment' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Date', 'better-payment' ),
                'ai' => [
                    'active' => false,
                ],
	    	] 
        );
        
        // $widgetObj->add_control( 
        //     'better_payment_user_dashboard_transactions_list_action_label', 
        //     [
        //         'label'       => esc_html__( 'Action', 'better-payment' ),
        //         'type'        => Controls_Manager::TEXT,
        //         'label_block' => false,
        //         'default'     => esc_html__( 'Action', 'better-payment' ),
        //         'ai' => [
        //             'active' => false,
        //         ],
	    // 	]
        // );

        // $widgetObj->add_control( 
        //     'better_payment_user_dashboard_transactions_list_status_active_label', 
        //     [
        //         'label'       => esc_html__( 'Status » Active', 'better-payment' ),
        //         'type'        => Controls_Manager::TEXT,
        //         'label_block' => false,
        //         'default'     => esc_html__( 'Active', 'better-payment' ),
        //         'ai' => [
        //             'active' => false,
        //         ],
	    // 	]
        // );
        
        // $widgetObj->add_control( 
        //     'better_payment_user_dashboard_transactions_list_status_inactive_label', 
        //     [
        //         'label'       => esc_html__( 'Status » Inactive', 'better-payment' ),
        //         'type'        => Controls_Manager::TEXT,
        //         'label_block' => false,
        //         'default'     => esc_html__( 'Inactive', 'better-payment' ),
        //         'ai' => [
        //             'active' => false,
        //         ],
	    // 	]
        // );

        // $widgetObj->add_control(
        //     'better_payment_user_dashboard_transactions_list_action_cancel_label', 
        //     [
        //         'label'       => esc_html__( 'Action » Cancel', 'better-payment' ),
        //         'type'        => Controls_Manager::TEXT,
        //         'label_block' => false,
        //         'default'     => esc_html__( 'Cancel', 'better-payment' ),
        //         'ai' => [
        //             'active' => false,
        //         ],
	    // 	]
        // );
	}

	public function get_user_transactions( $email = '' ){
        $current_user = wp_get_current_user();

        if( empty($email) ) {
            $email = $current_user->user_email;
        }

        $transactions = DB::get_transactions_by_email( $email );

        return $transactions;
    }

}
