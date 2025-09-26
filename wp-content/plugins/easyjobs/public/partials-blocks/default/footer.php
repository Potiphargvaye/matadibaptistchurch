<?php
/**
 * Render jobs landing page for shortcode
 *
 * @since 1.0.0
 * @package easyjobs
 */
echo Easyjobs_Helper::generate_block_style($atts);
?>
<?php if( empty( $company->ejel_hide_company_gallery ) ) : ?>
	<?php if ( $company->show_life && ! empty( $company->showcase_photo ) ) : ?>
<div <?php echo wp_kses_data( $wrapper_attributes); ?>>
	<div class="easyjobs-shortcode-wrapper">
	<?php if ( ! empty( $company ) ) : ?>
		<div class="easyjobs-details">
			<div class="ej-job-body">
				
					<div class="ej-section">
						<div class="ej-section-title">
							<span class="ej-section-title-icon"><i class="easyjobs-icon easyjobs-briefcase"></i></span>
							<span class="ej-section-title-text">
								<?php if( empty( $atts['lifeAtTitle'] ) ) : ?>
									<?php echo esc_html__( 'Life at ', 'easyjobs' ) . esc_html( $company->name ); ?>
								<?php else : ?>
									<?php esc_html_e( $atts['lifeAtTitle'], 'easyjobs' ); ?>
								<?php endif; ?>
							</span>
						</div>
						<div class="ej-section-content">
							<div class="ej-company-showcase">
								<div class="ej-showcase-inner">
									<div class="ej-showcase-left">
										<div class="ej-showcase-image">
											<div class="ej-image">
												<img src="<?php echo esc_url( $company->showcase_photo[0] ); ?>" alt="<?php echo esc_attr( $company->name ); ?>">
											</div>
										</div>
									</div>
									<?php if ( count( $company->showcase_photo ) > 1 ) : ?>
										<div class="ej-showcase-right">
											<?php foreach ( $company->showcase_photo as $key => $photo ) : ?>
												<?php
												if ( $key === 0 ) {
													continue;
												}
												?>
												<div class="ej-showcase-image">
													<div class="ej-image">
														<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $company->name ); ?>">
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				

			</div>
		</div>
	<?php else : ?>
		<h3>
			<?php esc_html_e( 'Failed to connect api', 'easyjobs' ); ?>
		</h3>
	<?php endif; ?>
	</div>
</div>
<?php endif; ?>
				<?php endif; ?>
