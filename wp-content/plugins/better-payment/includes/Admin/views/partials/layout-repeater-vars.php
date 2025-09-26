<?php
$item_primary_field_type = !empty($item["better_payment_primary_field_type"]) ? $item["better_payment_primary_field_type"] : "";

$is_primary_first_name_field = 'primary_first_name' === $item_primary_field_type ? 1 : 0;
$is_primary_last_name_field = 'primary_last_name' === $item_primary_field_type ? 1 : 0;
$is_primary_email_field = 'primary_email' === $item_primary_field_type ? 1 : 0;
$is_payment_amount_field = 'primary_payment_amount' === $item_primary_field_type ? 1 : 0;

$is_item_required = !empty($item["better_payment_field_name_required"]) && 'yes' === $item["better_payment_field_name_required"] ? 1 : 0;
$is_item_visible = !empty($item["better_payment_field_name_show"]) && 'yes' === $item["better_payment_field_name_show"] ? 1 : 0;
$required_class = $is_item_required ? ' required' : '';
$item_visible_class = $is_item_visible ? '' : ' is-hidden';
$required_placeholder = $is_item_required ? ' *' : '';

$render_attribute_label = sanitize_text_field( $item["better_payment_field_name_heading"] );
$render_attribute_name = $better_payment_helper_obj->titleToSnake($item["better_payment_field_name_heading"]);

$render_attribute_class = $required_class;
$render_attribute_class .= $item_visible_class;
$render_attribute_placeholder = ! empty($item["better_payment_field_name_placeholder"]) ? $item["better_payment_field_name_placeholder"] . $required_placeholder : $required_placeholder;
$render_attribute_type = !empty($item["better_payment_field_type"]) ? $item["better_payment_field_type"] : 'text';
$render_attribute_required = $is_item_required ? 'required' : '';

$render_attribute_icon = ('email' === $render_attribute_type) ? 'bp-icon bp-envelope' : 'bp-icon bp-user';
$layout_show_image = 0;

if (!empty($item['better_payment_field_icon']['library'])) {
    if ($item['better_payment_field_icon']['library'] == 'svg') {
        $layout_show_image = 1;
        $render_attribute_icon = $item['better_payment_field_icon']['value']['url'];
    } else {
        $render_attribute_icon = $item['better_payment_field_icon']['value'];
    }
}

$render_attribute_min = '';
$render_attribute_max = '';
$render_attribute_default = '';
$render_attribute_default_fixed = '';
$render_attribute_default_dynamic = 0;

if ($is_payment_amount_field) {
    $payment_amount_field_exists = 1;
    $render_attribute_class .= ' bp-custom-payment-amount';
    $render_attribute_type = 'number';
    $render_attribute_required = 'required';
    $render_attribute_min           = ! empty( $item["better_payment_field_name_min"] ) ? intval( $item["better_payment_field_name_min"] ) : 1;
    $render_attribute_max           = ! empty( $item["better_payment_field_name_max"] ) ? intval( $item["better_payment_field_name_max"] ) : '';
    $render_attribute_default       = ! empty( $item["better_payment_field_name_default"] ) ? intval( $item["better_payment_field_name_default"] ) : '';
    $render_attribute_default_fixed = ! empty( $item["better_payment_field_name_default_fixed"] ) ? esc_html( 'readonly' ) : '';
    $required_placeholder = ' *';
    
    $render_attribute_default_dynamic = ! empty( $item["better_payment_field_name_default_dynamic_enable"] ) && 'yes' ===  $item["better_payment_field_name_default_dynamic_enable"] ? 1 : 0;
    $render_attribute_default = $render_attribute_default_dynamic && ! empty( $_GET['payment_amount'] ) ? floatval($_GET['payment_amount']) : $render_attribute_default;
}

if ($is_primary_email_field) {
    $render_attribute_type = 'email';
    $render_attribute_required = 'required';
    $render_attribute_placeholder = $item["better_payment_field_name_placeholder"] . ' *';
}

if (!empty($item_primary_field_type)) {
    if (
        $is_primary_first_name_field ||
        $is_primary_last_name_field ||
        $is_primary_email_field ||
        $is_payment_amount_field
    ) {
        $render_attribute_name = $item_primary_field_type;
    }
}

$payment_amount_field_class = '';

if ( $is_payment_amount_field ) {
    $payment_amount_field_class = $is_payment_type_woocommerce || $is_payment_recurring || $is_payment_split_payment || $render_attribute_default_dynamic ? 'is-hidden' : '';
}

$field_display_inline_class = ! empty( $item["better_payment_field_name_display_inline"] ) && 'inline-block' === $item["better_payment_field_name_display_inline"] ? ' field-display-inline ' : '';