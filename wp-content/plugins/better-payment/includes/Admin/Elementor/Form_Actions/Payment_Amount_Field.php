<?php

namespace Better_Payment\Lite\Admin\Elementor\Form_Actions;

use Better_Payment\Lite\Admin\DB;
use Better_Payment\Lite\Classes\Handler;
use Elementor\Widget_Base;
use ElementorPro\Modules\Forms\Classes;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Fields\Field_Base;
use ElementorPro\Plugin;
use Better_Payment\Lite\Traits\Helper as TraitsHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Payment Amount field class
 *
 * @since 0.0.1
 */
class Payment_Amount_Field extends Field_Base {
	use TraitsHelper;

	public function get_type() {
		return 'payment_amount';
	}

	public function get_name() {
		return __( 'Payment Amount', 'better-payment' );
	}

	public function render( $item, $item_index, $form ) {
		$settings = $form->get_settings_for_display();
		
		$show_input_field = $this->is_bp_input_field_enable( $settings, $item );
		$show_amount_list = $this->is_bp_show_amount_list_enable( $settings, $item );

		$item['custom_id'] = $this->get_type();

		$form->add_render_attribute( 'input' . $item['custom_id'], [
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
		] );

		if ( $item['bp_placeholder'] ) {
			$form->add_render_attribute( 'input' . $item['custom_id'], 'placeholder', $item['bp_placeholder'] );
		}

		$form->add_render_attribute( 'input' . $item['custom_id'], 'required', 'required' );
		$form->add_render_attribute( 'input' . $item['custom_id'], 'aria-required', 'true' );

		if ( isset( $item['bp_field_min'] ) ) {
			$form->add_render_attribute( 'input' . $item['custom_id'], 'min', esc_attr( $item['bp_field_min'] ) );
		}
		
		if ( isset( $item['bp_field_max'] ) ) {
			$form->add_render_attribute( 'input' . $item['custom_id'], 'max', esc_attr( $item['bp_field_max'] ) );
		}
		
		if ( isset( $item['bp_field_default'] ) ) {
			$form->add_render_attribute( 'input' . $item['custom_id'], 'value', esc_attr( $item['bp_field_default'] ) );
		}

		$better_payment_amount_readonly 		= ! empty( $item['bp_field_default_fixed'] ) ? 'readonly' : '';
		$better_payment_form_currency 			= $this->get_better_payment_form_currency($form);
        $better_payment_form_currency_symbol 	= $this->get_currency_symbol( $better_payment_form_currency );
        
		$bp_payment_amount_input = '<input type="number" class="elementor-field elementor-size-sm elementor-field-textual bp-elementor-field-textual-amount" ' . $form->get_render_attribute_string( 'input' . $item['custom_id'] ) . '>';

		$bp_payment_amount_html = "<div class='bp-input-group'>
									<div class='bp-input-group-prepend'>
										<div class='bp-input-group-text' title='$better_payment_form_currency'>$better_payment_form_currency_symbol</div>
									</div>
									$bp_payment_amount_input
								</div>";
		
		$bp_payment_amount_html_v2 = "<div class='better-payment is-full-width'>";

		if( $show_amount_list ){
			$bp_payment_amount_html_v2 .= $this->render_amount_element( $settings );
		}

		if( $show_input_field ){
			$bp_payment_amount_html_v2 .= "
				<div class='better-payment-field-advanced-layout field-primary_payment_amount elementor-repeater-item-".$this->get_attribute_id( $item )."'>
					<div class='control has-icons-left'>
						<input class='input is-medium required bp-custom-payment-amount bp-custom-payment-amount-el-integration' type='number' placeholder='" . esc_attr( $item['bp_placeholder'] ) . "' name='" . esc_attr( $this->get_attribute_name( $item ) ) . "' required='required' min='" . esc_attr( $item['bp_field_min'] ) . "' max='" . esc_attr( $item['bp_field_max'] ) . "' value='" . esc_attr( $item['bp_field_default'] ) . "' " . esc_attr( $better_payment_amount_readonly ) . " >
						<span class='icon is-medium is-left'>
							<span class='bp-currency-symbol'>" . esc_html( $better_payment_form_currency_symbol ) . "</span>
						</span>
					</div>
				</div>
			";
		}

		$bp_payment_amount_html_v2 .= "</div>";
		

		$allowed_html = array(
			'a'      => array(
				'id'  => array(),
				'class'  => array(),
				'href'  => array(),
				'title' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'div' => array(
				'class'  => array(),
				'id'  => array(),
				'title' => array(),
			),
			'input' => array(
				'id' => array(),
				'class' => array(),
				'name' => array(),
				'type'  => array(),
				'placeholder' => array(),
				'aria-required' => array(),
				'required' => array(),
				'min' => array(),
				'max' => array(),
				'value' => array(),
				'readonly' => array(),
			),
			'span' => array(
				'class'  => array(),
				'id'  => array(),
				'title' => array(),
			),
			'label' => array(
				'for' => array(),
			),

		);

		echo wp_kses($bp_payment_amount_html_v2, $allowed_html) ;
	}

	/**
	 * Check if payment amount field is enabled
	 *
	 * @since 1.0.0
	 */
	public function is_bp_fields_enable( $settings ){
		return ! empty( $settings['better_payment_payment_amount_enable'] ) && 'yes' === $settings['better_payment_payment_amount_enable'];
	}

	/**
	 * Check item field type (input field / amount list / both)
	 *
	 * @since 1.0.0
	 */
	public function is_bp_input_field_enable( $settings, $item ){
		if( ! $this->is_bp_fields_enable( $settings ) ){
			return false;
		}

		return ! empty( $item['bp_field_type'] ) && ( 'input-field' === $item['bp_field_type'] || 'both' === $item['bp_field_type'] );
	}
	
	/**
	 * Check item field type (input field / amount list / both)
	 *
	 * @since 1.0.0
	 */
	public function is_bp_show_amount_list_enable( $settings, $item ){
		if( ! $this->is_bp_fields_enable( $settings ) ){
			return false;
		}

		$is_show_amount_list_enable = ! empty( $settings['better_payment_show_amount_list_enable'] ) && 'yes' === $settings['better_payment_show_amount_list_enable'];
		
		return $is_show_amount_list_enable && ( ! empty( $item['bp_field_type'] ) ) && ( 'amount-list' === $item['bp_field_type'] || 'both' === $item['bp_field_type'] );
	}

	/**
     * Render amount element
     *
     * @since 1.0.0
     */
    public function render_amount_element( $settings ) {
		if( empty($settings[ 'better_payment_show_amount_list_items' ] ) ){
			return;
		}

        $global_settings	= DB::get_settings();
        $currency			= $global_settings['better_payment_settings_general_general_currency'];
		$currency_alignment = 'left';

		if ( in_array( 'paypal', $settings['submit_actions'] ) ) {
			$currency = ! empty( $settings['better_payment_form_paypal_currency'] ) ? $settings['better_payment_form_paypal_currency'] : $currency;
			$currency_alignment = ! empty( $settings['better_payment_form_currency_alignment_paypal'] ) ? $settings['better_payment_form_currency_alignment_paypal'] : $currency_alignment;
		} elseif ( in_array( 'stripe', $settings['submit_actions'] ) ) {
			$currency = ! empty( $settings['better_payment_form_stripe_currency'] ) ? $settings['better_payment_form_stripe_currency'] : $currency;
			$currency_alignment = ! empty( $settings['better_payment_form_currency_alignment_stripe'] ) ? $settings['better_payment_form_currency_alignment_stripe'] : $currency_alignment;
		} elseif ( in_array( 'paystack', $settings['submit_actions'] ) ) {
			$currency = ! empty( $settings['better_payment_form_paystack_currency'] ) ? $settings['better_payment_form_paystack_currency'] : $currency;
			$currency_alignment = ! empty( $settings['better_payment_form_currency_alignment_paystack'] ) ? $settings['better_payment_form_currency_alignment_paystack'] : $currency_alignment;
		}

		$currency_symbol 		= $this->get_currency_symbol( esc_html( $currency ) );
		$currency_symbol_left	= 'left'    === $currency_alignment ? $currency_symbol : '';
        $currency_symbol_right	= 'right'   === $currency_alignment ? $currency_symbol : '' ;

		ob_start();
		?>
		<div class="payment-form-layout">
			<div class="bp-payment-amount-wrap">
				<?php 
				foreach ( $settings[ 'better_payment_show_amount_list_items' ] as $item ) {
					$uid = uniqid();

					?>
					<div class="bp-form__group pb-3">
						<input type="radio" value="<?php echo floatval( $item[ 'better_payment_amount_val' ] ); ?>"
							id="bp_payment_amount-<?php echo esc_attr($uid); ?>" class="bp-form__control bp-form_pay-radio "
							name="form_fields[primary_payment_amount_radio]">
						<label for="bp_payment_amount-<?php echo esc_attr($uid); ?>"><?php printf( "%s%s%s", esc_html( $currency_symbol_left ), floatval( $item['better_payment_amount_val'] ), esc_html( $currency_symbol_right ) ); ?></label>
					</div>
					<?php
				}
				?>
			</div>
		 </div>
		<?php 
		return ob_get_clean();
    }

	/**
	 * @param Widget_Base $widget
	 */
	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}
		
