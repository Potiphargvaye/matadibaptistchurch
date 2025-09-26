<?php
/**
 * Render job details for shortcode
 * Theme - Elegant
 * @since 1.0.0
 */

global $post;
?>
<div class="easyjobs-shortcode-wrapper ej-template-elegant easyjobs-details translate">
	<?php if ( ! empty( $company ) && ! empty( $job ) ) : ?>
		<?php
		/**
		 * Hooks anything before job details
		 *
		 * @since 1.0.0
		 */
		do_action( 'easyjobs_before_job_details' );
		?>
        <!--Start your html here-->
        <div class="ej-hero__wrap hero__bg "
			<?php echo !empty( $job->banner_image ) && get_theme_mod( 'easyjobs_single_display_job_banner', true ) && ! $job->hideCoverPhoto ? 'style="background-image: url('.$job->banner_image.');"' : ''; ?>>
            <div class="ej-container">
                <div class="ej-row">
                    <div class="ej-col">
                        <div class="ej-hero">
                            <h1 class="job__title"><?php echo esc_html( ucfirst($job->title) ); ?></h1>
                            <div class="job__infos__block">
                                <div class="job__informations">
                                    <div class="location_time">
                                        <?php if ( ! empty( $job->experience_level->name ) ) {?>
                                            <p>
                                                <span><i class="easyjobs-icon easyjobs-briefcase-2"></i>  
                                                <?php echo $job->experience_level->name; ?>
                                            </p>
                                        <?php } ?>
                                        <p>
                                            <?php if ( $job->is_remote || !empty($job->city) || !empty($job->country) ) { ?>
                                                <span><i class="easyjobs-icon easyjobs-map-maker"></i>
                                                <?php if ( $job->is_remote ) : ?>
                                                    <span><?php esc_html_e( 'Anywhere', 'easyjobs' ); ?></span>
                                                <?php else : ?>
                                                    <span>
                                                        <?php if(!empty($job->city) || !empty($job->country)): ?>
                                                            <?php echo ! empty( $job->city ) ? ucfirst( esc_html( $job->city->name ) ) . ', ' : ''; ?>
                                                            <?php echo ! empty( $job->country ) ? ucfirst( esc_html( $job->country->name ) ) : ''; ?>
                                                        <?php else: ?>
                                                            N/A
                                                        <?php endif ?>
                                                    </span>
                                                <?php endif ?>
                                                </span>
                                            <?php } ?>
                                            <?php if ( ! empty( $job->salary ) ) { ?>
                                                <span>
                                                    <i class="easyjobs-icon easyjobs-credit-card"></i>
                                                    <?php echo esc_html( $job->salary ) . ' ' . esc_html( $job->salary_type->name ); ?>
                                                </span>
                                            <?php } ?>
                                        </p>
                                        <p>
                                            <span>
                                                <i class="easyjobs-icon easyjobs-clock"></i>
                                                <?php echo ! empty($job->office_time) ? esc_html( $job->office_time ) : ''; ?>
                                                <?php echo ! empty($job->job_type) ? ' (' . ucfirst( esc_html( $job->job_type ) ) . ')' : ''; ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="job__info">
                                        <?php if ( ! empty( $job->vacancies ) ) { ?>
                                            <div class="info__item">
                                                <p><?php echo ! empty( $job->vacancies ) ? esc_html( $job->vacancies ) : 'N/A'; ?></p>
                                                <span><?php esc_html_e( 'No of vacancies' , 'easyjobs'); ?></span>
                                            </div>
                                        <?php } ?>
                                        <div class="info__item">
                                            <p>
												<?php
												if($job->employment_type->id == 99 && trim(strtolower($job->employment_type->name)) == 'other'){
													echo esc_html($job->meta->employment_type_other);
												}else{
													echo esc_html($job->employment_type->name);
												}
												?>
                                            </p>
                                            <span><?php esc_html_e( 'Job Type' , 'easyjobs'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="meta">
                                    <a href="<?php echo esc_url( $job->apply_url ); ?>" class="button radius-15" target="_blank">
										<?php esc_html_e( 'Apply now', 'easyjobs' ); ?>
                                    </a>
                                    <p class="deadline">
                                        <i class="easyjobs-icon easyjobs-calender"></i>
										<?php esc_html_e( 'Deadline', 'easyjobs' ); ?>
                                        : <?php echo esc_html( date( 'd F, Y', strtotime( str_replace( ', ', '', $job->expire_at ) ) ) ); ?>
                                    </p>
									<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing', false ) ) : ?>
                                    <div class="share__options">
                                        <span>Share on:</span>
                                        <ul>
											<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_fb') ) : ?>

                                                <li>
                                                    <a href="<?php echo !empty($job->social_links->facebook) ? esc_url($job->social_links->facebook) : esc_url('https://www.facebook.com/sharer.php?u=' . get_the_permalink()); ?>" class="social-button semi-button-primary facebook">
                                                        <i class="easyjobs-icon easyjobs-facebook"></i>
                                                    </a>
                                                </li>
											<?php endif; ?>
											<?php if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_twitter') ) : ?>
                                                <li>
                                                    <a href="<?php echo !empty($job->social_links->twitter) ? esc_url($job->social_links->twitter) : esc_url('https://twitter.com/intent/tweet?url=' . get_the_permalink() . '&text=' . $job->title); ?>" class="social-button semi-button-primary twitter">
                                                        <i class="easyjobs-icon easyjobs-twitter"></i>
                                                    </a>
                                                </li>
											<?php endif; ?>
											<?php
											if ( ! get_theme_mod( 'easyjobs_single_disable_social_sharing_linkedin' ) ) :
												?>
                                                <li>
                                                    <a href="<?php echo !empty($job->social_links->linkedIn) ? esc_url($job->social_links->linkedIn) : esc_url('http://www.linkedin.com/shareArticle?url=' . get_the_permalink() . '&title=' . $job->title . '&mini=true'); ?>" class="social-button semi-button-primary linkedin">
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
            </div>
        </div>
        <div class="ej-container">
            <div class="ej-row">
                <div class="ej-col">
                    <div class="job__details pb100 pt60">
						<?php if ( ! empty( $job->skills ) ) : ?>
                        <div class="job__details__block ej-content-block">
                            <h3 class="title">
                                <span><i class="ej-icon ej-skill"></i></span>
                                <?php esc_html_e( 'Skills ', 'easyjobs' ); ?>
                            </h3>
                            <div class="">
                                <div class="skills">
									<?php foreach ( $job->skills as $key => $skill ) : ?>
                                    <span class="label label__primary"><?php echo esc_html($skill->name)?></span>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
						<?php if ( ! is_null( $job->requirements ) && $job->requirements != '<p><br></p>' ) : ?>
                        <div class="job__details__block ej-content-block mt60">
                            <h3 class="title"><?php echo esc_html( get_theme_mod( 'easyjobs_single_job_description_title', __( 'Description', 'easyjobs' ) ) ); ?></h3>
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
                        <?php endif; ?>
						<?php if ( ! is_null( $job->responsibilies ) && $job->responsibilies != '<p><br></p>' ) : ?>
                        <div class="job__details__block ej-content-block mt60">
                            <h3 class="title">
								<?php echo esc_html( get_theme_mod( 'easyjobs_single_job_responsibility_title', __('Job Responsibilities', 'easyjobs')) ); ?>
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
						<?php if ( ! empty( $job->other_benefits )) : ?>
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
