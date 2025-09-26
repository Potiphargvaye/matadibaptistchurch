<?php
/**
 * Render job details for shortcode
 *
 * @since 1.0.0
 */
echo Easyjobs_Helper::generate_block_style($atts);
?>
<?php if( empty( $company->ejel_hide_company_gallery ) ) : ?>
    <?php if ( $company->show_life && ! empty( $company->showcase_photo ) ) : ?>
<div <?php echo wp_kses_data( $wrapper_attributes); ?>>
    <div class="easyjobs-shortcode-wrapper ej-template-classic">
        <?php if ( ! empty( $company )) : ?>
            <div class="easyjobs-details">
                
                    <div class="ej-section ej-company-showcase-classic">
                        <div class="section__header section__header--text-center" id="open_job_position">
                            <div class="ej-section-title">
                                <h2 class="ej-section-title-text">
                                    <?php
                                    if( empty( $company->ejel_galelry_section_title ) ) {
                                        echo esc_html(
                                            get_theme_mod(
                                                'easyjobs_landing_showcase_heading',
                                                esc_html__( 'Life at ', 'easyjobs' ) . esc_html( $company->name )
                                            )
                                        );
                                    } else {
                                        esc_html_e( $company->ejel_galelry_section_title, 'easyjobs' );
                                    }
                                    ?>
                                </h2>
                            </div>
                        </div>
                        <div class="ej-section-content">
                            <div class="office__gallery__slider ej-company-showcase owl-carousel">
                                <?php foreach ( $company->showcase_photo as $key => $photo ) : ?>
                                    <div class="item">
                                        <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $company->name ); ?>">
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    
                
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
                <?php endif; ?>
