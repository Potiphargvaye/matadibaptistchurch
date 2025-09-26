<?php
/**
 * Header for eayjobs admin pages
 *
 * @link       https://easy.jobs
 * @since      1.0.5
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/admin/partials
 */

$company = Easyjobs_Helper::get_company_info(true);
?>
<header class="content-area__header d-flex justify-content-between">
	<div>
		<div class="ej-logo">
			<img src="<?php echo esc_url( EASYJOBS_ADMIN_URL ); ?>/assets/img/logo-blue.svg" alt="">
			<?php if ( $company->is_pro ) : ?>
				<?php if ( $company->subscription_expired ) : ?>
					<span class="ej-pro-label ej-is-expired">pro(expired)</span>
				<?php else : ?>
					<span class="ej-pro-label">pro</span>
				<?php endif; ?>
			<?php else : ?>
				<span class="ej-free-label">free</span>
			<?php endif; ?>
		</div>
		<small class="easyjobs-version"><?php esc_html_e( 'Version: ', 'easyjobs' ); ?><?php echo esc_html( EASYJOBS_VERSION ); ?> </small>
	</div>
	<div class="d-flex">
		<a href="#" class="button success-button ej-sync-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'easyjobs_sync' ) ); ?>">
			<i class="dashicons dashicons-image-rotate"></i>
			<?php esc_html_e( 'Sync data', 'easyjobs' ); ?>
		</a>
		<a href="<?php echo ! empty( $company->company_easyjob_url ) ? esc_url( $company->company_easyjob_url ) : '#'; ?>" target="_blank" class="button info-button">
			<?php esc_html_e( 'View Company Page', 'easyjobs' ); ?>
		</a>
	</div>
</header>
<?php if ( ! empty( $company ) && ! empty( $company->subscription_expired ) ) : ?>
	<div class="ej-admin-notice ej-danger-notice">
		<h4>
			Your subscription package expired, please
			<a href="<?php echo ! empty( $company->payment_link ) ? esc_url( $company->payment_link ) : esc_url( EASYJOBS_APP_URL . '/my-account/payment-history' ); ?>" target="_blank"> pay now </a>
			to continue.
		</h4>
	</div>
<?php endif; ?>

<?php
$verification_status = Easyjobs_Helper::get_verification_status();
if ( null !== $verification_status && false === $verification_status ) :
	?>
	<div class="ej-admin-notice ej-warning-notice">
		<h4>
			<a href="https://easy.jobs/docs/verify-your-company-profile/" target="_blank"class="link-help">How to verify?</a>
			Your company is not verified, please verify your company.
		</h4>
	</div>
<?php endif; ?>
