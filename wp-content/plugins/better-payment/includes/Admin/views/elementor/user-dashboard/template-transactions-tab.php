<?php
use Better_Payment\Lite\Classes\Helper;

$transactions = $this->get_user_transactions();

?>

<!-- Need to replace with action -->
<?php if ( $bp_settings['header_show'] ) : ?>
<div class="better-payment-user-dashboard-header bp--db-header bp-dashboard-header flex items-center justify-center">
    <?php if ($bp_settings['sidebar_show']) : ?>
        <div class="bp-dashboard-hamburger padding-0 bp-visible-xs">
                <svg fill="none" viewBox="0 0 24 24" width="25" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m3 8c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1s-.4477 1-1 1h-16c-.55228 0-1-.44772-1-1zm0 4c0-.5523.44772-1 1-1h16c.5523 0 1 .4477 1 1s-.4477 1-1 1h-16c-.55228 0-1-.4477-1-1zm0 4c0-.5523.44772-1 1-1h8c.5523 0 1 .4477 1 1s-.4477 1-1 1h-8c-.55228 0-1-.4477-1-1z" fill="rgb(0,0,0)" fill-rule="evenodd"/></svg>
            </div>
    <?php endif; ?>
    <h2><?php esc_html_e($bp_settings['transaction_label'], 'better-payment'); ?></h2>
    <button class="primary-btn d-none"><a href="<?php echo the_permalink(); ?>">Refresh Stats</a></button>
</div>
<?php endif; ?>

