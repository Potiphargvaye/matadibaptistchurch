<?php

namespace Better_Payment\Lite\Admin\Elementor\Form_Actions;

use Better_Payment\Lite\Admin\DB;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Payment Amount integration class
 *
 * @since 0.0.1
 */
class Payment_Amount_Integration extends Action_Base {
    private $better_payment_global_settings = [];

    public function get_name() {
        return '';
    }

    public function get_label() {
        return __( 'Better Payment', 'better-payment' );
    }

    /**
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section( $widget ) {
        $this->better_payment_global_settings = DB::get_settings();

        $widget->start_controls_section(
            'section_better_payment_payment_amount',
            [
                'label'     => __( 'Better Payment', 'better-payment' ),
                'condition' => [
                ],
            ]
        );

        $widget->add_control(
			'better_payment_payment_amount_enable_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Don\'t forget to add PayPal or Stripe on <strong>Actions After Submit</strong>', 'better-payment' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'better_payment_payment_amount_enable',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'better_payment_show_amount_list_enable',
                            'value' => 'yes',
                        ],
                    ],
                ],
			]
		);
        
        $widget->add_control(
            'better_payment_payment_amount_enable',
            [
                'label'        => __( 'Payment Amount Field', 'better-payment' ),
                'description'  => __( 'We add an extra field type <b>Payment Amount</b> which offers you to accept payment via Paypal and Stripe. Disable it if you want to hide the field type.<br><br>', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
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

        $widget->add_control(
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

        $widget->add_control(
            'better_payment_show_amount_list_enable',
            [
                'label'        => __( 'Show Amount List', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'   => [
                    'better_payment_payment_amount_enable' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $widget->add_control(
			'better_payment_show_amount_list_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Form Fields => Payment Amount => <b>Field Type</b> helps to show Amount List with or without Input field.', 'better-payment' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'   => [
                    'better_payment_payment_amount_enable'      => 'yes',
                    'better_payment_show_amount_list_enable'    => 'yes',
                ],
			]
		);

        $widget->add_control(
            'better_payment_show_amount_list_items',
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
                    'better_payment_payment_amount_enable' => 'yes',
                    'better_payment_show_amount_list_enable' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_payment_amount_style',
            [
                'label'        => __( 'Field Style', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition' => [
                    'better_payment_payment_amount_enable' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $widget->add_control(
			'better_payment_field_text_color',
			[
				'label' => esc_html__( 'Text Color', 'better-payment' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field.bp-elementor-field-textual-amount' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

		$widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'better_payment_field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field.bp-elementor-field-textual-amount',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

		$widget->add_control(
			'better_payment_field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'better-payment' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field.bp-elementor-field-textual-amount' => 'background-color: {{VALUE}};',
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

		$widget->add_control(
			'better_payment_field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'better-payment' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group.elementor-field-type-payment_amount .bp-input-group' => 'border-color: {{VALUE}};border-style:solid;',
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

		$widget->add_control(
			'better_payment_field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'better-payment' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group.elementor-field-type-payment_amount .bp-input-group' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

		$widget->add_control(
			'better_payment_field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'better-payment' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group.elementor-field-type-payment_amount .bp-input-group' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'better_payment_payment_amount_style' => 'yes',
                ],
			]
		);

        $widget->add_control(
            'better_payment_amount_list_style',
            [
                'label'        => __( 'Amount List Style', 'better-payment' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'better-payment' ),
                'label_off'    => __( 'No', 'better-payment' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition' => [
                    'better_payment_payment_amount_enable' => 'yes',
                    'better_payment_show_amount_list_enable' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $widget->add_responsive_control(
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
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_responsive_control(
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
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_responsive_control(
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
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->start_controls_tabs( 'better_payment_form_amount_tabs_button_style' );

        $widget->start_controls_tab(
            'better_payment_form_fields_amount_normal',
            [
                'label' => __( 'Normal', 'better-payment' ),
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_form_fields_amount_normal_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_form_fields_amount_normal_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'better_payment_form_fields_amount_selected',
            [
                'label' => __( 'Selected', 'better-payment' ),
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_form_fields_amount_selected_bg',
            [
                'label'     => __( 'Background Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group input[type="radio"].bp-form__control:checked ~ label' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_form_fields_amount_selected_text_color',
            [
                'label'     => __( 'Text Color', 'better-payment' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group input[type="radio"].bp-form__control:checked ~ label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'better_payment_form_fields_amount_border',
                'label'       => __( 'Border', 'better-payment' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label',
                'separator'   => 'before',
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'better_payment_form_fields_amount_border_radius',
            [
                'label'      => __( 'Border Radius', 'better-payment' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'better_payment_form_fields_amount_typography',
                'label'     => __( 'Typography', 'better-payment' ),
                'selector'  => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label',
                'separator' => 'before',
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'better_payment_form_fields_amount_box_shadow',
                'selector'  => '{{WRAPPER}} .payment-form-layout .bp-payment-amount-wrap .bp-form__group label',
                'condition' => [
                    'better_payment_amount_list_style' => 'yes',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    /**
     * @param array $element
     * @return array
     */
    public function on_export( $element ) {
        unset(
            $element[ 'settings' ][ 'better_payment_payment_amount_enable' ]
        );

        return $element;
    }

    /**
     * @param Form_Record $record
     * @param Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {
        //Silence is golden!
    //     wp_enqueue_style( 'better-payment-el' );
    //     wp_enqueue_style( 'bp-icon-front' );
    //     wp_enqueue_style( 'better-payment-style' );
    //     wp_enqueue_style( 'better-payment-common-style' );
    //     wp_enqueue_style( 'better-payment-admin-style' );
        
    //     wp_enqueue_script( 'better-payment-common-script' );
    //     wp_enqueue_script( 'better-payment' );
    }
}