		$field_controls = [
			'bp_field_type' => [
				'name' => 'bp_field_type',
				'label' => __( 'Field Type', 'better-payment' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'both' => esc_html__( 'Both', 'better-payment' ),
					'input-field' => esc_html__( 'Input Field', 'better-payment' ),
					'amount-list' => esc_html__( 'Amount List', 'better-payment' ),
				],
				'default' => 'both',
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
				'separator' => 'before',
			],
			'bp_payment_field_usage_notice' => [
				'name' => 'bp_payment_field_usage_notice',
				'raw' => __( 'Don\'t forget to enable the <strong>Payment Amount</strong> (& Show Amount List) field from Better Payment Section below', 'better-payment' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'bp_placeholder' => [
				'name' => 'bp_placeholder',
				'label' => __( 'Placeholder', 'better-payment' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'bp_field_min' => [
				'name' => 'bp_field_min',
				'label' => __( 'Min. Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'bp_field_max' => [
				'name' => 'bp_field_max',
				'label' => __( 'Max. Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'bp_field_default' => [
				'name' => 'bp_field_default',
				'label' => __( 'Default Value', 'better-payment' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'bp_field_default_fixed' => [
				'name' => 'bp_field_default_fixed',
				'label' => __( 'Readonly', 'better-payment' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];
		
		foreach ( $control_data['fields'] as $index => $field ) {
			if ( 'required' === $field['name'] ) {
				$control_data['fields'][ $index ]['conditions']['terms'][] = [
					'name' => 'field_type',
					'operator' => '!in',
					'value' => [
						'payment_amount',
					],
				];
			}
		}
		
		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		
		$widget->update_control( 'form_fields', $control_data );
	}

	public function validation( $field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler ) {

		if ( ! empty( $field['bp_field_max'] ) && $field['bp_field_max'] < (int) $field['value'] ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be less than or equal to %s', 'better-payment' ), esc_html( $field['bp_field_max'] ) ) );
		}

		if ( ! empty( $field['bp_field_min'] ) && $field['bp_field_min'] > (int) $field['value'] ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be greater than or equal %s', 'better-payment' ), esc_html( $field['bp_field_min'] ) ) );
		}
	}

	public function sanitize_field( $value, $field ) {
		return intval( $value );
	}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['custom_id']}]";
	}

	public function get_attribute_id( $item ) {
		return 'form-field-' . $item['custom_id'];
	}

	public function get_better_payment_form_currency($form){
        $instance = $form->get_settings_for_display();
        $submit_actions = $instance['submit_actions'];
        
        $better_payment_global_currency = DB::get_settings('better_payment_settings_general_general_currency'); //USD
        $better_payment_global_currency = !empty($better_payment_global_currency) ? esc_html( $better_payment_global_currency ) : 'USD';
        
        if(in_array('paypal', $submit_actions)){
            $better_payment_form_currency = isset($instance['better_payment_form_paypal_currency']) ? esc_html($instance['better_payment_form_paypal_currency']) : esc_html($better_payment_global_currency);            
        } else if(in_array('paystack', $submit_actions)){
			$better_payment_form_currency = isset($instance['better_payment_form_paystack_currency']) ? esc_html($instance['better_payment_form_paystack_currency']) : esc_html($better_payment_global_currency);
		}else {
			$better_payment_form_currency = isset($instance['better_payment_form_stripe_currency']) ? esc_html($instance['better_payment_form_stripe_currency']) : esc_html($better_payment_global_currency);
        }
        
        return $better_payment_form_currency;
    }
}
