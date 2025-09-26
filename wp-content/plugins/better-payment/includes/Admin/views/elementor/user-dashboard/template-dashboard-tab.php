<?php

use Better_Payment\Lite\Admin\DB;

$current_user = wp_get_current_user();

if ( empty($email) ) {
    $email = $current_user->user_email;
}

$user_transactions = DB::get_transactions_by_email( $email );
$transactions_analytics = DB::get_transactions_analytics_dashboard( $user_transactions);

$total_transactions_count = isset($transactions_analytics['total_transactions_count']) ? $transactions_analytics['total_transactions_count'] : 0;
$completed_transactions_count = isset($transactions_analytics['completed_transactions_count']) ? $transactions_analytics['completed_transactions_count'] : 0;
$incomplete_transactions_count = isset($transactions_analytics['incomplete_transactions_count']) ? $transactions_analytics['incomplete_transactions_count'] : 0;
$refunded_transactions_count = isset($transactions_analytics['refunded_transactions_count']) ? $transactions_analytics['refunded_transactions_count'] : 0;

$total_transactions_amount = isset($transactions_analytics['total_transactions_amount']) ? number_format($transactions_analytics['total_transactions_amount'], 2) : 0;
$completed_transactions_amount = isset($transactions_analytics['completed_transactions_amount']) ? number_format($transactions_analytics['completed_transactions_amount'], 2) : 0;
$incomplete_transactions_amount = isset($transactions_analytics['incomplete_transactions_amount']) ? number_format($transactions_analytics['incomplete_transactions_amount'], 2) : 0;
$refunded_transactions_amount = isset($transactions_analytics['refunded_transactions_amount']) ? number_format($transactions_analytics['refunded_transactions_amount'], 2) : 0;

$transaction_amount_currency = isset($transaction_amount_currency) ? $transaction_amount_currency : '$';
?>

<!-- Need to replace with action -->
<?php if ($bp_settings['header_show']) : ?>
    <div class="better-payment-user-dashboard-header bp--db-header bp-dashboard-header flex items-center justify-center">
        <?php if ($bp_settings['sidebar_show']) : ?>
            <div class="bp-dashboard-hamburger padding-0 bp-visible-xs">
                <svg fill="none" viewBox="0 0 24 24" width="25" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m3 8c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1s-.4477 1-1 1h-16c-.55228 0-1-.44772-1-1zm0 4c0-.5523.44772-1 1-1h16c.5523 0 1 .4477 1 1s-.4477 1-1 1h-16c-.55228 0-1-.4477-1-1zm0 4c0-.5523.44772-1 1-1h8c.5523 0 1 .4477 1 1s-.4477 1-1 1h-8c-.55228 0-1-.4477-1-1z" fill="rgb(0,0,0)" fill-rule="evenodd"/></svg>
            </div>
        <?php endif; ?>
        <h2><?php esc_html_e($bp_settings['dashboard_label'], 'better-payment'); ?></h2>
        <button class="primary-btn bp-hidden-xs"><a href="<?php echo the_permalink(); ?>"><?php esc_html_e( $bp_settings['refresh_stats_label'], 'better-payment' ); ?></a></button>
    </div>
<?php endif; ?>

