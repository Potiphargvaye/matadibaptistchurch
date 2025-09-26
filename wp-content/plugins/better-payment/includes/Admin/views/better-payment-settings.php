<?php
/*
 * Settings page
 *  All undefined vars comes from 'render_better_payment_admin_pages' method
 *  $bp_admin_saved_settings : contains all values
 */
?>
<?php
$bp_admin_settings_total_transactions_count = isset($bp_admin_all_transactions_analytics['total_transactions']) ? $bp_admin_all_transactions_analytics['total_transactions'] : 0;
$bp_admin_settings_completed_transactions_count = isset($bp_admin_all_transactions_analytics['completed_transactions']) ? $bp_admin_all_transactions_analytics['completed_transactions'] : 0;
$bp_admin_settings_incomplete_transactions_count = isset($bp_admin_all_transactions_analytics['incomplete_transactions']) ? $bp_admin_all_transactions_analytics['incomplete_transactions'] : 0;
$better_payment_helper = new Better_Payment\Lite\Classes\Helper();
$currency_list = $better_payment_helper->get_currency_list();
?>
<!-- Admin Settings Form Wrapper: Starts  -->
<div class="better-payment">
    <div class="template__wrapper background__grey">

        <header class="pb30">
            <div class="bp-container">
                <div class="bp-row">
                    <div class="bp-col-9">
                        <div class="logo">
                            <a href="javascript:void(0)"><img src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/logo.svg'); ?>" alt="Better Payment logo"></a>
                        </div>
                    </div>
                    <div class="bp-col-3">
                        <div class="control text-right">
                            <button type="submit" class="button button__active better-payment-admin-settings-button" data-nonce="<?php echo esc_attr( wp_create_nonce('better_payment_admin_settings_nonce') ); ?>"><?php esc_html_e('Save Changes', 'better-payment'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="bp-container">
            <div class="bp-row">
                <div class="bp-col-lg-9">
                    <div class="bp-tabs">
                        <ul class="tab__menu">
                            <li class="tab__list">
                                <a href="#" class="tab__link active" data-id="settings"><i class="bp-icon bp-gear-alt"></i> <?php esc_html_e('Settings', 'better-payment'); ?></a>
                            </li>

                            <?php if ( ! $this->pro_enabled ) : ?>
                            <li class="tab__list">
                                <a href="#go-premium" class="tab__link" data-id="premium"><i class="bp-icon bp-crown"></i><?php esc_html_e('Go Premium', 'better-payment'); ?></a>
                            </li>
                            <?php endif; ?>

                            <?php if ( $this->pro_enabled ) : ?>
                            <li class="tab__list">
                                <a href="#license" class="tab__link" data-id="license"><i class="bp-icon bp-license"></i><?php esc_html_e('License', 'better-payment'); ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab__content">
                            <form method="post" id="better-payment-admin-settings-form" action="#">
                                <div class="tab__content__item background__white show" id="settings">
                                    <div class="main__content__area">
                                        <div class="sidebar">
                                            <ul class="sidebar__menu">
                                                <li class="sidebar__item">
                                                    <a href="#" class="sidebar__link active" data-id="general"><i class="bp-icon bp-gear"></i> <?php esc_html_e('General', 'better-payment'); ?></a>
                                                </li>
                                                <li class="sidebar__item">
                                                    <a href="#" class="sidebar__link" data-id="admin-email"><i class="bp-icon bp-mail"></i> <?php esc_html_e('Email', 'better-payment'); ?></a>
                                                    <ul class="sub__menu">
                                                        <li><a href="#" class="sidebar__link_submenu" data-id="admin-email"><?php esc_html_e('Admin Email', 'better-payment'); ?></a></li>
                                                        <li><a href="#" class="sidebar__link_submenu" data-id="customer-email"><?php esc_html_e('Customer Email', 'better-payment'); ?></a></li>
                                                    </ul>
                                                </li>
                                                <li class="sidebar__item">
                                                    <a href="#" class="sidebar__link" data-id="paypal"><i class="bp-icon bp-card"></i> <?php esc_html_e('Payment', 'better-payment'); ?></a>
                                                    <ul class="sub__menu">
                                                        <li><a href="#" class="sidebar__link_submenu" data-id="paypal"><?php esc_html_e('PayPal', 'better-payment'); ?></a></li>
                                                        <li><a href="#" class="sidebar__link_submenu" data-id="stripe"><?php esc_html_e('Stripe', 'better-payment'); ?></a></li>
                                                        <li><a href="#" class="sidebar__link_submenu" data-id="paystack"><?php esc_html_e('Paystack', 'better-payment'); ?></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="content__area__body">
                                            <div id="general" class="sidebar__tab__content show payment__options">
                                                <div class="payment__option">
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('PayPal', 'better-payment'); ?></h4>
                                                        <p>
                                                            <?php esc_html_e('Enable PayPal if you want to make transaction using PayPal.', 'better-payment'); ?>
                                                            <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paypal-account-with-better-payment/'); ?>"><?php _e('See documentation.', 'better-payment'); ?></a>
                                                        </p>
                                                    </div>
                                                    <div class="active__status">
                                                        <label class="bp-switch">
                                                            <input type="hidden" name="better_payment_settings_general_general_paypal" value="no">
                                                            <input type="checkbox" name="better_payment_settings_general_general_paypal" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_paypal']) && $bp_admin_saved_settings['better_payment_settings_general_general_paypal'] == 'yes' ? ' checked' : '' ?>>
                                                            <span class="switch__btn"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="payment__option">
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('Stripe', 'better-payment'); ?></h4>
                                                        <p>
                                                            <?php esc_html_e('Enable Stripe if you want to accept payment via Stripe.', 'better-payment'); ?>
                                                            <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-stripe-account-with-better-payment/'); ?>"><?php _e('See documentation.', 'better-payment'); ?></a>
                                                        </p>
                                                    </div>
                                                    <div class="active__status">
                                                        <label class="bp-switch">
                                                            <input type="hidden" name="better_payment_settings_general_general_stripe" value="no">
                                                            <input type="checkbox" name="better_payment_settings_general_general_stripe" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_stripe']) && $bp_admin_saved_settings['better_payment_settings_general_general_stripe'] == 'yes' ? ' checked' : '' ?>>
                                                            <span class="switch__btn"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="payment__option">
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('Paystack', 'better-payment'); ?></h4>
                                                        <p>
                                                            <?php esc_html_e('Enable Paystack if you want to accept payment via Paystack.', 'better-payment'); ?>
                                                            <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paystack-account-with-better-payment/'); ?>"><?php _e('See documentation.', 'better-payment'); ?></a>
                                                        </p>
                                                    </div>
                                                    <div class="active__status">
                                                        <label class="bp-switch">
                                                            <input type="hidden" name="better_payment_settings_general_general_paystack" value="no">
                                                            <input type="checkbox" name="better_payment_settings_general_general_paystack" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_paystack']) && $bp_admin_saved_settings['better_payment_settings_general_general_paystack'] == 'yes' ? ' checked' : '' ?>>
                                                            <span class="switch__btn"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="payment__option">
                                                    <?php $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] = isset( $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] ) ? sanitize_text_field( $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] ) : 'yes'; ?>
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('User Dashboard', 'better-payment'); ?></h4>
                                                        <p>
                                                            <?php esc_html_e('Enable User Dashboard widget. It shows list of transactions and subscriptions for the user.', 'better-payment'); ?>
                                                            <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/user-dashboard-using-better-payment/'); ?>"><?php _e('See documentation.', 'better-payment'); ?></a>
                                                        </p>
                                                    </div>
                                                    <div class="active__status">
                                                        <label class="bp-switch">
                                                            <input type="hidden" name="better_payment_settings_general_general_user_dashboard" value="no">
                                                            <input type="checkbox" name="better_payment_settings_general_general_user_dashboard" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard']) && $bp_admin_saved_settings['better_payment_settings_general_general_user_dashboard'] == 'yes' ? ' checked' : '' ?>>
                                                            <span class="switch__btn"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="payment__option">
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('Email Notification', 'better-payment'); ?></h4>
                                                        <p><?php esc_html_e('Enable email notification for each transaction. It sends notification to the website admin and customer (who makes the payment). You can modify email settings as per your need.', 'better-payment'); ?></p>
                                                    </div>
                                                    <div class="active__status">
                                                        <label class="bp-switch">
                                                            <input type="hidden" name="better_payment_settings_general_general_email" value="no">
                                                            <input type="checkbox" name="better_payment_settings_general_general_email" value="yes" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_general_email']) && $bp_admin_saved_settings['better_payment_settings_general_general_email'] == 'yes' ? ' checked' : '' ?>>
                                                            <span class="switch__btn"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="payment__option">
                                                    <div class="payment__option__content">
                                                        <h4><?php esc_html_e('Currency', 'better-payment'); ?></h4>
                                                        <p><?php esc_html_e('Select default currency for each transaction. You can also overwrite this setting from each widget control on elementor page builder.', 'better-payment'); ?></p>
                                                    </div>
                                                    <div class="active__status">
                                                        <div class="bp-select">
                                                            <select name="better_payment_settings_general_general_currency">
                                                                <option value="" disabled> <?php esc_html_e('Select Currency', 'better-payment'); ?> </option>
                                                                <?php foreach ($currency_list as $key => $value){
                                                                    $selected = isset($bp_admin_saved_settings['better_payment_settings_general_general_currency']) && $bp_admin_saved_settings['better_payment_settings_general_general_currency'] == $key?'selected':'';
                                                                    printf( '<option value="%s" %s >%s</option>', esc_attr( $key ), esc_attr( $selected ), esc_html__( $key, 'better-payment' ) );
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="admin-email" class="sidebar__tab__content p50">
                                                <div class="mailing__option mb30">
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('To', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" placeholder="<?php esc_html_e('Email address', 'better-payment'); ?>" name="better_payment_settings_general_email_to" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_to']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_to'] ) : '' ?>">
                                                            <p><?php esc_html_e('Enter website admin email address here. This email will be used to send email notification for each transaction.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Subject', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="text" class="form__control" placeholder="<?php esc_html_e('Email subject', 'better-payment'); ?>" name="better_payment_settings_general_email_subject" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_subject']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_subject'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email subject for the admin email notification.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Message', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <textarea class="form__control" name="better_payment_settings_general_email_message_admin"><?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_message_admin']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_message_admin'] ) : '' ?> </textarea>
                                                            <p><?php esc_html_e('Email body for the admin email notification.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="#" class="button add__button email-additional-headers"><span><i class="bp-icon bp-plus"></i></span> <?php esc_html_e('Additional Headers', 'better-payment'); ?></a>
                                                <div class="mailing__option mt30 email-additional-headers-content bp-d-none">
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('From Name', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="text" class="form__control" name="better_payment_settings_general_email_from_name" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_from_name']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_from_name'] ) : '' ?>">
                                                            <p><?php esc_html_e('From name that will be used in the email headers.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('From Email', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_from_email" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_from_email']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_from_email'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as From Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Reply-To', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_reply_to" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_reply_to']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_reply_to'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Reply-To Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Cc', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_cc" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_cc']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_cc'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Cc Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Bcc', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_bcc" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_bcc']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_bcc'] ) : '' ?>" type="email">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Bcc Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Send As', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <div class="bp-select">
                                                                <select name="better_payment_settings_general_email_send_as">
                                                                    <option value="" disabled> <?php esc_html_e('Select One', 'better-payment'); ?> </option>
                                                                    <option value="plain" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_send_as']) && $bp_admin_saved_settings['better_payment_settings_general_email_send_as'] == 'plain' ? ' selected' : '' ?>> <?php esc_html_e('Plain', 'better-payment'); ?> </option>
                                                                    <option value="html" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_send_as']) && $bp_admin_saved_settings['better_payment_settings_general_email_send_as'] == 'html' ? ' selected' : '' ?>> <?php esc_html_e('Html', 'better-payment'); ?> </option>
                                                                </select>
                                                            </div>
                                                            <p><?php esc_html_e('Html helps to send html markup in the email body. Select plain if you just want plain text in the email body.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="customer-email" class="sidebar__tab__content p50">
                                                <div class="mailing__option mb30">
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('To', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <p class="mt0"><?php esc_html_e('Customer email address will be auto populated from payment form. This email will be used to send email notification for each transaction.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Subject', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="text" class="form__control" placeholder="<?php esc_html_e('Email subject', 'better-payment'); ?>" name="better_payment_settings_general_email_subject_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_subject_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_subject_customer'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email subject for the customer (who make payments) email notification.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Message', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <textarea class="form__control" name="better_payment_settings_general_email_message_customer"><?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_message_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_message_customer'] ) : '' ?> </textarea>
                                                            <p><?php esc_html_e('Email body for the customer email notification. ', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="#" class="button add__button email-additional-headers"><span><i class="bp-icon bp-plus"></i></span> <?php esc_html_e('Additional Headers', 'better-payment'); ?></a>
                                                <div class="mailing__option mt30 email-additional-headers-content">
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('From Name', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="text" class="form__control" name="better_payment_settings_general_email_from_name_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_from_name_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_from_name_customer'] ) : '' ?>">
                                                            <p><?php esc_html_e('From name that will be used in the email headers.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('From Email', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_from_email_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_from_email_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_from_email_customer'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as From Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Reply-To', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_reply_to_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_reply_to_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_reply_to_customer'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Reply-To Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Cc', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_cc_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_cc_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_cc_customer'] ) : '' ?>">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Cc Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Bcc', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <input type="email" class="form__control" name="better_payment_settings_general_email_bcc_customer" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_bcc_customer']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_general_email_bcc_customer'] ) : '' ?>" type="email">
                                                            <p><?php esc_html_e('Email address that will be displayed in the email header as Bcc Email.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="input__wrap">
                                                        <p class="title"><?php esc_html_e('Send As', 'better-payment'); ?></p>
                                                        <div class="input__area">
                                                            <div class="bp-select">
                                                                <select name="better_payment_settings_general_email_send_as_customer">
                                                                    <option value="" disabled> <?php esc_html_e('Select One', 'better-payment'); ?> </option>
                                                                    <option value="plain" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_send_as_customer']) && $bp_admin_saved_settings['better_payment_settings_general_email_send_as_customer'] == 'plain' ? ' selected' : '' ?>> <?php esc_html_e('Plain', 'better-payment'); ?> </option>
                                                                    <option value="html" <?php echo isset($bp_admin_saved_settings['better_payment_settings_general_email_send_as_customer']) && $bp_admin_saved_settings['better_payment_settings_general_email_send_as_customer'] == 'html' ? ' selected' : '' ?>> <?php esc_html_e('Html', 'better-payment'); ?> </option>
                                                                </select>
                                                            </div>
                                                            <p><?php esc_html_e('Html helps to send html markup in the email body. Select plain if you just want plain text in the email body.', 'better-payment'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="paypal" class="sidebar__tab__content better-payment-settings-payment-paypal">
                                                <div class="payment__options">
                                                    <div class="payment__option">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Mode', 'better-payment'); ?></h4>
                                                            <p><?php esc_html_e('Live mode allows you to process real transactions. It just requires PayPal business email to accept real payments.', 'better-payment'); ?></p>
                                                        </div>
                                                        <div class="active__status">
                                                            <label class="bp-switch">
                                                                <input type="hidden" name="better_payment_settings_payment_paypal_live_mode" value="no">
                                                                <input type="checkbox" name="better_payment_settings_payment_paypal_live_mode" value="yes" data-target="better-payment-settings-payment-paypal-live" data-targettest="bp-paypal-test-key" data-targetlive="bp-paypal-live-key" <?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode'] == 'yes' ? ' checked' : '' ?>>
                                                                <span class="switch__btn"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="payment__option">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Business Email', 'better-payment'); ?></h4>
                                                            <p><?php esc_html_e('Your PayPal account email address to accept payment via PayPal.', 'better-payment'); ?></p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="email" class="form__control" name="better_payment_settings_payment_paypal_email" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_email']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paypal_email'] ) : '' ?>">
                                                        </div>
                                                    </div>

                                                    <?php $better_payment_settings_payment_paypal_live = isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_paypal_live_mode'] == 'yes'; ?>

                                                    <div class="payment__option bp-paypal-key bp-paypal-live-key <?php echo $better_payment_settings_payment_paypal_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Client ID', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('PayPal live client ID is required to do Refund via PayPal. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://developer.paypal.com/developer/applications">https://developer.paypal.com/developer/applications</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paypal-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_paypal_live_client_id" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_live_client_id']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paypal_live_client_id'] ) : '' ?>" type="text" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paypal-key bp-paypal-live-key <?php echo $better_payment_settings_payment_paypal_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Secret', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('PayPal live secret is required to do refund via PayPal. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://developer.paypal.com/developer/applications">https://developer.paypal.com/developer/applications</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paypal-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_paypal_live_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_live_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paypal_live_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paypal-key bp-paypal-test-key <?php echo $better_payment_settings_payment_paypal_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test/Sandbox Client ID', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('PayPal test/sandbox client id is required to do refund via PayPal. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://developer.paypal.com/developer/applications">https://developer.paypal.com/developer/applications</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paypal-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_paypal_test_client_id" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_test_client_id']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paypal_test_client_id'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paypal-key bp-paypal-test-key <?php echo $better_payment_settings_payment_paypal_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test/Sandbox Secret', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('PayPal test/sandbox secret is required to do refund via PayPal. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://developer.paypal.com/developer/applications">https://developer.paypal.com/developer/applications</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paypal-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_paypal_test_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paypal_test_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paypal_test_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div id="stripe" class="sidebar__tab__content better-payment-settings-payment-stripe">
                                                <div class="payment__options">
                                                    <div class="payment__option">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Mode', 'better-payment'); ?></h4>
                                                            <p><?php esc_html_e('Live mode allows you to process real transactions. It just requires live Stripe keys (public and secret keys) to accept real payments.', 'better-payment'); ?></p>
                                                        </div>
                                                        <div class="active__status">
                                                            <label class="bp-switch">
                                                                <input type="hidden" name="better_payment_settings_payment_stripe_live_mode" value="no">
                                                                <input type="checkbox" name="better_payment_settings_payment_stripe_live_mode" value="yes" data-targettest="bp-stripe-test-key" data-targetlive="bp-stripe-live-key" <?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode'] == 'yes' ? ' checked' : '' ?>>
                                                                <span class="switch__btn"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <?php $better_payment_settings_payment_stripe_live = isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_mode'] == 'yes'; ?>

                                                    <div class="payment__option bp-stripe-key bp-stripe-live-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Public Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Stripe live public key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-stripe-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_stripe_live_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_public'] ) : '' ?>" type="text" placeholder="<?php //esc_html_e('Live public key', 'better-payment'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-stripe-key bp-stripe-live-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Secret Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Stripe live secret key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-stripe-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_stripe_live_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_live_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_live_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-stripe-key bp-stripe-test-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test Public Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Stripe test public key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-stripe-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_stripe_test_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_test_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_test_public'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-stripe-key bp-stripe-test-key <?php echo $better_payment_settings_payment_stripe_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test Secret Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Stripe test secret key is required to make payments via Stripe. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://stripe.com/docs/keys">https://stripe.com/docs/keys</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-stripe-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_stripe_test_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_stripe_test_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_stripe_test_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="paystack" class="sidebar__tab__content better-payment-settings-payment-paystack">
                                                <div class="payment__options">
                                                    <div class="payment__option">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Mode', 'better-payment'); ?></h4>
                                                            <p><?php esc_html_e('Live mode allows you to process real transactions. It just requires live Paystack keys (public and secret keys) to accept real payments.', 'better-payment'); ?></p>
                                                        </div>
                                                        <div class="active__status">
                                                            <label class="bp-switch">
                                                                <input type="hidden" name="better_payment_settings_payment_paystack_live_mode" value="no">
                                                                <input type="checkbox" name="better_payment_settings_payment_paystack_live_mode" value="yes" data-targettest="bp-paystack-test-key" data-targetlive="bp-paystack-live-key" <?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_paystack_live_mode'] == 'yes' ? ' checked' : '' ?>>
                                                                <span class="switch__btn"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <?php $better_payment_settings_payment_paystack_live = isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_live_mode']) && $bp_admin_saved_settings['better_payment_settings_payment_paystack_live_mode'] == 'yes'; ?>

                                                    <div class="payment__option bp-paystack-key bp-paystack-live-key <?php echo $better_payment_settings_payment_paystack_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Public Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Paystack live public key is required to make payments via Paystack. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://dashboard.paystack.com/#/settings/developers">https://dashboard.paystack.com/#/settings/developers</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paystack-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_paystack_live_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_live_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paystack_live_public'] ) : '' ?>" type="text" placeholder="<?php //esc_html_e('Live public key', 'better-payment'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paystack-key bp-paystack-live-key <?php echo $better_payment_settings_payment_paystack_live ? 'bp-d-block' : 'bp-d-none' ?>">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Live Secret Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Paystack live secret key is required to make payments via Paystack. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://dashboard.paystack.com/#/settings/developers">https://dashboard.paystack.com/#/settings/developers</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paystack-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_paystack_live_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_live_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paystack_live_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paystack-key bp-paystack-test-key <?php echo $better_payment_settings_payment_paystack_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test Public Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Paystack test public key is required to make payments via Paystack. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://dashboard.paystack.com/#/settings/developers">https://dashboard.paystack.com/#/settings/developers</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paystack-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="text" class="form__control mt15" name="better_payment_settings_payment_paystack_test_public" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_test_public']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paystack_test_public'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="payment__option bp-paystack-key bp-paystack-test-key <?php echo $better_payment_settings_payment_paystack_live ? 'bp-d-none' : 'bp-d-block' ?> ">
                                                        <div class="payment__option__content">
                                                            <h4><?php esc_html_e('Test Secret Key', 'better-payment'); ?></h4>
                                                            <p>
                                                                <?php esc_html_e('Paystack test secret key is required to make payments via Paystack. For more help visit', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://dashboard.paystack.com/#/settings/developers">https://dashboard.paystack.com/#/settings/developers</a> or
                                                                <a class="color__themeColor" target="_blank" href="<?php echo esc_url('//betterpayment.co/docs/set-up-paystack-account-with-better-payment/'); ?>"><?php _e('see documentation.', 'better-payment'); ?></a>
                                                            </p>
                                                        </div>
                                                        <div class="active__status input__wrap">
                                                            <input type="password" class="form__control mt15" name="better_payment_settings_payment_paystack_test_secret" value="<?php echo isset($bp_admin_saved_settings['better_payment_settings_payment_paystack_test_secret']) ? esc_attr( $bp_admin_saved_settings['better_payment_settings_payment_paystack_test_secret'] ) : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            
                            <?php if ( ! $this->pro_enabled ) : ?>
                            <div class="tab__content__item background__white" id="premium">
                                <?php 
                                ob_start();
                                include BETTER_PAYMENT_ADMIN_VIEWS_PATH . "/partials/go-premium.php";
                                $go_premium_tab_content = ob_get_contents();
                                ob_end_clean();
                                
                                $go_premium_tab_content = apply_filters('better_payment/admin/go_premium_tab_content', $go_premium_tab_content);
                                
                                echo wp_kses( $go_premium_tab_content, $this->bp_allowed_tags() );
                                ?>
                            </div>
                            <?php endif; ?>

                            <?php if ( $this->pro_enabled ) : ?>
                            <div class="tab__content__item background__white" id="license">
                                <?php do_action('better_payment/admin/license_tab_content'); ?>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <div class="bp-col-lg-3">
                    <div class="statistic">
                        <div class="icon">
                            <i class="bp-icon bp-swap"></i>
                        </div>
                        <div class="statistic__body">
                            <h3><?php esc_html_e($bp_admin_settings_total_transactions_count, 'better-payment'); ?></h3>
                            <p><?php esc_html_e('Total Transactions', 'better-payment'); ?></p>
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="icon">
                            <i class="bp-icon bp-list-check"></i>
                        </div>
                        <div class="statistic__body">
                            <h3><?php esc_html_e($bp_admin_settings_completed_transactions_count, 'better-payment'); ?></h3>
                            <p><?php esc_html_e('Completed Transactions', 'better-payment'); ?></p>
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="icon">
                            <i class="bp-icon bp-server"></i>
                        </div>
                        <div class="statistic__body">
                            <h3><?php esc_html_e($bp_admin_settings_incomplete_transactions_count, 'better-payment'); ?></h3>
                            <p><?php esc_html_e('Incomplete Transactions', 'better-payment'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bp-row">
                <div class="bp-col-xl-3 bp-col-md-6">
                    <div class="feature__card__wrapper">
                        <div class="feature__card">
                            <div class="icon">
                                <i class="bp-icon bp-doc"></i>
                            </div>
                            <h3><?php esc_html_e('Documentation', 'better-payment'); ?></h3>
                            <p><?php esc_html_e('Get started by spending some time with the documentation to get familiar with Better Payment.', 'better-payment'); ?></p>
                            <a href="//betterpayment.co/docs/" class="button" target="_blank"><?php esc_html_e('Documentation', 'better-payment'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="bp-col-xl-3 bp-col-md-6">
                    <div class="feature__card__wrapper">
                        <div class="feature__card">
                            <div class="icon">
                                <i class="bp-icon bp-contribute"></i>
                            </div>
                            <h3><?php esc_html_e('Contribute to Better Payment', 'better-payment'); ?></h3>
                            <p><?php esc_html_e('You can contribute to make Better Payment better reporting bugs, creating issues, pull requests at ', 'better-payment'); ?> <a class="color__themeColor" target="_blank" rel="nofollow" href="https://github.com/WPDevelopers">Github</a>.</p>
                            <a href="//wordpress.org/support/plugin/better-payment/" class="button" target="_blank"><?php esc_html_e('Report a Bug', 'better-payment'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="bp-col-xl-3 bp-col-md-6">
                    <div class="feature__card__wrapper">
                        <div class="feature__card">
                            <div class="icon">
                                <i class="bp-icon bp-help-center"></i>
                            </div>
                            <h3><?php esc_html_e('Need Help?', 'better-payment'); ?></h3>
                            <p><?php esc_html_e('Stuck with something? Get help from live chat or support ticket.', 'better-payment'); ?></p>
                            <a href="//wpdeveloper.com/support" class="button" target="_blank"><?php esc_html_e('Initiate a Chat', 'better-payment'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="bp-col-xl-3 bp-col-md-6">
                    <div class="feature__card__wrapper">
                        <div class="feature__card">
                            <div class="icon">
                                <i class="bp-icon bp-heart"></i>
                                <h3><?php esc_html_e('Show Your Love', 'better-payment'); ?></h3>
                            </div>

                            <p><?php esc_html_e('We love to have you in Better Payment family. We are making it more awesome everyday. Take your 2 minutes to review the plugin and spread the love to encourage us to keep it going.', 'better-payment'); ?></p>
                            <a href="//wordpress.org/support/plugin/better-payment/reviews/#new-post" class="button" target="_blank"><?php esc_html_e('Leave a Review', 'better-payment'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Admin Settings Form Wrapper: Ends  -->
