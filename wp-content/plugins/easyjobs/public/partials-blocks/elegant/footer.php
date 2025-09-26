<?php
/**
 * Render landing page for elegant template
 * @since 1.0.0
 */
echo Easyjobs_Helper::generate_block_style($atts);
?>
<?php if( empty( $company->ejel_hide_company_gallery ) ) : ?>
	<?php if ( $company->show_life && ! empty( $company->showcase_photo ) ) : ?>
<div <?php echo wp_kses_data( $wrapper_attributes); ?>>
	<div class="easyjobs-shortcode-wrapper ej-template-elegant">
		<?php if(!empty($company)): ?>
		<div class="pt150 pb100">
			
			<div class="mt60">
				<div class="ej-container">
					<div class="ej-row">
						<div class="ej-col">
							<div class="section__header">
								<h2>
									<?php if( empty( $company->ejel_galelry_section_title ) ) : ?>
										<?php if ( ! empty( $showcase_heading = get_theme_mod( 'easyjobs_landing_showcase_heading' ) ) ) : ?>
											<?php echo esc_html( $showcase_heading ); ?>
										<?php else : ?>
											<?php echo esc_html__( 'Life at ', 'easyjobs' ) . esc_html( $company->name ); ?>
										<?php endif; ?>
									<?php else : ?>
										<?php esc_html_e( $company->ejel_galelry_section_title, 'easyjobs' ); ?>
									<?php endif; ?>
								</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="image__gallery">
					<?php foreach ( $company->showcase_photo as $photo ) : ?>
					<div class="item">
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $company->name ); ?>">
					</div>
					<?php endforeach;?>
				</div>
			</div>
			

		</div>
		<?php endif;?>
	</div>
</div>
<?php endif; ?>
			<?php endif; ?>