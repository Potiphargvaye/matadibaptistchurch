<?php 
$allowed_pro_banners = [
    'layout-4-pro',
    'layout-5-pro',
    'layout-6-pro',
];

if ( in_array( $better_payment_form_layout, $allowed_pro_banners )  ) : ?>
<p>
    <a class="width-100" target="_blank" href="//wpdeveloper.com/in/upgrade-better-payment-pro">
        <img width="100%" src="<?php echo esc_url(BETTER_PAYMENT_ASSETS . '/img/'. $better_payment_form_layout .'-banner.jpg'); ?>" alt="layout-pro-banner">
    </a>
</p>
<?php endif;