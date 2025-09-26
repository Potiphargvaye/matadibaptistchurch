<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Easyjobs_Elementor_Template {

	public function company_info_template() {

		$company      = $this->company_info;
		$setting      = $this->get_settings();
		$company_name = trim( $setting['easyjobs_company_name'] );
		$company_name = ( empty( $company_name ) && ! empty( $company->name ) ) ? $company->name : $company_name;
		?>
        <div class="easyjobs-shortcode-wrapper">
			<?php if ( ! empty( $company ) ) : ?>

                <div class="easyjobs-details">
					<?php if ( $setting['easyjobs_company_details_control'] !== 'yes' ) : ?>
						<?php if ( ! empty( $company->cover_photo ) ) : ?>
                            <div class="ej-job-cover">
                                <img src="<?php echo esc_url( $company->cover_photo[0] ); ?>" alt="<?php echo esc_attr( $company->name ); ?>">
                            </div>
						<?php else : ?>
                            <div class="ej-no-cover-photo"></div>
						<?php endif; ?>
                        <div class="ej-header">
                            <div class="ej-company-highlights">
                                <div class="ej-company-info">
									<?php if ( ! empty( $company->logo ) ) : ?>
                                        <div class="logo">
                                            <img src="<?php echo esc_url( $company->logo ); ?>" alt="">
                                        </div>
									<?php endif; ?>
                                    <div class="info">
                                        <h2 class="name"><?php echo esc_html( $company_name ); ?></h2>
										<?php if ( isset( $company->address ) ) : ?>
                                            <p class="location">
                                                <i class="easyjobs-icon easyjobs-map-maker"></i>
                                                <span>
                                            <?php
                                            echo ! empty( $company->address->city->name ) ?
	                                            esc_html( $company->address->city->name ) : ''
											?>
                                                ,
                                            <?php
                                            echo ! empty( $company->address->country->name ) ?
	                                            esc_html( $company->address->country->name ) : '';
											?>
                                        </span>
                                            </p>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="ej-header-tools">
                                    <a href="<?php echo ! empty( $company->website ) ? esc_url( $company->website ) : '#'; ?>"
                                       class="ej-btn
                                 ej-info-btn">
										<?php echo esc_html( $setting['easyjobs_website_link_text'] ); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="ej-company-description">
								<?php
                                echo ! empty( $company->description ) ? wp_kses(
                                    $company->description,
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
                                    )
                                ) : '';
                                ?>
                            </div>
                        </div>
					<?php endif; ?>

                    <div class="ej-job-body">
						<?php if ( $setting['easyjobs_job_list_control'] !== 'yes' ) : ?>
                            <div class="ej-section">
                                <div class="ej-section-title">
                                    <span class="ej-section-title-icon"><i class="easyjobs-icon easyjobs-briefcase"></i></span>
                                    <span class="ej-section-title-text"><?php echo esc_html( $setting['easyjobs_joblist_heading'] ); ?></span>
                                </div>
                                <div class="ej-section-content">
									<?php $this->job_list_template(); ?>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( ! empty( $company->showcase_photo ) && $setting['easyjobs_company_gallery_control'] !== 'yes' ) : ?>
                            <div class="ej-section">
                                <div class="ej-section-title">
                                    <span class="ej-section-title-icon"><i class="easyjobs-icon easyjobs-briefcase"></i></span>
                                    <span class="ej-section-title-text"><?php echo esc_html( $setting['easyjobs_gallery_section_text'] ) . ' ' . esc_html( $company_name ); ?></span>
                                </div>
                                <div class="ej-section-content">
                                    <div class="ej-company-showcase">
                                        <div class="ej-showcase-inner">
                                            <div class="ej-showcase-left">
                                                <div class="ej-showcase-image">
                                                    <div class="ej-image">
                                                        <img src="<?php echo esc_url( $company->showcase_photo[0] ); ?>"
                                                             alt="
                                                             <?php
																echo ! empty( $company->name ) ? esc_attr( $company->name ) : '';
																?>
                                                             ">
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
                                                                <img src="<?php echo esc_url( $photo ); ?>"
                                                                     alt="
                                                                     <?php
																		echo ! empty( $company->name ) ?
																	     esc_attr( $company->name ) : '';
																		?>
                                                                         ">
                                                            </div>
                                                        </div>
													<?php endforeach; ?>
                                                </div>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
			<?php else : ?>
                <h3>
					<?php esc_html_e( 'No open jobs right now', 'easyjobs' ); ?>
                </h3>
			<?php endif; ?>
        </div>
		<?php
	}

	public function job_list_template() {

		$jobs_obj = new Easyjobs_Admin_Jobs();
		$settings = $this->get_settings();
		$jobs     = $this->get_published_jobs(
            array(
				'rows'   => $settings['easyjobs_jobs_per_page']['size'],
				// 'orderby' => $settings['easyjobs_job_list_order_by'] . ':' . $settings['easyjobs_job_list_sort_by'],
				'orderby' => $settings['easyjobs_job_list_order_by'],
				'order' => $settings['easyjobs_job_list_sort_by'],
				'status' => !empty( $settings['easyjobs_show_open_job'] ) && 'yes' ===  $settings['easyjobs_show_open_job'] ? 'active' : '',
            )
        );
		$job_with_page_id     = Easyjobs_Helper::get_job_with_page( $jobs );
		/*$new_job_with_page_id = Easyjobs_Helper::   create_pages_if_required( $jobs, $job_with_page_id );
		$job_with_page_id    += $new_job_with_page_id;*/
		?>
		<?php if ( ! empty( $jobs ) ) : ?>
            <div class="easyjobs-shortcode-wrapper">
                <div class="ej-job-list">
					<?php foreach ( $jobs as $key => $job ) : ?>
						<?php
						$current_date = time();
						$end_date     = strtotime( str_replace( ',', '', $job->expire_at ) );

						if ( $job->is_expired && $settings['easyjobs_show_open_job'] === 'yes' ) {
							continue;
						}
						?>
                        <div class="ej-job-list-item">
                            <div class="ej-job-list-item-inner">
                                <div class="ej-job-list-item-col">
                                    <h2 class="ej-job-title">
                                        <a href="<?php echo esc_url( get_the_permalink( $job_with_page_id[ $job->id ] ) ); ?>"><?php echo esc_html( $job->title ); ?></a>
                                    </h2>
                                    <div class="ej-job-list-info">
                                        <div class="ej-job-list-info-block ej-job-list-company-name">
                                            <i class="easyjobs-icon easyjobs-briefcase-2"></i>
                                            <a href="<?php echo esc_url( $job->company_easyjob_url ); ?>" target="_blank">
												<?php echo esc_html( $job->company_name ); ?>
                                            </a>
                                        </div>
                                        <div class="ej-job-list-info-block ej-job-list-location">
                                            <i class="easyjobs-icon easyjobs-map-maker"></i>
                                            <?php if ( $job->is_remote ) : ?>
                                                <span><?php esc_html_e( 'Anywhere (Remote)', 'easyjobs' ); ?></span>
                                            <?php else : ?>
                                                <span>
                                                    <?php if(empty($job->job_address->city->name) && empty($job->job_address->country->name)): ?>
                                                        <?php _e( 'N/A', 'easyjobs' ); ?>
                                                    <?php else: ?>
                                                        <?php if(!empty($job->job_address->city->name)): ?>
							                                <?php echo esc_html( $job->job_address->city->name ); ?>,
                                                        <?php endif;?>
														<?php if(!empty($job->job_address->country->name)): ?>
															<?php echo esc_html( $job->job_address->country->name ); ?>
														<?php endif;?>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ej-job-list-item-col ej-job-time-col">
									<?php if ( ! $job->is_expired ) : ?>
                                        <p class="ej-deadline ej-el-deadline">
											<?php echo esc_html( $job->expire_at ); ?>
                                        </p>
                                        <p class="ej-list-sub">
                                            No of vacancies: <?php echo esc_html( $job->vacancies ); ?>
                                        </p>
									<?php else : ?>
                                        <p class="ej-list-title ej-expired">
											<?php esc_html_e( 'Expired', 'easyjobs' ); ?>
                                        </p>
									<?php endif; ?>
                                </div>
                                <div class="ej-job-list-item-col ej-job-apply-btn">
                                    <a href="<?php echo esc_url( $job->apply_url ); ?>"
                                       class="ej-apply-btn ej-btn ej-info-btn-light"
                                       target="_blank"><?php echo esc_html( $settings['easyjobs_joblist_apply_button_text'] ); ?></a>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
		<?php else : ?>
            <h3>
				<?php esc_html_e( 'No open jobs right now', 'easyjobs' ); ?>
            </h3>
		<?php endif; ?>
		<?php
	}
    
    public function job_list_shortcode_template( $company ) {

		$jobs_obj = new Easyjobs_Admin_Jobs();
		$settings = $this->get_settings();

		$ej_is_search				= false;
		$sanitized_get_data 		= $this->sanitize_get_data();
		$job_page 					= isset($sanitized_get_data['job_page']) ? $sanitized_get_data['job_page'] : 1;
		
		if ( isset( $sanitized_get_data ) && ( ! empty( $sanitized_get_data['job_title'] ) || ! empty( $sanitized_get_data['job_category'] ) || ! empty( $sanitized_get_data['job_location'] ) ) ) {
			$ej_is_search 			  = true;
		}
		$fetch_params 					= $this->build_fetch_params($settings, $sanitized_get_data);
		$ej_all_datas     				= $this->get_published_jobs($fetch_params);
		if(!empty($ej_all_datas)) {
			$jobs_data						= $ej_all_datas->jobs;
			$ej_categories					= (array) $ej_all_datas->categories;
			$ej_locations					= (array) $ej_all_datas->locations;
		}
		$paginate_data					= [];
		if (isset($jobs_data) && !empty($jobs_data)) {
			if (isset($jobs_data->last_page) && $jobs_data->last_page > 1) {
				$permalink 					= get_the_permalink();
				$prev_page_num 				= ( $jobs_data->current_page ) == 1 ? 1 : ( $job_page - 1 );
				$next_page_num 				= ( $jobs_data->current_page ) == $jobs_data->last_page ? $jobs_data->last_page : ( $job_page + 1 );
				$prev_page_url				= $permalink . "?" . Easyjobs_Helper::get_pagination_url($sanitized_get_data, $prev_page_num);
				$next_page_url				= $permalink . "?" . Easyjobs_Helper::get_pagination_url($sanitized_get_data, $next_page_num);
				$paginate_data = Easyjobs_Helper::paginate(["current" => $jobs_data->current_page, "max" => $jobs_data->last_page]);
			}

            $jobs 							= isset($jobs_data->last_page) ? $jobs_data->data : $ej_all_datas->jobs;
            $jobs							= (array) $jobs;
			$job_with_page_id     = Easyjobs_Helper::sync_job_pages( $jobs );
			usort($jobs, function ($a, $b) {
				return $b->is_pinned - $a->is_pinned;
			});
		}
		/*$new_job_with_page_id = Easyjobs_Helper::create_pages_if_required( $jobs, $job_with_page_id );
		$job_with_page_id    += $new_job_with_page_id;*/
		
        ob_start();
		include Easyjobs_Helper::get_path_by_template( $company->selected_template, 'list' );
		return ob_get_clean();
	}

	/**
	 * merge the settings and query params to fetch data
	 */
	private function build_fetch_params($settings, $search_params) {
		return array_merge(
			array(
				'rows'   => $settings['easyjobs_jobs_per_page']['size'],
				// 'orderby' => $settings['easyjobs_job_list_order_by'] . ':' . $settings['easyjobs_job_list_sort_by'],
				'orderby' => $settings['easyjobs_job_list_order_by'],
				'order' => $settings['easyjobs_job_list_sort_by'],
				'status' => !empty( $settings['easyjobs_show_open_job'] ) && 'yes' ===  $settings['easyjobs_show_open_job'] ? 'active' : '',
      		), 
			[
				'title' => isset($search_params['job_title']) ? $search_params['job_title'] : '',
				'category' => isset($search_params['job_category']) ? $search_params['job_category'] : '',
				'location' => isset($search_params['job_location']) ? $search_params['job_location'] : '', 
				'page' => isset($search_params['job_page']) ? $search_params['job_page'] : 1
			]
		);
	}

	/**
	 * Function to sanitize $_GET data
	 */
	private function sanitize_get_data() {

		$requred = [
			'job_page',
			'job_title',
			'job_category',
			'job_location'
		];
		$sanitized = [];

		foreach($requred as $field){
			if(isset($_GET[$field])) {
				if($field == 'job_page') {
					$sanitized[$field] = absint($_GET[$field]);
				}else {
					$sanitized[$field] = sanitize_text_field($_GET[$field]);
				}
			}
		}

		return $sanitized;
	}
}
