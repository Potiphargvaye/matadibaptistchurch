<?php if ( 'admin' === $type && 'plain' === $all_data['email_content_type'] ) : ?>
<?php echo $all_data['email_content_admin']; ?>
<?php elseif ( 'admin' !== $type && 'plain' === $all_data['email_content_type_customer'] ) : ?>
<?php echo $all_data['email_content_customer']; ?>
<?php else : ?>

    <!-- admin / customer html email  -->
    <!-- start__style-sheets  -->
    <style>
        /* font: IBM Plex Sans ;  */
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Play:wght@400;700&family=Syne:wght@400..800&display=swap');

        /* base  */
        * {
            padding: 0px;
            margin: 0px;
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
        }

        body {
            font-family: "IBM Plex Sans", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        @media screen and (max-width: 880px) {



            /* hero-table  */
            h1.bp-title {
                font-size: 34px;
            }

            p.bp-sub__title {
                font-size: 18px;
            }

            /*/ hero-table  */

            .bp-inv__number {
                font-size: 25px;
            }

            .bp-ive__date,
            .bp-success {
                font-size: 16px;
            }

            .bp-summary__table-wrapper {
                padding: 12px;
            }

            .bp-invelop h4,
            .bp-invelop .bp-name,
            .bp-invelop .bp-email,
            .bp-invelop .bp-content,
            .bp-invelop .bp-address,
            .bp-invelop .bp-ess__campaign,
            .bp-invelop .bp-payment__methord span {
                font-size: 16px;
                margin-bottom: 8px;

            }

            .bp-summary__title,
            h2.bp-footer__title {
                font-size: 26px;
            }

            .bp-summary__table-header,
            a.bp-footer__btn {
                font-size: 16px;
            }

            .bp-summary__table-item__style,
            .bp-table__data-output {
                font-size: 14px;
                line-height: 1.0;
            }

            td.bp-width__100p-active__tablet {
                width: 100px;
            }

            td.bp-width__80p-active__tablet {
                width: 80px;
            }

        }

        @media screen and (max-width: 520px) {

            /* base  */
            td.bp-width__60p-active__phone {
                width: 60px;
            }

            .bp-text-align__center-active__phone {
                text-align: center;
            }


            /* hero-table  */
            h1.bp-title {
                font-size: 30px;
            }

            p.bp-sub__title {
                font-size: 16px;
            }

            /*/ hero-table  */

            .bp-inv__number {
                font-size: 18px;
            }

            .bp-ive__date,
            .bp-success {
                font-size: 13px;
            }

            .bp-summary__table-wrapper {
                padding: 10px;
            }

            .bp-invelop h4,
            .bp-invelop .bp-name,
            .bp-invelop .bp-email,
            .bp-invelop .bp-content,
            .bp-invelop .bp-address,
            .bp-invelop .bp-ess__campaign,
            .bp-invelop .bp-payment__methord span {
                font-size: 13px;
                margin-bottom: 8px;

            }

            .bp-summary__title,
            h2.bp-footer__title {
                font-size: 23px;
            }

            .bp-summary__table-header,
            .abp-footer__btn {
                font-size: 13px;
            }

            .bp-summary__table-item__style,
            .bp-table__data-output {
                font-size: 12px;
                line-height: 1.0;
            }

        }
    </style>
    <div style="width: 100%; table-layout: fixed;">
        <table style="background-color: #fff; margin: 0 auto; width: 100%; max-width: 1200px; border-spacing: 0; padding-bottom: 50px; padding-left: 12px; padding-right: 12px;">
            <!-- start Better payment  email header  -->
            <?php if ( $all_data['email_content_heading_show'] || $all_data['is_elementor_form'] ) : ?>
            <tr>
                <td style="background: rgba(110, 88, 247, 1); width: 100%; padding: 55px 0; border-radius: 8px 8px 0 0; text-align: center;">
                    <img style="width: 100%; height: auto; max-width: 66px; margin-bottom: 10px;" src="<?php echo esc_url( BETTER_PAYMENT_ASSETS . '/img/email-tick.png' ); ?>" alt="<?php esc_html_e( 'email-tick', 'better-payment' ); ?>">
                    
                    <?php if($type === 'admin') : ?>
                    <h1 style="color: #fff; font-size: 64px; font-weight: 500; margin: 0 0 10px; line-height: 1.2em"><?php esc_html_e( 'Great News! Admin', 'better-payment' ); ?></h1>
                    <p style="color: rgba(255, 255, 255, 1); font-size: 28px; font-weight: 400; margin: 0; line-height: 1.2em;"><?php esc_html_e( 'You have received a new transaction through', 'better-payment' ); ?> <?php esc_html_e( $all_data['form_name'], 'better-payment' ); ?></p>
                    
                    <?php else : ?>
                    <h1 style="color: #fff; font-size: 64px; font-weight: 500; margin: 0 0 10px; line-height: 1.2em"><?php esc_html_e( 'Thank You!', 'better-payment' ); ?> <?php echo esc_html( $all_data['customer_name']); ?></h1>
                    <p style="color: rgba(255, 255, 255, 1); font-size: 28px; font-weight: 400; max-width: 930px; padding: 0 10px; margin: 0 auto; line-height: 1.2em;"><?php printf( __( 'This is to acknowledge that we have received the payment of %s %s on %s', 'better-payment' ), esc_html( $all_data['amount'] ), esc_html( $all_data['currency'] ), esc_html( $all_data['payment_date_time'] ) ); ?></p>
                    <?php endif; ?>

                </td>
            </tr>
            <?php endif; ?>
            <!-- end Better payment   email header  -->

            <tr>
                <td style="background-color: #fff;">
                    <!-- invoice table -->
                    <table style="width: 100%; max-width: 1000px; margin: 0 auto;">
                        <tr>
                            <td>
                                <!-- invoice info table  -->
                                <table style="width: 100%; padding-bottom: 5px; padding: 55px 0px 0px 0px; margin-bottom: 55px; border-bottom: 1px solid rgba(216, 223, 251, 1); padding-bottom: 5px;">
                                    <tr>
                                        <td style="color: rgba(0, 0, 0, 1); font-weight: 300; font-size: 24px; text-align: left; line-height: 1.2em">
                                            <span><?php esc_html_e( 'Transaction ID - ', 'better-payment' ); ?><?php esc_html_e( $all_data['transaction_id'], 'better-payment' ); ?></span>
                                        </td>

                                        <td style="text-align: right;">
                                            <span style="font-size: 18px; font-weight: 400; color: rgba(43, 39, 72, 1);">
                                                <span style="color:  rgba(113, 120, 148, 1);"><?php esc_html_e( 'Date : ', 'better-payment' ); ?></span><span> <?php esc_html_e( $all_data['payment_date_only'], 'better-payment' ); ?></span>
                                            </span>
                                            <span>
                                                <span style="font-size: 18px; font-weight: 400; color: rgba(43, 39, 72, 1); background: rgba(208, 243, 226, 1); padding: 5px 16px; border-radius: 8px; margin-left: 40px; margin-top: 10px;"><?php esc_html_e( $all_data['status'], 'better-payment' ); ?></span>
                                            </span>
                                        </td>
                                    </tr>

                                </table>
                                <!-- invoice info table  -->
                                <!-- invoice invelop table  -->
                                <table style="width: 100%; padding-bottom: 60px;">
                                    <tr>
                                        <!-- from section -->
                                        <?php if ( $all_data['email_content_from_section_show'] || $all_data['is_elementor_form'] ) : ?>
                                        <td style="vertical-align: text-top;" class="bp-invelop">
                                            <h4 style="color: rgba(110, 88, 247, 1); font-size: 24px; font-weight: 500; margin: 0 0 8px;"><?php esc_html_e( 'From', 'better-payment' ); ?></h4>
                                            <h3 style="color: rgba(110, 88, 247, 1); font-size: 24px; font-weight: 500; color: rgba(43, 39, 72, 1); margin: 0 0 20px;" class="bp-name"><?php echo esc_html( $all_data['customer_name'] ); ?></h3>
                                            <?php 
                                            $email_address = ! empty( $all_data['form_fields_info_arr']['primary_email'] ) ? $all_data['form_fields_info_arr']['primary_email'] : '';
                                            $email_address = empty( $email_address ) && ! empty( $all_data['form_fields_info_arr']['email'] ) ? $all_data['form_fields_info_arr']['email'] : $email_address; 
                                            ?>
                                            <p style="color: rgba(110, 88, 247, 1); font-size: 24px; color: rgba(43, 39, 72, 1); font-weight: 400; margin: 0 0 14px; line-height: 1.2em;" class="bp-email"><?php echo esc_html( $email_address ); ?></p>
                                            <?php 
                                            foreach ( $all_data['form_fields_info_arr'] as $key => $field ) {
                                                if ( $key === 'is_payment_split_payment' ) {
                                                    $is_payment_split_payment = 1;
                                                }
                                                
                                                if ( $key === 'split_payment_installment_iteration' ) {
                                                    $key = 'installment_iteration';
                                                }
                                                
                                                //Hide few fields
                                                if( $key === 'referer_page_id' || $key === 'referer_widget_id' || $key === 'source' || $key === 'el_form_fields' ||
                                                    $key === 'primary_first_name' || $key === 'primary_last_name' || $key === 'primary_email' 
                                                    || $key === 'primary_payment_amount'
                                                    || $key === 'amount_quantity'
                                                    || $key === 'is_woo_layout'
                                                    || $key === 'is_payment_split_payment'
                                                    || $key === 'split_payment_total_amount'
                                                    || $key === 'split_payment_total_amount_price_id'
                                                    || $key === 'split_payment_installment_price_id'
                                                    || $key === 'recurring_price_id'
                                                ) {
                                                    if($key === 'referer_page_id'){
                                                        $referer_content_page_link = !empty($field) ? get_permalink( $field ) : $referer_content_page_link;
                                                    }
                                
                                                    continue;
                                                }
                                
                                                $key_formatted = self::better_title_case($key);
                                                
                                                if( $key_formatted === 'Amount' ){
                                                    $key_formatted = __('Paid', 'better-payment');

                                                    if ( ! empty( $is_payment_split_payment ) ) {
                                                        $key_formatted = __('Total Amount', 'better-payment');
                                                    }
                                                }
                                                ?>
                                                <p style="color: rgba(110, 88, 247, 1); font-size: 24px; color: rgba(43, 39, 72, 1); font-weight: 400; margin: 0 0 14px; line-height: 1.2em;" class="bp-content"><?php echo esc_html( $key_formatted ); ?>: <?php echo esc_html( $field ); ?></p>
                                                <?php 
                                            }
                                            ?>
                                        </td>
                                        <?php endif; ?>
                                        <!-- / from section -->

                                        <!-- to section -->
                                        <?php if ( $all_data['email_content_to_section_show'] || $all_data['is_elementor_form'] ) : ?>
                                        <td style="text-align: right; vertical-align: text-top;" class="bp-invelop">
                                            <h4 style="color: rgba(110, 88, 247, 1); font-size: 24px; font-weight: 500; margin-bottom: 8px;line-height: 1.2em;"><?php esc_html_e( 'To', 'better-payment' ); ?></h4>
                                            <h3 style="color: rgba(110, 88, 247, 1); font-size: 24px; font-weight: 500; color: rgba(43, 39, 72, 1); margin-bottom: 20px; line-height: 1.2em;" class="bp-name"><?php echo esc_html( $all_data['site_title']); ?></h3>
                                            <p style="color: rgba(110, 88, 247, 1); font-size: 24px; color: rgba(43, 39, 72, 1); font-weight: 400; margin: 0 0 14px; line-height: 1.2em;" class="bp-email"><?php echo esc_html( $all_data['site_admin_email']); ?></p>
                                            <p style="color: rgba(110, 88, 247, 1); font-size: 24px; font-weight: 500; color: rgba(43, 39, 72, 1); margin: 0 0 14px; line-height: 1.2em;" class="bp-ess__campaign"><?php esc_html_e( $all_data['form_name'], 'better-payment'); ?></p>
                                            
                                            <div style="line-height: 18px;">
                                                <span style="font-size: 22px; color: rgba(43, 39, 72, 1); font-weight: 400;"><?php esc_html_e( 'Payment Method : ', 'better-payment'); ?></span> <img style="vertical-align:middle;" src="<?php echo esc_url( $source_image_url ); ?>" alt="<?php echo esc_url( $source_image_alt ); ?>">
                                            </span>
                                        </td>
                                        <?php endif; ?>
                                        <!-- / to section -->
                                    </tr>
                                </table>
                                <!-- / invoice invelop table  -->
                            </td>
                        </tr>
                    </table>
                    <!-- / invoice table  -->
                </td>
            </tr>

            <tr>
                <td>
                    <div style="max-width: 1000px; margin: 0 auto 20px; font-size: 20px">
                        <?php if($type === 'admin') : ?>
                        <?php echo $all_data['email_content_admin']; ?>
                        <?php else : ?>
                        <?php echo $all_data['email_content_customer']; ?>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>

            <?php if ( $all_data['email_content_transaction_summary_show'] || $all_data['is_elementor_form'] ) : ?>
            <tr>
                <td>
                    <!-- summary title -->
                    <div style="max-width: 1000px; margin: 0 auto;">
                        <h2 style="font-size: 32px; font-weight: 500; color: rgba(43, 39, 72, 1); text-align: left; padding-bottom: 20px; margin: 0; line-height: 1.2em"><?php esc_html_e( 'Transaction Summary:', 'better-payment'); ?></h2>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <!-- Summary table -->
                    <div style="max-width: 950px; margin: 0 auto; background: rgba(246, 248, 255, 1); border-radius: 8px; padding: 30px; margin-bottom: 50px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td>
                                    <h3 style="color: rgba(113, 120, 148, 1); font-size: 20px; line-height: 2.4; font-weight: 500; border-bottom: 2px solid rgba(193, 200, 228, 1); margin: 0;" class="bp-summary__table-header"><?php esc_html_e( 'Description', 'better-payment'); ?></h3>
                                </td>
                                <td style="width: 180px;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(113, 120, 148, 1); font-size: 20px; line-height: 2.4; font-weight: 500; border-bottom: 2px solid rgba(193, 200, 228, 1); margin: 0;" class="bp-summary__table-header bp-text-align__center-active__phone"><?php esc_html_e( 'Rate', 'better-payment'); ?></h3>
                                </td>
                                <td style="width: 180px;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(113, 120, 148, 1); font-size: 20px; line-height: 2.4; font-weight: 500; border-bottom: 2px solid rgba(193, 200, 228, 1); margin: 0;" class="bp-summary__table-header bp-text-align__center-active__phone"><?php esc_html_e( 'Qty', 'better-payment'); ?></h3>
                                </td>
                                <td style="width: 180px;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(113, 120, 148, 1); font-size: 20px; line-height: 2.4; font-weight: 500; border-bottom: 2px solid rgba(193, 200, 228, 1); margin: 0;" class="bp-summary__table-header bp-text-align__center-active__phone"><?php esc_html_e( 'Amount', 'better-payment'); ?></h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid rgba(193, 200, 228, 1); padding-bottom: 10px; padding-top: 25px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <?php if ( ! empty( $all_data['woo_product_id'] ) ) : ?>
                                            <td style="width: 50px; width: 32px; display: inline-block; margin-top: 5px;"> <span><img style="width: 100%; height: auto;" src="<?php echo esc_url( $all_data['product_image_src'] ); ?>" alt="<?php echo esc_html( $all_data['product_name']); ?>"></span></td>
                                            <td> <span style="color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 1.2em;"><?php echo esc_html( $all_data['product_name']); ?></span>
                                            
                                            <?php else : ?>
                                            <td> <span style="color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 1.2em;"><?php esc_html_e( $all_data['form_name'], 'better-payment' ); ?></span>
                                            <?php endif; ?>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td style="width: 180px; border-bottom: 1px solid rgba(193, 200, 228, 1); padding-bottom: 10px; padding-top: 25px;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 1.2em; margin: 0;" class="bp-text-align__center-active__phone" style="font-weight: 500;"><?php echo esc_html( $all_data['amount_single'] ); ?></h3>
                                </td>
                                <td style="width: 180px; border-bottom: 1px solid rgba(193, 200, 228, 1); padding-bottom: 10px; padding-top: 25px; color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 2.4;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 1.2em; margin: 0;" class="bp-text-align__center-active__phone" style="font-weight: 500;"><?php echo esc_html( $all_data['amount_quantity'] ); ?></h3>
                                </td>
                                <td style="width: 180px; border-bottom: 1px solid rgba(193, 200, 228, 1); padding-bottom: 10px; padding-top: 25px;" class="bp-width__100p-active__tablet bp-width__60p-active__phone">
                                    <h3 style="text-align: right; color: rgba(43, 39, 72, 1); font-size: 18px; font-weight: 500; line-height: 1.2em; margin: 0;" class="bp-text-align__center-active__phone" style="font-weight: 500;"><?php echo esc_html( $all_data['currency_symbol'] ); ?><?php echo esc_html( $all_data['amount'] ); ?></h3>
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; padding-top: 30px;">
                            <tr>
                                <td>&nbsp;</td>
                                <td style="width: 120px;" class="bp-width__80p-active__tablet"><span style="font-size: 18px; font-weight: 500; line-height: 1.7; color: rgba(43, 39, 72, 1);"><?php esc_html_e( 'Total:', 'better-payment' ); ?></span></td>
                                <td style="text-align: right; width: 120px;" class="bp-width__80p-active__tablet"><span style="font-size: 18px; font-weight: 500; line-height: 1.7; color: rgba(43, 39, 72, 1);"><?php echo esc_html( $all_data['currency_symbol'] ); ?><?php echo esc_html( $all_data['amount'] ); ?></span></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <?php endif; ?>

            <!-- footer  -->
            <?php if ( $all_data['email_content_footer_text_show'] || $all_data['is_elementor_form'] ) : ?>
            <tr>
                <td>
                    <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
                        <?php if($type === 'admin') :  // till bp user dashboard widget?> 
                        <h2 style="font-size: 32px; font-weight: 400; color: rgba(43, 39, 72, 1); margin: 0 0 24px; line-height: 1.2em"><?php esc_html_e('You can also find the transaction details by visiting the link below.', 'better-payment') ?></h2>
                        <a style="cursor: pointer; display: inline-block; background: rgba(110, 88, 247, 1); color: #fff; font-size: 22px; font-weight: 500; padding: 20px 90px; border-radius: 8px; margin-bottom: 70px; text-decoration: none;" class="bp-footer__btn" href="<?php echo esc_url( $all_data['view_transaction_link'] ); ?>"><?php esc_html_e( 'View Transaction', 'better-payment'); ?></a>
                        <?php endif; ?>

                        <?php if ( $all_data['email_logo_url'] ) : ?>
                        <h4 style="font-size: 18px; font-weight: 500; line-height: 1.7; color: rgba(128, 136, 166, 1); margin-bottom: 10px;"><?php esc_html_e( 'Powered By', 'better-payment'); ?></h4>
                        <img style="width: 100%; height: auto; max-width: 235px;" src="<?php echo esc_url( $all_data['email_logo_url'] ); ?>" alt="logo">
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
<?php endif; ?>