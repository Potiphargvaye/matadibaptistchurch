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
            <div class="easyjobs-shortcode-wrapper ej-template-elegant" id="easyjobs-list">
                <div class="ej-section">
                    <?php if ( ! empty( $company->ejel_enabled ) && ! empty( $company->ejel_job_list_enabled ) ) : ?>
                        <div class="section__header section__header--flex ej-job-filter-wrap">
                            <div class="ej-section-title">
                                <?php if ( empty( $company->ejel_hide_job_list_heading_text ) ) : ?>
                                    <span class="ej-section-title-text">
                                    <?php
                                    if ( empty ( $company->ejel_joblist_heading ) ) {
                                        echo esc_html(
                                            get_theme_mod(
                                                'easyjobs_landing_job_list_heading',
                                                esc_html__( 'Open Job Positions', 'easyjobs' )
                                            )
                                        );
                                    } else {
                                        esc_html_e( $company->ejel_joblist_heading, 'easyjobs' );
                                    }
                                    ?>
                                    </span>
                                <?php endif; ?>

                            </div>
                            <?php
                                if (isset($company->show_job_filter) && $company->show_job_filter) {
                                    Easyjobs_Helper::job_filter( $jobs, $company, $ej_categories, $ej_locations, $settings );
                                }
                            ?>
                        </div>
                    <?php else: ?>
                    <div class="section__header section__header--flex ej-job-filter-wrap" id="open_job_position">
                        <div class="ej-section-title">
                            <span class="ej-section-title-text">
                                        <?php
                                        if ( empty ( $company->ejel_joblist_heading ) ) {
                                            echo esc_html(
                                                get_theme_mod(
                                                    'easyjobs_landing_job_list_heading',
                                                    esc_html__( 'Open Job Positions', 'easyjobs' )
                                                )
                                            );
                                        } else {
                                            esc_html_e( $company->ejel_joblist_heading, 'easyjobs' );
                                        }
                                        ?>
                                    </span>
                        </div>
                        <?php
                            if (isset($company->show_job_filter) && $company->show_job_filter) {
                                Easyjobs_Helper::job_filter_for_blocks( $ej_categories, $ej_locations, $atts );
                            }
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ( ( ! empty( $jobs ) && ! empty( $job_with_page_id )) ) : ?>
                    <div class="ej-job-list ej-job-list-elegant">
                        <div class="ej-row">
                            <?php foreach ( $jobs as $job ) : ?>
                            <?php
                                // if ( empty( $company->ejel_enabled ) && $job->is_expired ) {
                                //     continue;
                                // } else {
                                //     // Elementor Integration
                                //     if( is_object( $job ) && $job->is_expired && 'yes' === $settings['easyjobs_show_open_job'] ) {
                                //         continue;
                                //     }
                                // }
                            ?>
                            <div class="ej-col-lg-6 ej-job-list-item-cat" data-category="<?php echo esc_html($job->category->id)?>" data-location="<?php echo esc_html($job->job_address_id)?>">
                                <div class="job__card <?php if(isset($job->is_pinned) && $job->is_pinned) echo 'ej-has-badge'?>">
                                    <h3 class="ej-job-title">
                                        <a href="<?php echo esc_url( get_the_permalink( $job_with_page_id[ $job->id ] ) ); ?>"
                                        >
                                            <?php echo esc_html( $job->title ); ?>
                                        </a>
                                    </h3>
                                    <p class="meta">
                                        <i class="easyjobs-icon easyjobs-briefcase"> </i>
                                        <a href="<?php echo esc_url( $job->company_easyjob_url ); ?>" class="office__name">
                                            <?php echo is_object( $job ) ? esc_html( $job->company_name ) : ''; ?>
                                        </a>

                                        <?php if ( $atts['showLocation'] && ($job->is_remote || !empty($job->job_address->city) || !empty($job->job_address->country) )) { ?>
                                            <span class="office__location">
                                                <i class="easyjobs-icon easyjobs-map-maker"></i>
                                                <?php if ( $job->is_remote ) : ?>
                                                    <span><?php esc_html_e( 'Anywhere', 'easyjobs' ); ?></span>
                                                <?php else : ?>
                                                    <span>
                                                        <?php if(!empty($job->job_address->city) || !empty($job->job_address->country)): ?>
                                                        <?php if ( ! empty( $job->job_address->city ) ) : ?>
                                                            <?php echo esc_html( $job->job_address->city->name ); ?>
                                                        <?php endif; ?>
                                                        <?php if ( ! empty( $job->job_address->country ) ) : ?>
                                                            <?php if ( ! empty( $job->job_address->city ) ) :
                                                                echo ", ";
                                                            endif; ?>
                                                            <?php echo esc_html( $job->job_address->country->name ); ?>
                                                        <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php echo esc_attr('N/A'); ?>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif ?>
                                            </span>
                                        <?php } ?>
                                    </p>
                                    <div class="job__bottom">
                                        <div class="job__apply">
                                            <a href="<?php echo ! empty( $job->apply_url ) ? esc_url( $job->apply_url ) : '#'; ?>" class="button button__primary radius-15" target="_blank">
                                                <?php esc_html_e( $company->apply_button_text ); ?>
                                            </a>
                                        </div>
                                        <?php if ( $atts['showNoOfJob'] && ! empty( $job->vacancies ) ) { ?>
                                        <div class="job__vacancy">
                                            <h4><?php echo is_object( $job ) && $job->vacancies ? esc_html( $job->vacancies ) : 'N/A'; ?></h4>
                                            <p><?php esc_html_e( 'No of vacancies', 'easyjobs' ); ?></p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php if( $atts['showDateLine'] ): ?>
                                        <span class="deadline"> <i class="ej-icon ej-calender"></i> <?php echo is_object( $job ) ? esc_html( $job->expire_at ) : ''; ?></span>
                                    <?php endif; ?>
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
                        <?php esc_html_e( 'No jobs found', 'easyjobs' ); ?>
                    </h3>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <h3 class="empty-message">
                <?php esc_html_e( 'No jobs found.', 'easyjobs' ); ?>
            </h3>
        <?php endif; ?>
    </div>
</div>
