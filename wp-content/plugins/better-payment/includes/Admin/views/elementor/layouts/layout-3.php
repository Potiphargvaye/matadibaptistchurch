<div class="better-payment">
    <div class="better-payment--wrapper">
        <div class="better-payment--container">
            <?php
            use Better_Payment\Lite\Classes\Helper;

            $better_payment_helper_obj = new Helper();
            
            include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/partials/layout-vars.php";

            $render_attribute_default_text = $this->render_attribute_default_text( $settings );
            ?>

            <form name="better-payment-form-<?php echo esc_attr( $widgetObj->get_id() ); ?>" data-better-payment="<?php echo esc_attr($layout_setting_meta); ?>" class="better-payment-form" id="better-payment-form-<?php echo esc_attr($widgetObj->get_id()); ?>" action="<?php echo esc_url($layout_action); ?>" method="post">
                <input type="hidden" name="better_payment_page_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
                <input type="hidden" name="better_payment_widget_id" value="<?php echo esc_attr( $widgetObj->get_id() ); ?>">

                <div class="payment-form-layout-3 payment-form-layout">
                    <div class="tile is-ancestor m-0">
                        <?php if ( $sidebar_show ) : ?>
                        <div class="tile is-parent dynamic-amount-section ">
                            <div class="tile is-child">
                                <div class="dynamic-amount-section-inner has-text-white p-6">
                                    <div class="pt-3 pb-3">
                                        <p class="bp-dynamic-amount-section-icon-wrap"><i class="bp-icon bp-logo-2 is-size-1-fix dynamic-amount-section-icon bp-dynamic-amount-section-icon"></i></p>
                                        <h4 class="is-size-5-fix pt-5 dynamic-amount-section-title bp-dynamic-amount-section-title"><?php esc_html_e($layout_form_transaction_details_heading, 'better-payment'); ?></h4>
                                        <p class="is-size-6-fix pt-3 dynamic-amount-section-subtitle bp-dynamic-amount-section-sub-title"><?php esc_html_e($layout_form_transaction_details_sub_heading, 'better-payment'); ?></p>
                                        <p class="is-size-6-fix pt-2 dynamic-amount-section-subtitle bp-dynamic-amount-section-amount">
                                            <?php echo wp_kses( __(sprintf("%s <a class=' %s ' href='%s'>%s</a>", $layout_form_transaction_details_product_title, $layout_dynamic_payment_hide_show ,$product_permalink, $product_name), 'better-payment'), $valid_html_tags ); ?><br>
                                            <?php echo wp_kses( __(sprintf("%s <span class='bp-transaction-details-amount-text'>%s</span>", $layout_form_transaction_details_amount_text, '' !== $product_price ? floatval($product_price) : $render_attribute_default_text), 'better-payment'), $valid_html_tags ); ?>
                                        </p>
                                        <h4 class="is-size-2-fix pt-5 dynamic-amount-section-amount bp-dynamic-amount-section-amount-summary"><?php echo wp_kses( __(sprintf('%s<span class="bp-transaction-details-amount-text">%s</span>%s', wp_kses( $layout_form_currency_left, $valid_html_tags ), '' !== $product_price ? floatval($product_price) : $render_attribute_default_text, wp_kses( $layout_form_currency_right, $valid_html_tags ) ), 'better-payment'), $valid_html_tags ); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="tile is-parent has-background-white-fix form-content-section <?php echo ( intval( $sidebar_show ) ? esc_attr('is-8') : esc_attr('is-12') ); ?> <?php esc_attr_e($layout_form_content_offset_class, 'better-payment'); ?>">
                            <div class="tile is-child">
                                <div class="form-content-section-inner p-6">
                                    <div class="pt-3 pb-3">
                                        <div class="form-content-section-fields">
                                            <div class="field-payment_method">
                                                <div class="control bp-radio-box columns">
                                                    <?php 
                                                        $bp_paypal_button_enable = !empty( $settings[ 'better_payment_form_paypal_enable' ] ) && 'yes' === $settings[ 'better_payment_form_paypal_enable' ]; 
                                                        $bp_stripe_button_enable = !empty( $settings[ 'better_payment_form_stripe_enable' ] ) && 'yes' === $settings[ 'better_payment_form_stripe_enable' ];
                                                        $bp_paystack_button_enable = !empty( $settings[ 'better_payment_form_paystack_enable' ] ) && 'yes' === $settings[ 'better_payment_form_paystack_enable' ];
                                                    ?>

                                                    <?php if( true === $bp_paypal_button_enable ) : ?>
                                                    <label class="radio mb-5 payment-method-checkbox active column has-text-centered payment-method-checkbox-paypal">
                                                        <input type="radio" name="payment_method" class="layout-payment-method-input layout-payment-method-paypal" checked>
                                                        <img src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/paypal.png'); ?>" alt="PayPal">
                                                    </label>
                                                    <?php endif; ?>

                                                    <?php if( true === $bp_stripe_button_enable ) : ?>
                                                    <label class="radio mb-5 payment-method-checkbox payment-method-checkbox-stripe <?php echo empty($settings['better_payment_form_paypal_enable']) ? esc_attr('active') : ''; ?> column has-text-centered">
                                                        <input type="radio" name="payment_method" class="layout-payment-method-input layout-payment-method-stripe" <?php echo empty($settings['better_payment_form_paypal_enable']) ? 'checked' : '' ?>>
                                                        <img src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/stripe.svg'); ?>" alt="Stripe">
                                                    </label>
                                                    <?php endif; ?>
                                                    
                                                    <?php if( true === $bp_paystack_button_enable ) : ?>
                                                    <label class="radio mb-5 payment-method-checkbox payment-method-checkbox-paystack <?php echo empty($settings['better_payment_form_paypal_enable']) && empty($settings['better_payment_form_stripe_enable']) ? esc_attr('active') : ''; ?> column has-text-centered">
                                                        <input type="radio" name="payment_method" class="layout-payment-method-input layout-payment-method-paystack" <?php echo empty($settings['better_payment_form_paypal_enable']) && empty($settings['better_payment_form_stripe_enable']) ? 'checked' : '' ?>>
                                                        <img src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/paystack.svg'); ?>" alt="Paystack">
                                                    </label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <?php 
                                            if ( !empty( $settings['better_payment_form_fields'] ) ) :
                                                foreach (  $settings['better_payment_form_fields'] as $item ) :
                                                    include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/partials/layout-repeater-vars.php";
                                                    
                                                    if ($is_payment_amount_field) : ?>
                                                        <div class="bp-payment-amount-wrap <?php esc_attr_e($layout_put_amount_field_hide_show, 'better-payment'); ?>">
                                                            <?php
                                                            if (!empty($settings['better_payment_show_amount_list']) && 'yes' === $settings['better_payment_show_amount_list']) {
                                                                $this->render_amount_element($settings);
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="better-payment-field-advanced-layout field-<?php echo esc_attr($render_attribute_name); ?> pt-5 elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> <?php echo esc_attr( $payment_amount_field_class ); ?> <?php echo esc_attr( $item_visible_class ); ?>">
                                                        <div class="control has-icons-left">
                                                            <input class="input is-medium 
                                                                    <?php echo esc_attr($render_attribute_class); ?>" type="<?php echo esc_attr($render_attribute_type); ?>" placeholder="<?php echo esc_attr($render_attribute_placeholder); ?>" name="<?php echo esc_attr($render_attribute_name); ?>" <?php if ($render_attribute_required) : ?> required="<?php echo esc_attr($render_attribute_required); ?>" <?php endif; ?> <?php if ($is_payment_amount_field) : ?> step="any" min="<?php echo esc_attr( $render_attribute_min ); ?>" max="<?php echo esc_attr( $render_attribute_max ); ?>" value="<?php echo $is_payment_type_woocommerce ? floatval($product_price) : esc_attr( $render_attribute_default ); ?>" <?php echo esc_attr( $render_attribute_default_fixed ); ?> <?php endif; ?>>
                                                            <span class="icon is-medium is-left">
                                                                <?php 
                                                                $show_payment_default_symbol = 0;
                                                                if ( $is_payment_amount_field ) {
                                                                    $show_payment_default_symbol = 'bp-icon bp-envelope' === $render_attribute_icon || 'bp-icon bp-user' === $render_attribute_icon;
                                                                    
                                                                    if( $show_payment_default_symbol ){
                                                                        ?>
                                                                        <span class="icon is-medium is-left">
                                                                            <?php printf('<span class="bp-currency-symbol">%s</span>', esc_html( $layout_form_currency_symbol ) ); ?>
                                                                        </span>
                                                                        <?php 
                                                                    }
                                                                } ?>

                                                                <?php if ($layout_show_image) : ?>
                                                                    <img src="<?php echo esc_url($render_attribute_icon); ?>" alt="Icon" width="20">
                                                                <?php elseif( ! $show_payment_default_symbol ) : ?>
                                                                    <i class="<?php echo esc_attr($render_attribute_icon); ?>"></i>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php 
                                                endforeach;
                                            endif;
                                            ?>

                                            <div class="pb-5"></div>

                                            <div class="field-process_payment_button pt-3">
                                                <div class="control">
                                                    <div class="payment__option">
                                                        <?php
                                                        if ( !empty($settings['better_payment_form_paypal_enable']) && 'yes' === $settings['better_payment_form_paypal_enable'] ) {
                                                            echo Better_Payment\Lite\Classes\Handler::paypal_button(esc_attr($widgetObj->get_id()), $settings);
                                                        }

                                                        if ( !empty($settings['better_payment_form_stripe_enable']) && 'yes' === $settings['better_payment_form_stripe_enable'] ) {
                                                            echo Better_Payment\Lite\Classes\Handler::stripe_button(esc_attr($widgetObj->get_id()), $settings);
                                                        }
                                                        
                                                        if ( !empty($settings['better_payment_form_paystack_enable']) && 'yes' === $settings['better_payment_form_paystack_enable'] ) {
                                                            echo Better_Payment\Lite\Classes\Handler::paystack_button(esc_attr($widgetObj->get_id()), $settings);
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>