<?php
/**
 * Render job list for shortcode
 *
 * @since 1.0.0
 */
echo Easyjobs_Helper::generate_block_style($atts);
if ( ! isset( $jobs ) && empty( $jobs ) ) {
    $jobs = [];
}
?>
<div <?php echo wp_kses_data( $wrapper_attributes); ?>>
    <div class="ej-job-body easyjobs-blocks easyjobs-blocks-job-list">
        <?php if ( $ej_is_search || ( ! empty( $jobs ) && ! empty( $job_with_page_id ) ) ) : ?>
            <div class="easyjobs-shortcode-wrapper ej-template-default" id="easyjobs-list">
                <div class="ej-section">
                    <div class="d-flex ej-job-filter-wrap">
                        <?php if( ! empty( $company->joblist_heading_icon ) || ! empty( $company->joblist_heading ) ): ?>
                            <div class="ej-section-title">
                                <?php if( ! empty( $company->joblist_heading_icon ) ): ?>
                                    <?php printf( '<i class="dashicon dashicons %s ej-section-title-icon"></i>', $company->joblist_heading_icon ); ?>
                                <?php endif; ?>
                                <?php if( ! empty( $company->joblist_heading ) ): ?>
                                    <span class="ej-section-title-text">
                                        <?php esc_html_e( $company->joblist_heading, 'easyjobs' ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php
                            if (isset($company->show_job_filter) && $company->show_job_filter) {
                                Easyjobs_Helper::job_filter_for_blocks( $ej_categories, $ej_locations, $atts );
                            }
                        ?>
                    </div>
                    <?php if ( ( ! empty( $jobs ) && ! empty( $job_with_page_id )) ) : ?>
                        <div class="ej-section-content">
                            <div class="ej-job-list">
                                <?php foreach ( $jobs as $job ) : ?>
                                    <?php
                                        if( $job->is_expired && $atts['activeOnly'] ) {
                                            continue;
                                        }
                                    ?>
                                    <div class="ej-job-list-item ej-job-list-item-cat" data-category="<?php echo esc_html($job->category->id); ?>" data-location="<?php echo esc_html($job->job_address_id); ?>">
                                        <div class="ej-job-list-item-inner">
                                            <div class="ej-job-list-item-col <?php if(isset($job->is_pinned) && $job->is_pinned) echo 'ej-has-badge'; ?>">
                                                <h2 class="ej-job-title">
                                                    <a href="<?php echo esc_url( get_the_permalink( $job_with_page_id[ $job->id ] ) ); ?>"><?php echo esc_html( $job->title ); ?></a>
                                                </h2>
                                                <?php if ( $atts['showCompanyName'] || $atts['showLocation'] ) : ?>
                                                    <div class="ej-job-list-info">
                                                        <?php if( $atts['showCompanyName'] ): ?>
                                                            <div class="ej-job-list-info-block ej-job-list-company-name">
                                                                <i class="easyjobs-icon easyjobs-briefcase-2"></i>
                                                                <a href="<?php echo esc_url( $job->company_easyjob_url ); ?>" target="_blank">
                                                                    <?php echo esc_html( $job->company_name ); ?>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if( $atts['showLocation'] ): ?>
                                                            <div class="ej-job-list-info-block ej-job-list-location">
                                                                <?php if ($job->is_remote || !empty($job->job_address->city) || !empty($job->job_address->country)) { ?>
                                                                    <i class="easyjobs-icon easyjobs-map-maker"></i>
                                                                <?php } ?>
                                                                <?php if ( $job->is_remote ) : ?>
                                                                    <span>
                                                                        <?php esc_html_e( 'Anywhere', 'easyjobs' ); ?>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <span>
                                                                        <?php if ( ! empty( $job->job_address->city ) ) : ?>
                                                                            <?php echo esc_html( $job->job_address->city->name ); ?>
                                                                        <?php endif; ?>
                                                                        <?php if ( ! empty( $job->job_address->country ) ) : ?>
                                                                            <?php if ( ! empty( $job->job_address->city ) ) :
                                                                                echo ", ";
                                                                            endif; ?>
                                                                            <?php echo esc_html( $job->job_address->country->name ); ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                <?php endif ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( $atts['showDateLine'] || $atts['showNoOfJob'] ) : ?>
                                                <div class="ej-job-list-item-col ej-job-time-col">
                                                    <?php
                                                    if ( ! $job->is_expired ) :
                                                    ?>
                                                        <?php if( $atts['showDateLine'] ): ?>
                                                            <p class="ej-deadline">
                                                                <?php echo esc_html( $job->expire_at ); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if ( $atts['showNoOfJob'] && !empty( $job->vacancies ) ) { ?>
                                                            <p class="ej-list-sub">
                                                                <?php esc_html_e( 'No of vacancies: ', 'easyjobs' ); ?> <?php echo $job->vacancies ? esc_html( $job->vacancies ) : '1'; ?>
                                                            </p>
                                                        <?php } ?>
                                                    <?php else : ?>
                                                        <p class="ej-list-title ej-expired">
                                                            <?php esc_html_e( 'Expired', 'easyjobs' ); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="ej-job-list-item-col ej-job-apply-btn">
                                                <a href="<?php echo ! empty( $job->apply_url ) ? esc_url( $job->apply_url ) : '#'; ?>" class="ej-btn ej-info-btn-light" target="_blank">
                                                    <?php esc_html_e( $company->apply_button_text ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($jobs_data) && isset($jobs_data->last_page) && ($jobs_data->last_page > 1)) { 
                                Easyjobs_Helper::job_pagination($jobs_data, $sanitized_get_data, $permalink, $prev_page_url, $next_page_url, $paginate_data);
                            } ?>
                        </div>
                    <?php else : ?>
                        <h3 class="empty-message">
                            <?php esc_html_e( 'No jobs found.', 'easyjobs' ); ?>
                        </h3>
                    <?php endif; ?>
                </div>
            </div>

        <?php else : ?>
            <h3 class="empty-message mt-10">
                <?php esc_html_e( 'No jobs found.', 'easyjobs' ); ?>
            </h3>
        <?php endif; ?>
    </div>
</div>