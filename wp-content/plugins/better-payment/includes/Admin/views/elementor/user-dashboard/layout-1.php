<?php

$current_user = wp_get_current_user();
$username = '';

if ( empty($current_user->user_email) ) {
    return;
}

?>

<div class="better-payment">
    <div class="better-payment-user-dashboard-container bp--section-wrapper flex gap-6 min-h-full">
        <div class="bp-overlay"></div>
        <?php if ( $bp_settings['sidebar_show'] ) : ?>
        <div class="better-payment-user-dashboard-sidebar user-dashboard-sidebar bp--sidebar-wrapper bp-hidden-xs">
            <div class="bp--author">
                <?php 
                if ( $bp_settings['avatar_show'] ) :
                    if ( ($current_user instanceof WP_User) ) {
                        echo get_avatar( $current_user->user_email, 32 );
                        $username = $current_user->user_login;
                    }
                endif;
                ?>

                <?php if ( $bp_settings['username_show'] ) : ?>
                <h5 class="user-name"><?php echo esc_html( $username ); ?></h5>
                <?php endif; ?>
            </div>
            <div class="bp--sidebar-nav-list">
                <?php if ( $bp_settings['dashboard_show'] ) : ?>
                <div class="bp--sidebar-nav dashboard-tab active" data-tab="dashboard">
                    <span class="bp--nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_400_549)">
                                <path
                                    d="M4 5C4 4.73478 4.10536 4.48043 4.29289 4.29289C4.48043 4.10536 4.73478 4 5 4H9C9.26522 4 9.51957 4.10536 9.70711 4.29289C9.89464 4.48043 10 4.73478 10 5V9C10 9.26522 9.89464 9.51957 9.70711 9.70711C9.51957 9.89464 9.26522 10 9 10H5C4.73478 10 4.48043 9.89464 4.29289 9.70711C4.10536 9.51957 4 9.26522 4 9V5Z"
                                    stroke="#48506D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M4 15C4 14.7348 4.10536 14.4804 4.29289 14.2929C4.48043 14.1054 4.73478 14 5 14H9C9.26522 14 9.51957 14.1054 9.70711 14.2929C9.89464 14.4804 10 14.7348 10 15V19C10 19.2652 9.89464 19.5196 9.70711 19.7071C9.51957 19.8946 9.26522 20 9 20H5C4.73478 20 4.48043 19.8946 4.29289 19.7071C4.10536 19.5196 4 19.2652 4 19V15Z"
                                    stroke="#48506D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M14 15C14 14.7348 14.1054 14.4804 14.2929 14.2929C14.4804 14.1054 14.7348 14 15 14H19C19.2652 14 19.5196 14.1054 19.7071 14.2929C19.8946 14.4804 20 14.7348 20 15V19C20 19.2652 19.8946 19.5196 19.7071 19.7071C19.5196 19.8946 19.2652 20 19 20H15C14.7348 20 14.4804 19.8946 14.2929 19.7071C14.1054 19.5196 14 19.2652 14 19V15Z"
                                    stroke="#48506D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M14 7H20" stroke="#48506D" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M17 4V10" stroke="#48506D" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_400_549">
                                    <rect width="24" height="24" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <span class="bp--nav-text"><?php esc_html_e($bp_settings['dashboard_label'], 'better-payment'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['transactions_show'] ) : ?>
                <div class="bp--sidebar-nav transactions-tab <?php echo esc_attr( ! $bp_settings['dashboard_show'] ? 'active' : ''); ?>" data-tab="transactions">
                    <span class="bp--nav-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path 
                                fill-rule="evenodd" 
                                clip-rule="evenodd" 
                                d="M7.09878 1.24992C7.14683 1.24994 7.19559 1.24996 7.24508 1.24996H16.7551C16.8045 1.24996 16.8533 
                                    1.24994 16.9014 1.24992C17.9181 1.24947 18.6178 1.24917 19.2072 1.45422C20.3201 1.84149 21.1842 
                                    2.73714 21.5547 3.86546L20.8421 4.09942L21.5547 3.86546C21.7507 4.46258 21.7505 5.17242 21.7501 
                                    6.22642C21.7501 6.27359 21.7501 6.32145 21.7501 6.37002V20.3742C21.7501 21.8394 20.0231 22.7117 
                                    18.8857 21.6709C18.8062 21.5981 18.694 21.5981 18.6145 21.6709L18.1314 22.1129C17.2032 22.9623 
                                    15.7969 22.9623 14.8688 22.1129C14.5138 21.7881 13.9864 21.7881 13.6314 22.1129C12.7032 22.9623 
                                    11.2969 22.9623 10.3688 22.1129C10.0138 21.7881 9.48637 21.7881 9.13138 22.1129C8.20319 22.9623 
                                    6.79694 22.9623 5.86875 22.1129L5.38566 21.6709C5.30618 21.5981 5.19395 21.5981 5.11448 21.6709C3.97705 
                                    22.7117 2.25007 21.8394 2.25007 20.3742V6.37002C2.25007 6.32145 2.25005 6.2736 2.25003 6.22643C2.24965 
                                    5.17242 2.24939 4.46259 2.44545 3.86546C2.81591 2.73714 3.68002 1.84149 4.79298 1.45422C5.3823 1.24917 
                                    6.08203 1.24947 7.09878 1.24992ZM7.24508 2.74996C6.024 2.74996 5.6034 2.76045 5.28593 2.87091C4.62655 
                                    3.10035 4.09919 3.63716 3.8706 4.33338C3.75951 4.67171 3.75007 5.11784 3.75007 6.37002V20.3742C3.75007 
                                    20.4932 3.80999 20.566 3.88517 20.6007C3.92434 20.6189 3.96264 20.6235 3.99456 20.6193C4.0227 20.6155 
                                    4.05911 20.6034 4.10185 20.5643C4.75453 19.967 5.74561 19.967 6.39828 20.5643L6.88138 21.0063C7.23637 
                                    21.3312 7.76377 21.3312 8.11875 21.0063C9.04694 20.157 10.4532 20.157 11.3814 21.0063C11.7364 21.3312 
                                    12.2638 21.3312 12.6188 21.0063C13.5469 20.157 14.9532 20.157 15.8814 21.0063C16.2364 21.3312 16.7638 
                                    21.3312 17.1188 21.0063L17.6019 20.5643C18.2545 19.967 19.2456 19.967 19.8983 20.5643C19.941 20.6034 19.9774 
                                    20.6155 20.0056 20.6193C20.0375 20.6235 20.0758 20.6189 20.115 20.6007C20.1901 20.566 20.2501 20.4932 20.2501 
                                    20.3742V6.37002C20.2501 5.11784 20.2406 4.67171 20.1295 4.33338C19.9009 3.63716 19.3736 3.10035 18.7142 2.87091C18.3967 
                                    2.76045 17.9761 2.74996 16.7551 2.74996H7.24508ZM6.25007 7.49996C6.25007 7.08575 6.58585 6.74996 7.00007 6.74996H7.50007C7.91428 
                                    6.74996 8.25007 7.08575 8.25007 7.49996C8.25007 7.91417 7.91428 8.24996 7.50007 8.24996H7.00007C6.58585 8.24996 6.25007 7.91417 
                                    6.25007 7.49996ZM9.75007 7.49996C9.75007 7.08575 10.0859 6.74996 10.5001 6.74996H17.0001C17.4143 6.74996 17.7501 7.08575 17.7501 
                                    7.49996C17.7501 7.91417 17.4143 8.24996 17.0001 8.24996H10.5001C10.0859 8.24996 9.75007 7.91417 9.75007 7.49996ZM6.25007 11C6.25007 
                                    10.5857 6.58585 10.25 7.00007 10.25H7.50007C7.91428 10.25 8.25007 10.5857 8.25007 11C8.25007 11.4142 7.91428 11.75 7.50007 11.75H7.00007C6.58585 
                                    11.75 6.25007 11.4142 6.25007 11ZM9.75007 11C9.75007 10.5857 10.0859 10.25 10.5001 10.25H17.0001C17.4143 10.25 17.7501 10.5857 17.7501 11C17.7501 
                                    11.4142 17.4143 11.75 17.0001 11.75H10.5001C10.0859 11.75 9.75007 11.4142 9.75007 11ZM6.25007 14.5C6.25007 14.0857 6.58585 13.75 7.00007 13.75H7.50007C7.91428 
                                    13.75 8.25007 14.0857 8.25007 14.5C8.25007 14.9142 7.91428 15.25 7.50007 15.25H7.00007C6.58585 15.25 6.25007 14.9142 6.25007 14.5ZM9.75007 14.5C9.75007 14.0857 
                                    10.0859 13.75 10.5001 13.75H17.0001C17.4143 13.75 17.7501 14.0857 17.7501 14.5C17.7501 14.9142 17.4143 15.25 17.0001 15.25H10.5001C10.0859 15.25 9.75007 14.9142 9.75007 14.5Z" 
                                fill="#667085"
                            />
                        </svg>
                    </span>
                    <span class="bp--nav-text"><?php esc_html_e($bp_settings['transaction_label'], 'better-payment'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( $bp_settings['subscriptions_show'] ) : ?>
                <div class="bp--sidebar-nav subscriptions-tab <?php echo esc_attr( ( $bp_settings['dashboard_show'] || $bp_settings['transactions_show'] ) ? '' : 'active'); ?>" data-tab="subscriptions">
                    <span class="bp--nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_400_558)">
                                <path
                                    d="M3 8V12.172C3.00011 12.7024 3.2109 13.211 3.586 13.586L9.296 19.296C9.74795 19.7479 10.3609 20.0017 11 20.0017C11.6391 20.0017 12.252 19.7479 12.704 19.296L16.296 15.704C16.7479 15.252 17.0017 14.6391 17.0017 14C17.0017 13.3609 16.7479 12.748 16.296 12.296L10.586 6.586C10.211 6.2109 9.70239 6.00011 9.172 6H5C4.46957 6 3.96086 6.21071 3.58579 6.58579C3.21071 6.96086 3 7.46957 3 8Z"
                                    stroke="#2B2748" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M18 19L19.592 17.408C20.4958 16.5041 21.0035 15.2782 21.0035 14C21.0035 12.7218 20.4958 11.4959 19.592 10.592L15 6"
                                    stroke="#2B2748" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.99828 10H6.98828" stroke="#2B2748" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_400_558">
                                    <rect width="24" height="24" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <span class="bp--nav-text"><?php esc_html_e($bp_settings['subscription_label'], 'better-payment'); ?></span>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

        <div class="bp--db-main-wrapper">
            <?php if ( $bp_settings['dashboard_show'] ) : ?>
            <div class="bp--tab-conetnt-wrapper dashboard-tab-wrapper">
		        <?php include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/elementor/user-dashboard/template-dashboard-tab.php"; ?>
            </div>
            <?php endif; ?>
            
            <?php if ( $bp_settings['transactions_show'] ) : ?>
            <div class="bp--tab-conetnt-wrapper transactions-tab-wrapper <?php echo esc_attr( ! $bp_settings['dashboard_show'] ? '' : 'd-none'); ?>">
		        <?php include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/elementor/user-dashboard/template-transactions-tab.php"; ?>
            </div>    
            <?php endif; ?>
            
            <?php if ( $bp_settings['subscriptions_show'] ) : ?>
                <div class="bp--tab-conetnt-wrapper subscriptions-tab-wrapper <?php echo esc_attr( ( $bp_settings['dashboard_show'] || $bp_settings['transactions_show'] ) ? 'd-none' : ''); ?>">
                    <?php if ( ( ! $this->pro_enabled ) ) : ?>
                    <p>
                        <a class="width-100" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro">
                            <img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/' . 'user-dashboard-subscription-pro-banner.png'); ?>" alt="subscription-pro-banner">
                        </a>
                    </p>
                    <?php endif; ?>
                    
                    <?php do_action('better_payment/widget/user-dashboard/subscriptions_tab', $settings, $bp_settings); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>