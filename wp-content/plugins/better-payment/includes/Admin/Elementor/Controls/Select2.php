<?php

namespace Better_Payment\Lite\Admin\Elementor\Controls;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use \Elementor\Base_Data_Control;

class Select2 extends Base_Data_Control
{
    public function get_type()
    {
        return 'better-payment-select2';
    }

    public function enqueue()
    {
        wp_register_script('better-payment-select2', BETTER_PAYMENT_ASSETS . '/js/elementor/edit/better-payment-select2.min.js',
            ['jquery-elementor-select2'], '1.0.0', true);
        wp_localize_script(
            'better-payment-select2',
            'better_payment_select2_localize',
	        [
		        'ajaxurl'     => esc_url( admin_url( 'admin-ajax.php' ) ),
		        'search_text' => esc_html__( 'Search', 'better-payment' ),
		        'remove'      => __( 'Remove', 'better-payment' ),
		        'thumbnail'   => __( 'Image', 'better-payment' ),
		        'name'        => __( 'Title', 'better-payment' ),
		        'price'       => __( 'Price', 'better-payment' ),
		        'quantity'    => __( 'Quantity', 'better-payment' ),
		        'subtotal'    => __( 'Subtotal', 'better-payment' ),
	        ]
        );
        wp_enqueue_script('better-payment-select2');
    }

    protected function get_default_settings()
    {
        return [
            'multiple' => false,
            'source_name' => 'post_type',
            'source_type' => 'post',
        ];
    }

    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="ea-select2" data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <#
        ( function( $ ) {
        $( document.body ).trigger( 'better_payment_select2_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
        }( jQuery ) );
        #>
        <?php
    }
}