<div class="bp-dashboard_wrapper">
    <div class="bp-amount_wrapper">
        <?php if ($bp_settings['dashboard_transaction_summary_show']) : ?>
        <div class="bp-row">
            <div class="bp-col_4 bp-col">
                <div class="bp-amount">
                    <h4 class="mb-0 pb-0"><?php esc_html_e($bp_settings['dashboard_total_amount_label'], 'better-payment'); ?></h4>
                    <div class="bp-amount_price mb-0 pb-0">
                        <span><?php printf('%s%s', esc_html( $transaction_amount_currency ), esc_html( $total_transactions_amount ) ); ?></span>
                    </div>
                    <div class="bp-transaction">
                        <span class="bp-transaction_title">No of Transaction:</span>
                        <span class="bp-transaction_number"> <?php printf('%s', esc_html( $total_transactions_count ) ); ?></span>
                    </div>
                </div>
            </div>
            <div class="bp-col_4 bp-col">
                <div class="bp-amount">
                    <h4 class="mb-0 pb-0"><?php esc_html_e($bp_settings['dashboard_completed_amount_label'], 'better-payment'); ?></h4>
                    <div class="bp-amount_price mb-0 pb-0">
                        <span><?php printf('%s%s', esc_html( $transaction_amount_currency ), esc_html( $completed_transactions_amount ) ); ?></span>
                    </div>
                    <div class="bp-transaction">
                        <span class="bp-transaction_title">No of Transaction:</span>
                        <span class="bp-transaction_number"> <?php printf('%s', esc_html( $completed_transactions_count ) ); ?></span>
                    </div>
                </div>
            </div>
            <div class="bp-col_4 bp-col">
                <div class="bp-amount">
                    <h4 class="mb-0 pb-0"><?php esc_html_e($bp_settings['dashboard_incomplete_amount_label'], 'better-payment'); ?></h4>
                    <div class="bp-amount_price mb-0 pb-0">
                        <span><?php printf('%s%s', esc_html( $transaction_amount_currency ), esc_html( $incomplete_transactions_amount ) ); ?></span>
                    </div>
                    <div class="bp-transaction">
                        <span class="bp-transaction_title">No of Transaction:</span>
                        <span class="bp-transaction_number"> <?php printf('%s', esc_html( $incomplete_transactions_count ) ); ?></span>
                    </div>
                </div>
            </div>
            <div class="bp-col_4 bp-col">
                <div class="bp-amount">
                    <h4 class="mb-0 pb-0"><?php esc_html_e($bp_settings['dashboard_refunded_amount_label'], 'better-payment'); ?></h4>
                    <div class="bp-amount_price mb-0 pb-0">
                        <span><?php printf('%s%s', esc_html( $transaction_amount_currency ), esc_html( $refunded_transactions_amount ) ); ?></span>
                    </div>
                    <div class="bp-transaction">
                        <span class="bp-transaction_title">No of Transaction:</span>
                        <span class="bp-transaction_number"> <?php printf('%s', esc_html( $refunded_transactions_count ) ); ?></span>
                    </div>
                </div>
            </div>

        </div>
        <?php endif; ?>
    </div>

    <div class="bp-analytics_chart-wrapper">
        <div class="bp-row">
            <?php if ($bp_settings['dashboard_analytics_report_show']) : ?>
                    <?php if ( ( ! $this->pro_enabled ) && current_user_can('manage_options') ) : ?>
                    <div class="bp-col_10 bp-col">
                        <a class="bp-analytics_reports" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro" >
                            <img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/' . 'user-dashboard-analytics-reports-pro-banner.png'); ?>" alt="user-dashboard-analytics-reports-pro-banner">
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php do_action('better_payment/widget/user-dashboard/dashboard_tab_analytics_reports', $settings, $bp_settings); ?>
            <?php endif; ?>

            <?php if ($bp_settings['dashboard_recent_transactions_show']) : ?>
            <div class="bp-col_4 bp-col">
                <div class="bp-recent_box">
                    <div class="bp-recent_header flex justify-between items-center">
                        <div class="flex gap-2 items-center">
                            <span>
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.9586 2.16663H6.03361C5.06788 2.16663 4.58502 2.16663 4.19558 2.30213C3.45711 2.55909 2.87733 3.15595 2.62772 3.91618C2.49609 4.31708 2.49609 4.81417 2.49609 5.80834V17.4785C2.49609 18.1937 3.31692 18.5731 3.83617 18.098C4.14124 17.8188 4.60095 17.8188 4.90601 18.098L5.30859 18.4664C5.84325 18.9556 6.64894 18.9556 7.18359 18.4664C7.71825 17.9771 8.52394 17.9771 9.05859 18.4664C9.59325 18.9556 10.3989 18.9556 10.9336 18.4664C11.4682 17.9771 12.2739 17.9771 12.8086 18.4664C13.3432 18.9556 14.1489 18.9556 14.6836 18.4664L15.0862 18.098C15.3912 17.8188 15.8509 17.8188 16.156 18.098C16.6753 18.5731 17.4961 18.1937 17.4961 17.4785V5.80834C17.4961 4.81417 17.4961 4.31708 17.3645 3.91618C17.1149 3.15595 16.5351 2.55909 15.7966 2.30213C15.4072 2.16663 14.9243 2.16663 13.9586 2.16663Z"
                                        stroke="#475467" stroke-width="1.25" />
                                    <path d="M8.74609 9.66663L14.1628 9.66663" stroke="#475467"
                                        stroke-width="1.25" stroke-linecap="round" />
                                    <path d="M5.8291 9.66663H6.24577" stroke="#475467" stroke-width="1.25"
                                        stroke-linecap="round" />
                                    <path d="M5.8291 6.75H6.24577" stroke="#475467" stroke-width="1.25"
                                        stroke-linecap="round" />
                                    <path d="M5.8291 12.5834H6.24577" stroke="#475467" stroke-width="1.25"
                                        stroke-linecap="round" />
                                    <path d="M8.74609 6.75H14.1628" stroke="#475467" stroke-width="1.25"
                                        stroke-linecap="round" />
                                    <path d="M8.74609 12.5834H14.1628" stroke="#475467" stroke-width="1.25"
                                        stroke-linecap="round" />
                                </svg>

                            </span>
                            <h3 class="bp-recent_header-title"><?php esc_html_e($bp_settings['dashboard_recent_transactions_label'], 'better-payment'); ?></h3>
                        </div>

                        <a href="#" class="bp-view_all-btn is-hidden"><?php esc_html_e($bp_settings['dashboard_view_all_label'], 'better-payment'); ?></a>
                    </div>

                    <div class="bp-recent_body">
                        <div class="bp-th flex  items-center">
                            <div class="w-195">
                                <h5>Transaction</h5>
                            </div>

                            <div class="w-80">
                                <h5>Amount</h5>
                            </div>
                        </div>
                        <div class="bp-product_scroll">
                        <?php 
                            $bp_txn_counter = 0; 
                            $allowed_sources = ['paypal', 'stripe', 'paystack'];
                            $td_source_image_url = BETTER_PAYMENT_ASSETS . '/img/stripe.svg';
                            $td_source_image_alt = 'Stripe';
                        ?>
                        <?php if ( is_array( $user_transactions ) && count( $user_transactions ) ) : ?>
                            <?php foreach( $user_transactions as $user_transaction ) : ?>
                                <div class="bp-td flex items-center">
                                    <div class="td-product flex items-start w-195 flex-col">
                                        <div class="td-product_logo">
                                            <?php
                                            if( in_array( strtolower( $user_transaction->source ), $allowed_sources ) ){
                                                $image_url = strtolower( $user_transaction->source ) == 'paypal' ? BETTER_PAYMENT_ASSETS . '/img/paypal.png' : BETTER_PAYMENT_ASSETS . "/img/{$user_transaction->source}.svg";
                                                $image_alt = strtolower( $user_transaction->source ) == 'paypal' ? 'PayPal' : ucfirst( $user_transaction->source );
                                            }
                                            ?>
                                            <img src="<?php echo esc_url($image_url) ?>" title="<?php echo esc_attr( $image_alt ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
                                        </div>

                                        <div class="td-product_info">
                                            <h4 class="td-product_name">
                                                <!-- #TODO same id on transaction tab too. fix duplicate id issue  -->
                                                <!-- <?php //$user_transaction_id = sanitize_text_field($user_transaction->transaction_id);  ?>
                        
                                                <?php //if( !empty($user_transaction_id) ) : ?>
                                                    <span id="bp_copy_clipboard_input_<?php //echo esc_html($bp_txn_counter); ?>"><?php //echo esc_html($user_transaction_id); ?></span> <span id="bp_copy_clipboard_<?php //echo esc_attr($bp_txn_counter); ?>" class="bp-icon bp-copy-square bp-copy-clipboard" title="<?php //esc_html_e('Copy', 'better-payment'); ?>" data-bp_txn_counter="<?php //echo esc_attr($bp_txn_counter); ?>" ></span>
                                                <?php //endif; ?> -->

                                                <?php $payment_date = sanitize_text_field( $user_transaction->payment_date ); ?>
                                                <?php $payment_date = wp_date(get_option('date_format'), strtotime( $payment_date ) ); ?>
                                                <?php echo esc_html( $payment_date ); ?>
                                                <?php //echo esc_html( $user_transaction->status ); ?>
                                            </h4>
                                            <!-- <p class="td-details">
                                                <?php //$payment_date = sanitize_text_field( $user_transaction->payment_date ); ?>
                                                <?php //$payment_date = wp_date(get_option('date_format'), strtotime( $payment_date ) ); ?>
                                                <?php //echo esc_html( $payment_date ); ?>
                                            </p> -->
                                        </div>
                                    </div>

                                    <div class="td-product_price w-80">
                                        <span><?php echo esc_html($user_transaction->currency) . ' ' . esc_html( floatval( $user_transaction->amount ) ); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>

    <div class="bp-subscription_wrapper">
        <div class="bp-row">
            <?php if ($bp_settings['dashboard_recurring_subscription_show']) : ?>
                <?php if ( ( ! $this->pro_enabled ) && current_user_can('manage_options') ) : ?>
                <div class="bp-col_7 bp-col ">
                    <a class="width-100" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro">
                        <img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/' . 'user-dashboard-recurring-subscription-pro-banner.png'); ?>" alt="user-dashboard-recurring-subscriptions-pro-banner">
                    </a>
                </div>
                <?php endif; ?>
                
                <?php do_action('better_payment/widget/user-dashboard/dashboard_tab_recurring_subscriptions', $settings, $bp_settings); ?>
            <?php endif; ?>

            <?php if ($bp_settings['dashboard_split_subscription_show']) : ?>
                <?php if ( ( ! $this->pro_enabled ) && current_user_can('manage_options') ) : ?>
                <div class=" bp-col_7 bp-col">
                    <a class="width-100" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro">
                        <img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/' . 'user-dashboard-split-subscription-pro-banner.png'); ?>" alt="user-dashboard-split-subscriptions-pro-banner">
                    </a>
                </div>
                <?php endif; ?>
                
                <?php do_action('better_payment/widget/user-dashboard/dashboard_tab_split_subscriptions', $settings, $bp_settings); ?>
            <?php endif; ?>
        </div>
    </div>

</div>