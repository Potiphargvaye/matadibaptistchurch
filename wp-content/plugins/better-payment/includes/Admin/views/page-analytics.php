<div class="better-payment">
    <div class="better-payment-analytics">
            <div class="analytics-page-content p-5 m-1">
                <div class="analytics-page-content-header">
                    <div class="columns">
                        <div class="column">
                            <div class="logo pb-4">
                                <a href="javascript:void(0)"><img src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/logo.svg'); ?>" alt="Better Payment logo"></a>
                            </div>
                        </div>
                        <div class="column is-3">
                            <p class="has-text-right">
                                <a title="<?php esc_attr_e('We are caching all data for 1 hour. To see the live data press this button!', 'better-payment') ?>" href="#" class="button mr-1 fix-common"><?php esc_html_e('Refresh Stats', 'better-payment'); ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="columns analytics-page-content-header-boxes">
                        <div class="column">
                            <div class="box">
                                <div class="header-box header-box-1 px-3 pt-3 pb-0">
                                    <div class="columns">
                                        <div class="column is-1">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="16" height="16" rx="5" fill="#735EF8" />
                                            </svg>
                                        </div>

                                        <div class="column is-11">
                                            <p class="is-size-6 analytics-total-transaction"><?php esc_html_e('Total Transactions', 'better-payment'); ?></p>
                                            <p class="is-size-3 analytics-total-transaction-amount"><?php printf('%s %s', esc_html( '$' ), esc_html( 0 ) ) ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="box">
                                <div class="header-box header-box-2 px-3 pt-3 pb-0">
                                    <div class="columns">
                                        <div class="column is-1">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="16" height="16" rx="5" fill="#0ECA86" />
                                            </svg>
                                        </div>

                                        <div class="column is-11">
                                            <p class="is-size-6 analytics-completed-transaction"><?php esc_html_e('Completed Transactions', 'better-payment'); ?></p>
                                            <p class="is-size-3 analytics-completed-transaction-amount"><?php printf('%s %s', esc_html( '$' ), esc_html( 0 ) ) ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="box">
                                <div class="header-box header-box-3 px-3 pt-3 pb-0">
                                    <div class="columns">
                                        <div class="column is-1">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="16" height="16" rx="5" fill="#FFDA15" />
                                            </svg>
                                        </div>

                                        <div class="column is-11">
                                            <p class="is-size-6 analytics-incomplete-transaction"><?php esc_html_e('Incomplete Transactions', 'better-payment'); ?></p>
                                            <p class="is-size-3 analytics-incomplete-transaction-amount"><?php printf('%s %s', esc_html( '$' ), esc_html( 0 ) ) ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="box">
                                <div class="header-box header-box-4 px-3 pt-3 pb-0">
                                    <div class="columns">
                                        <div class="column is-1">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="16" height="16" rx="5" fill="#FF0202" />
                                            </svg>
                                        </div>

                                        <div class="column is-11">
                                            <p class="is-size-6 analytics-refund-transaction"><?php esc_html_e('Refund Transactions', 'better-payment'); ?></p>
                                            <p class="is-size-3 analytics-refund-transaction-amount"><?php printf('%s %s', esc_html( '$' ), esc_html( 0 ) ) ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">

                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <p class="pt-6"><a class="width-100" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro"><img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/analytics-banner.jpg'); ?>" alt="analytics-banner"></a></p>
                        </div>
                    </div>
                </div>
                
            </div>
    </div>
</div>