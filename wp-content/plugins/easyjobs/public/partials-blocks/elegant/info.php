<?php
/**
 * Render landing page for elegant template
 * @since 1.0.0
 */

global $post;
echo Easyjobs_Helper::generate_block_style($atts);
?>
<div <?php echo wp_kses_data( $wrapper_attributes); ?>>
	<div class="easyjobs-block-info">
		<div class="easyjobs-shortcode-wrapper ej-template-elegant">
			<?php if(!empty($company)): ?>
			<div class="pt150 pb100">
				<?php if ( empty( $company->ejel_hide_company_details ) ) : ?>
				<div class="ej-container">
					<div class="ej-row">
						<div class="ej-col">
							<div class="about__company">
								<?php if ( ! get_theme_mod( 'easyjobs_landing_hide_company_info', false ) ) : ?>
								<div class="ej-company-info">
									<?php if ( ! get_theme_mod( 'easyjobs_landing_hide_company_logo', false ) ) : ?>
										<div class="logo">
											<img src="<?php echo esc_url( $details->logo ); ?>" alt="">
										</div>
									<?php endif; ?>
									<div class="info">
										<h4 class="name">
											<a href="<?php echo !empty($details->website) ? esc_url( $details->website ) : '#'; ?>" target="_blank">
												<?php echo esc_html( $details->name ); ?>
											</a>
											<?php if (isset($details->badge)): ?>
												<span class="tooltip">
													<svg width="25" viewBox="0 0 44 43" fill="none" xmlns="http://www.w3.org/2000/svg">
														<g clip-path="url(#clip0_101_41)">
															<path d="M21.6843 3.60303C23.0779 3.60311 24.4237 4.11073 25.4701 5.03099L25.7461 5.29078L26.9966 6.54136C27.3398 6.88234 27.788 7.09759 28.2687 7.15232L28.5106 7.16665H30.3023C31.7664 7.16657 33.1752 7.72667 34.2396 8.7321C35.304 9.73753 35.9434 11.1121 36.0266 12.5739L36.0356 12.9V14.6917C36.0356 15.1754 36.2004 15.6466 36.4978 16.0229L36.6591 16.202L37.9079 17.4526C38.943 18.4818 39.5465 19.8671 39.5954 21.326C39.6444 22.7848 39.135 24.2074 38.1713 25.3037L37.9115 25.5796L36.6609 26.8302C36.3199 27.1734 36.1047 27.6216 36.0499 28.1023L36.0356 28.3442V30.1358C36.0357 31.6 35.4756 33.0087 34.4701 34.0731C33.4647 35.1375 32.0902 35.7769 30.6283 35.8602L30.3023 35.8692H28.5106C28.0275 35.8693 27.5586 36.0321 27.1794 36.3314L27.0002 36.4927L25.7496 37.7414C24.7204 38.7765 23.3352 39.3801 21.8763 39.429C20.4174 39.4779 18.9948 38.9686 17.8986 38.0048L17.6226 37.745L16.3721 36.4944C16.0288 36.1535 15.5807 35.9382 15.1 35.8835L14.8581 35.8692H13.0664C11.6022 35.8692 10.1935 35.3091 9.12912 34.3037C8.06472 33.2983 7.42533 31.9237 7.34205 30.4619L7.33309 30.1358V28.3442C7.33294 27.8611 7.1701 27.3921 6.87084 27.0129L6.70959 26.8338L5.4608 25.5832C4.42572 24.554 3.82219 23.1687 3.77325 21.7098C3.72431 20.251 4.23365 18.8284 5.19743 17.7321L5.45722 17.4562L6.7078 16.2056C7.04878 15.8624 7.26403 15.4142 7.31876 14.9335L7.33309 14.6917V12.9L7.34205 12.5739C7.42205 11.1682 8.01648 9.84116 9.01204 8.8456C10.0076 7.85004 11.3347 7.25561 12.7403 7.17561L13.0664 7.16665H14.8581C15.3412 7.16649 15.8101 7.00366 16.1893 6.7044L16.3685 6.54315L17.6191 5.29436C18.1518 4.75845 18.7852 4.33314 19.4829 4.04288C20.1805 3.75262 20.9287 3.60314 21.6843 3.60303ZM28.3081 16.6499C27.9721 16.3141 27.5165 16.1254 27.0414 16.1254C26.5663 16.1254 26.1107 16.3141 25.7747 16.6499L19.8748 22.5481L17.5581 20.2333L17.3897 20.0846C17.0296 19.8061 16.577 19.6752 16.1239 19.7184C15.6707 19.7615 15.251 19.9756 14.9499 20.317C14.6489 20.6584 14.489 21.1016 14.5029 21.5566C14.5168 22.0116 14.7034 22.4443 15.0247 22.7667L18.6081 26.35L18.7765 26.4987C19.1212 26.7661 19.5516 26.8986 19.9871 26.8712C20.4225 26.8438 20.833 26.6585 21.1415 26.35L28.3081 19.1834L28.4568 19.0149C28.7243 18.6702 28.8567 18.2398 28.8293 17.8043C28.8019 17.3689 28.6166 16.9585 28.3081 16.6499Z" fill="<?php echo $details->badge->color; ?>"/>
														</g>
														<defs>
															<clipPath id="clip0_101_41">
																<rect width="43" height="43" fill="white" transform="translate(0.166504)"/>
															</clipPath>
														</defs>
													</svg>
													<span class="tooltiptext"><?php echo $details->badge->label; ?></span>
												</span>
											<?php endif; ?>
										</h4>
										<?php if ( ! empty( $details->address->city->name ) || !empty( $details->address->country->name ) ) { ?>
											<span class="location">
												<i class="easyjobs-icon easyjobs-map-maker"></i>
												<?php
												echo ! empty( $details->address->city->name ) ? esc_html( $details->address->city->name )
													. ', ' : ''
												?>
												<?php echo ! empty( $details->address->country->name ) ? esc_html( $details->address->country->name ) : ''; ?>
											</span>
										<?php } ?>
									</div>
									<?php if ( ! get_theme_mod( 'easyjobs_landing_hide_company_website_button' ) ) : ?>
										<div class="ej-header-tools">
											<a href="<?php echo !empty($details->website) ? esc_url( $details->website ) : '#'; ?>" class="ej-btn ej-info-btn">
											<?php empty ( $details->ejel_website_link_text ) ? esc_html_e( 'Explore company website', 'easyjobs' ) : esc_html_e( $details->ejel_website_link_text, 'easyjobs' ); ?>
											</a>
										</div>
									<?php endif; ?>
								</div>
								<?php endif;?>
								<?php if ( ! empty( $details->description ) && ! get_theme_mod( 'easyjobs_landing_hide_company_description', false )) : ?>
								<div class="ej-company-description">
									<?php
										echo wp_kses(
										$details->description,
										array(
											'div'    => array(
												'class' => array(),
												'style' => array(),
											),
											'p'      => array(
												'class' => array(),
												'style' => array(),
											),
											'h1'     => array(
												'class' => array(),
												'style' => array(),
											),
											'h2'     => array(
												'class' => array(),
												'style' => array(),
											),
											'h3'     => array(
												'class' => array(),
												'style' => array(),
											),
											'h4'     => array(
												'class' => array(),
												'style' => array(),
											),
											'span'   => array(
												'class' => array(),
												'style' => array(),
											),
											'strong' => array(
												'class' => array(),
												'style' => array(),
											),
											'em'     => array(
												'class' => array(),
												'style' => array(),
											),
											'b'      => array(
												'class' => array(),
												'style' => array(),
											),
											'a'      => array(
												'class' => array(),
												'style' => array(),
												'href'  => array(),
												'title' => array(),
											),
											'ul'  => array(
												'class' => array(),
												'style' => array(),
											),
											'li'  => array(
												'class' => array(),
												'style' => array(),
											),
											'ol'  => array(
												'class' => array(),
												'style' => array(),
											),
											'blockquote'  => array(
												'class' => array(),
												'style' => array(),
											),
										)
									)
									?>
								</div>
								<?php endif; ?>
								<?php if ( !$details->remove_cover_photo && ! empty( $details->cover_photo ) ) : ?>
									<div class="about__company__bottom">
										<div class="ej-job-cover">
											<img src="<?php echo esc_url( $details->cover_photo[0] ); ?>" alt="<?php echo esc_html( $details->name ); ?>">
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
