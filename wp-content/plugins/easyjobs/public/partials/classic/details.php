<?php
/**
 * Render job details for shortcode
 *
 * @since 1.0.0
 */

global $post;
?>
<div class="easyjobs-shortcode-wrapper ej-template-classic translate">
	<?php if ( ! empty( $company ) && ! empty( $job ) ) : ?>
		<?php
		/**
		 * Hooks anything before job details
		 *
		 * @since 1.0.0
		 */
		do_action( 'easyjobs_before_job_details' );
		?>
        <div class="pb100 mt60">
            <div class="ej-container">
                <div class="ej-row">
                    <div class="ej-col-lg-7">
                        <div class="job__details easyjobs-details">
                            <h1 class="job__title"><?php echo esc_html($job->title)?></h1>
                            <div class="meta">
								<?php if ( $job->is_remote || (!empty($job->city) || !empty($job->country))) { ?>
									<span class="label label__primary">
										<i class="easyjobs-icon easyjobs-map-maker"></i>
										<?php if ( $job->is_remote  ) : ?>
											<?php esc_html_e( 'Anywhere', 'easyjobs' ); ?>
										<?php else : ?>
											<?php if(!empty($job->city) || !empty($job->country)): ?>
												<?php echo ! empty( $job->city ) ? esc_html( $job->city->name ) . ', ' : ''; ?>
												<?php echo ! empty( $job->country ) ? esc_html( $job->country->name ) : ''; ?>
											<?php else: ?>
												'N/A'
											<?php endif ?>
										<?php endif ?>
									</span>
								<?php } ?>

								<?php if ( ! empty( $job->job_type ) ) { ?>
									<span class="label label__primary"> <i class="easyjobs-icon"></i>
										<?php echo ucfirst(esc_html( $job->job_type )); ?>
									</span>
								<?php } ?>

								<?php if ( ! empty( $job->salary ) ) { ?>
									<span class="label label__primary"> <i class="easyjobs-icon easyjobs-credit-card"></i>
										<?php echo esc_html( $job->salary ) . ' ' . esc_html( $job->salary_type->name ); ?>
									</span>
								<?php } ?>
                            </div>
							<?php if ( ! empty( $job->skills ) ) : ?>
                            <div class="job__details__block ej-content-block mt60">
                                <h3 class="title">Skills</h3>
                                <div class="required__skill">
                                    <ul>
                                        <?php foreach ($job->skills as $skill): ?>
                                        <li><?php echo esc_html($skill->name);?></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                            <?php endif;?>
							<?php if ( ! is_null( $job->requirements ) && $job->requirements != "<p><br></p>" ) : ?>
                            <div class="job__details__block ej-content-block mt60">
                                <h3 class="title"><?php echo esc_html( get_theme_mod( 'easyjobs_single_job_description_title', __( 'Description','easyjobs' ) ) ); ?></h3>
                                <div class="company__description">
									<?php
									echo wp_kses(
										$job->requirements,
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
									);
									?>
                                </div>
                            </div>
							<?php endif;?>
							<?php if ( ! is_null( $job->responsibilies ) && $job->responsibilies != "<p><br></p>" ) : ?>
                            <div class="job__details__block ej-content-block mt60">
                                <h3 class="title">
									<?php echo esc_html( get_theme_mod( 'easyjobs_single_job_responsibility_title' ,__('Job Responsibilities', 'easyjobs')) ); ?>
                                </h3>
                                <div class="company__description">
									<?php
									echo wp_kses(
										$job->responsibilies,
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
									);
									?>
                                </div>
                            </div>
							<?php endif;?>
                            <?php if(!empty($job->other_benefits)) : ?>
                            <div class="job__details__block ej-content-block mt60">
                                <h3 class="title">
									<?php echo esc_html( get_theme_mod( 'easyjobs_single_job_benefits_title', __('Benefits', 'easyjobs')) ); ?>
                                </h3>
                                <div class="company__description">
									<?php
									echo wp_kses(
										$job->other_benefits,
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
									);
									?>
                                </div>
                            </div>
							<?php endif;?>
                        </div>
                    </div>
                    <div class="ej-col-lg-5">
                        <div class="job__more__details">
                            <a href="<?php echo $job->apply_url?>" class="button button__success button__radius" target="_blank">
								<?php _e('Apply Now', 'easyjobs')?>
                            </a>
                            <p class="deadline">
                                <i class="easyjobs-icon easyjobs-calender"></i>
                                <span>
                                    <?php _e('Deadline', 'easyjobs')?>:
                                </span> <?php echo esc_html( date( 'd F, Y', strtotime( str_replace( ', ', '', $job->expire_at ) ) ) ); ?>
                            </p>
							<?php if ( ! empty( $job->experience_level->name ) ) { ?>
								<p class="deadline">
									<i class="easyjobs-icon easyjobs-briefcase-2"></i>
									<span>
										<?php _e('Experience', 'easyjobs')?>:
									</span> <?php echo esc_html($job->experience_level->name); ?>
								</p>
							<?php } ?>
                            <div class="infos">
                                <div class="info">
                                    <p>
                                        <?php
                                            if($job->employment_type->id == 99 && trim(strtolower($job->employment_type->name)) == 'other'){
                                                echo esc_html($job->meta->employment_type_other);
                                            }else{
                                                echo esc_html($job->employment_type->name);
                                            }
                                        ?>
                                    </p>
                                    <span><?php _e('Job Type', 'easyjobs')?></span>
                                </div>
								<?php if ( ! empty( $job->vacancies ) ) { ?>
									<div class="info">
										<p><?php echo ! empty( $job->vacancies ) ? esc_html( $job->vacancies ) : 'N/A'; ?></p>
										<span><?php _e('No of vacancies', 'easyjobs')?></span>
									</div>
								<?php } ?>
                            </div>
							<?php if ( ! empty( $job->office_time ) ) { ?>
								<p class="office__time">
									<i class="easyjobs-icon easyjobs-clock"></i>
									<span><?php _e('Office Time', 'easyjobs')?>:</span>
									<?php echo esc_html( $job->office_time ); ?>
								</p>
							<?php } ?>

							<?php if ( $company->description != '<p><br></p>' ) { ?>
								<div class="about__company">
									<?php
										echo ! empty( $company->description ) ? esc_html(wp_trim_words(wp_strip_all_tags($company->description)), 80,'') : '';
									?>
									<?php if ( ! empty( $company->description ) && strlen( $company->description ) > 80 ) { ?>
										<a href="#" style="color:#ff5f74;" class="ej-modal-trigger">Read more</a>
									<?php } ?>
									</p>
								</div>
							<?php } ?>
							<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing', false ) ) : ?>
                            <div class="share__options">
                                <p> <?php _e('Share on', 'easyjobs')?>:</p>
                                <ul>
									<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_fb', false ) ) : ?>

                                        <li>
                                            <a href="<?php echo !empty($job->social_links->facebook) ? esc_url($job->social_links->facebook) : esc_url('https://www.facebook.com/sharer.php?u=' . get_the_permalink()); ?>" class="ej-social-button social-button semi-button-primary facebook">
                                                <i class="easyjobs-icon easyjobs-facebook"></i>
                                            </a>
                                        </li>
									<?php endif; ?>
									<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_twitter', false ) ) : ?>
                                        <li>
                                            <a href="<?php echo !empty($job->social_links->twitter) ? esc_url($job->social_links->twitter) : esc_url('https://twitter.com/intent/tweet?url=' . get_the_permalink() . '&text=' . $job->title); ?>" class="ej-social-button social-button semi-button-primary twitter">
                                                <i class="easyjobs-icon easyjobs-twitter"></i>
                                            </a>
                                        </li>
									<?php endif; ?>
									<?php
									if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_linkedin', false ) ) :
										?>
                                        <li>
                                            <a href="<?php echo !empty($job->social_links->linkedIn) ? esc_url($job->social_links->linkedIn) : esc_url('http://www.linkedin.com/shareArticle?url=' . get_the_permalink() . '&title=' . $job->title . '&mini=true'); ?>" class="ej-social-button social-button semi-button-primary linkedin">
                                                <i class="easyjobs-icon easyjobs-linkedin"></i>
                                            </a>
                                        </li>
									<?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ej-modal">
            <div class="ej-modal-inner" role="document">
                <div class="ej-modal-content">
                    <div class="ej-modal-header">
                        <h5 class="ej-modal-title">
                            <?php _e('Company Description', 'easyjobs');?>
                        </h5>
                        <button class="ej-modal-close">
                            <span >×</span>
                        </button>
                    </div>
                    <div class="ej-modal-body">
                        <div class="company__description">
                            <?php echo $company->description?>
                        </div>
                    </div>
                    <div class="ej-modal-footer">
                        <button type="button" class="btn btn-secondary ej-modal-close">
                            <?php _e('Close', 'easyjobs');?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
		<?php
		/**
		 * Hooks anything after job details
		 *
		 * @since 1.0.0
		 */
		do_action( 'easyjobs_after_job_details' );
		?>
	<?php endif; ?>
</div>