<div class="bp--body-content">
    <div class="bp--table-main-wrapper">
        <div class="bp--table-wrapper transaction better-payment-user-dashboard-table">
            <div class="better-payment-user-dashboard-table-header bp--table-header bp-min_width-1300 flex justify-between gap-1">
                <?php if ( $bp_settings['transaction_table_name_show'] ) : ?>
                <div class="th user-name details">
                    <h5><?php esc_html_e($bp_settings['transaction_table_name_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_email_address_show'] ) : ?>
                <div class="th details email">
                    <h5><?php esc_html_e($bp_settings['transaction_table_email_address_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_amount_show'] ) : ?>
                <div class="th details amount">
                    <h5><?php esc_html_e($bp_settings['transaction_table_amount_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_payment_type_show'] ) : ?>
                <div class="th details">
                    <h5><?php esc_html_e($bp_settings['transaction_table_payment_type_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_transaction_id_show'] ) : ?>
                <div class="th details transaction">
                    <h5><?php esc_html_e($bp_settings['transaction_table_transaction_id_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_source_show'] ) : ?>
                <div class="th details flex justify-center">
                    <h5><?php esc_html_e($bp_settings['transaction_table_source_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_status_show'] ) : ?>
                <div class="th details flex justify-center">
                    <h5><?php esc_html_e($bp_settings['transaction_table_status_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_date_show'] ) : ?>
                <div class="th details flex justify-center">
                    <h5><?php esc_html_e($bp_settings['transaction_table_date_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transaction_table_action_show'] ) : ?>
                <div class="th details flex justify-center d-none">
                    <h5><?php esc_html_e($bp_settings['transaction_table_action_label'], 'better-payment') ?></h5>
                </div>
                <?php endif; ?>
            </div>

            <?php if ( is_array( $transactions ) && count($transactions)) : ?>
                <?php 
                $bp_txn_counter = 0; 
                $allowed_sources = ['paypal', 'stripe', 'paystack'];
                $td_source_image_url = BETTER_PAYMENT_ASSETS . '/img/stripe.svg';
                $td_source_image_alt = 'Stripe';
                
                foreach ($transactions as $bp_transaction) :
                    $bp_txn_counter++;
                    $bp_customer_info = maybe_unserialize($bp_transaction->customer_info); //obj 
                    $bp_form_fields_info = maybe_unserialize($bp_transaction->form_fields_info); //array 
                
                    $is_imported = ! empty( $bp_form_fields_info['is_imported'] ) && 1 === intval($bp_form_fields_info['is_imported']) ? 1 : 0;
                    $bp_transaction_customer_name = isset($bp_form_fields_info['primary_first_name']) ? sanitize_text_field($bp_form_fields_info['primary_first_name']) : '';
                    $bp_transaction_customer_name .= ' ';
                    $bp_transaction_customer_name .= isset($bp_form_fields_info['primary_last_name']) ? sanitize_text_field($bp_form_fields_info['primary_last_name']) : '';

                    //legacy
                    if( empty($bp_transaction_customer_name) || $bp_transaction_customer_name == ' ' ){
                        $bp_transaction_customer_name = isset($bp_form_fields_info['first_name']) ? sanitize_text_field($bp_form_fields_info['first_name']) : '';
                        $bp_transaction_customer_name .= ' ';
                        $bp_transaction_customer_name .= isset($bp_form_fields_info['last_name']) ? sanitize_text_field($bp_form_fields_info['last_name']) : '';
                    }
                
                    $bp_transaction_customer_email = isset($bp_form_fields_info['primary_email']) ? sanitize_text_field($bp_form_fields_info['primary_email']) : '';
                    //legacy
                    if( empty($bp_transaction_customer_email) ){
                        $bp_transaction_customer_email = isset($bp_form_fields_info['email']) ? sanitize_text_field($bp_form_fields_info['email']) : '';
                    }

                    $is_subscription = ! empty( $bp_form_fields_info['subscription_id'] ) ? 'Subscription' : 'One Time';
                    ?>
                    <div class="better-payment-user-dashboard-table-body bp--table-body bp-min_width-1300 flex items-center justify-between gap-1">
                        <?php 
                        if ( $bp_settings['transaction_table_name_show'] ) : ?>
                        <div class="td details user-name flex items-center gap-3">
                            <p><?php if ( $is_imported ) : ?> <span title="<?php esc_attr_e('Imported', 'better-payment'); ?>" class="bp-icon bp-imported imported-icon"></span> <?php endif; ?> <?php echo esc_html( $bp_transaction_customer_name ); ?> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_email_address_show'] ) : ?>
                        <div class="td details email flex items-center gap-3">
                            <p> <span id="bp_email_copy_clipboard_input_<?php echo esc_html($bp_txn_counter); ?>"><?php echo esc_html($bp_transaction_customer_email); ?></span> <span id="bp_email_copy_clipboard_<?php echo esc_attr($bp_txn_counter); ?>" class="bp-icon bp-copy-square bp-email-copy-clipboard" title="<?php esc_html_e('Copy', 'better-payment'); ?>" data-bp_txn_counter="<?php echo esc_attr($bp_txn_counter); ?>" ></span> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_amount_show'] ) : ?>
                        <div class="td details amount flex items-center gap-3">
                            <p> <?php echo esc_html($bp_transaction->currency) . ' ' . esc_html( floatval( $bp_transaction->amount ) ); ?> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_payment_type_show'] ) : ?>
                        <div class="td details flex items-center gap-3">
                            <p> <?php echo esc_html($is_subscription); ?> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_transaction_id_show'] ) : ?>
                        <div class="td details transaction flex items-center gap-3">
                            <?php $bp_transaction_id = sanitize_text_field($bp_transaction->transaction_id);  ?>
                            
                            <?php if( !empty($bp_transaction_id) ) : ?>
                                <p> <span id="bp_copy_clipboard_input_<?php echo esc_html($bp_txn_counter); ?>"><?php echo esc_html($bp_transaction_id); ?></span> <span id="bp_copy_clipboard_<?php echo esc_attr($bp_txn_counter); ?>" class="bp-icon bp-copy-square bp-copy-clipboard" title="<?php esc_html_e('Copy', 'better-payment'); ?>" data-bp_txn_counter="<?php echo esc_attr($bp_txn_counter); ?>" ></span> </p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_source_show'] ) : ?>
                        <div class="td details flex justify-center gap-3">
                            <?php
                            if( in_array( strtolower( $bp_transaction->source ), $allowed_sources ) ){
                                $td_source_image_url = strtolower( $bp_transaction->source ) == 'paypal' ? BETTER_PAYMENT_ASSETS . '/img/paypal.png' : BETTER_PAYMENT_ASSETS . "/img/{$bp_transaction->source}.svg";
                                $td_source_image_alt = strtolower( $bp_transaction->source ) == 'paypal' ? 'PayPal' : ucfirst( $bp_transaction->source );
                            }
                            ?>
                            <p> <img src="<?php echo esc_url($td_source_image_url) ?>" title="<?php echo esc_attr( $td_source_image_alt ); ?>" alt="<?php echo esc_attr( $td_source_image_alt ); ?>"> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_status_show'] ) : ?>
                        <div class="td details flex justify-center gap-3">
                            <?php
                            $bp_transaction_status_for_color = $bp_transaction->status ? sanitize_text_field($bp_transaction->status) : '';
                            $bp_helper_obj = new Helper();
                            $bp_transaction_status_color = $bp_helper_obj->get_color_by_transaction_status($bp_transaction_status_for_color, 'v2');
                            $td_status_btn_text_v2 = $bp_helper_obj->get_type_by_transaction_status($bp_transaction_status_for_color, 'v2');

                            $bp_transaction_status = $bp_transaction->status ? sanitize_text_field($bp_transaction->status) : esc_html__('N/A', 'better-payment');
                            ?>
                            <p class="" data-id="<?php echo esc_attr($bp_transaction->id) ?>"> <span style="color:#fff; padding:7px 15px; border-radius: 20px;background: <?php echo esc_attr($bp_transaction_status_color); ?>"><?php echo esc_html(ucfirst($td_status_btn_text_v2)); //$bp_transaction_status ?></span> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_date_show'] ) : ?>
                        <div class="td details flex justify-center gap-3">
                            <?php $bp_payment_date = sanitize_text_field($bp_transaction->payment_date); ?>
                            <?php $bp_payment_date = wp_date(get_option('date_format').' '.get_option('time_format'), strtotime($bp_payment_date)); ?>
                            <p> <?php echo esc_html($bp_payment_date); ?> </p>
                        </div>
                        <?php endif; ?>

                        <?php if ( $bp_settings['transaction_table_action_show'] ) : ?>
                        <div class="td details flex justify-center d-none">
                            <a href='<?php echo esc_url(admin_url("admin.php?page=better-payment-transactions&action=view&id={$bp_transaction->id}")); ?>' class="button button--sm view-button" data-id="<?php echo esc_attr($bp_transaction->id) ?>"><span title="<?php esc_attr_e('View', 'better-payment'); ?>" class="bp-icon bp-view font-bold"></span></a>
                        </div>
                        <?php endif; ?>

                    </div>
                    <?php $bp_txn_counter++; ?>
                <?php endforeach; ?>
            <?php else : ?>
            <div class="flex justify-center m-5">
                <p class="bp-no_subscription-text"><?php esc_html_e($bp_settings['no_items_label'], 'better-payment'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>