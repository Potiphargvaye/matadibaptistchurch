/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

    /********** Easyjobs started *************/

    /**
     * Landing page
     */

    wp.customize( 'easyjobs_landing_container_width', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'width', to + '%' );
        } );
    });
    wp.customize( 'easyjobs_landing_container_max_width', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'maxWidth', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_container_padding_top', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'paddingTop', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_container_padding_right', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'paddingRight', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_container_padding_bottom', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'paddingBottom', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_container_padding_left', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'paddingLeft', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_page_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_section_heading_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-text' ).css( 'color', to );
        } );
    });
    wp.customize( 'easyjobs_landing_section_heading_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-text' ).css( 'fontSize', to + 'px');
        } );
    });

    wp.customize( 'easyjobs_landing_section_heading_icon_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-icon' ).css( 'color', to );
        } );
    });
    wp.customize( 'easyjobs_landing_section_heading_icon_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-icon' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_overview_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_overview_padding_top', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header' ).css( 'paddingTop', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_company_overview_padding_right', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header' ).css( 'paddingRight', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_company_overview_padding_bottom', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header' ).css( 'paddingBottom', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_company_overview_padding_left', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header' ).css( 'paddingLeft', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_company_name_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-company-info .info .name' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_company_location_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-company-info .info .location' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_company_website_btn_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-header .ej-header-tools .ej-btn, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_company_website_btn_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-header .ej-header-tools .ej-btn, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_website_btn_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-header .ej-header-tools .ej-btn, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_website_btn_hover_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-header .ej-header-tools .ej-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn:hover' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_website_btn_hover_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-header .ej-header-tools .ej-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn:hover' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_company_description_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-company-description, .easyjobs-landing-page .ej-company-description p, .easyjobs-landing-page .ej-company-description p span, .easyjobs-landing-page .ej-company-description ul li, .easyjobs-landing-page .ej-company-description a, .easyjobs-landing-page .ej-company-description p strong' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_company_description_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .ej-company-description, .easyjobs-landing-page .ej-company-description p, .easyjobs-landing-page .ej-company-description p span, .easyjobs-landing-page .ej-company-description ul li, .easyjobs-landing-page .ej-company-description a, .easyjobs-landing-page .ej-company-description p strong' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_job_list_column_padding_top', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col' ).css( 'paddingTop', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_job_list_column_padding_right', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col' ).css( 'paddingRight', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_job_list_column_padding_bottom', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col' ).css( 'paddingBottom', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_job_list_column_padding_left', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col').css( 'paddingLeft', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_job_column_separator_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col').css( 'borderColor', to );
        } );
    });

    wp.customize( 'easyjobs_landing_job_title_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-title' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_job_title_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-title a' ).css( 'color', to );
        } );
    });
    wp.customize( 'easyjobs_landing_job_title_hover_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-title a:hover' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_job_meta_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block' ).css( 'fontSize', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_landing_job_meta_company_link_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block a' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_job_meta_location_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_landing_job_deadline_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-deadline' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_job_deadline_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-deadline' ).css( 'color', to);
        } );
    });
    wp.customize( 'easyjobs_landing_job_vacancy_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_job_vacancy_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub' ).css( 'color', to);
        } );
    });

    wp.customize( 'easyjobs_landing_apply_btn_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-btn.ej-info-btn-light' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_landing_apply_btn_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-btn.ej-info-btn-light' ).css( 'color', to);
        } );
    });

    wp.customize( 'easyjobs_landing_apply_btn_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-btn.ej-info-btn-light' ).css( 'backgroundColor', to);
        } );
    });

    wp.customize( 'easyjobs_landing_apply_btn_hover_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-btn.ej-info-btn-light:hover' ).css( 'color', to);
        });
    });

    wp.customize( 'easyjobs_landing_apply_btn_hover_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-btn.ej-info-btn-light:hover' ).css( 'backgroundColor', to);
        } );
    });

    // Job filter (Submit)
    wp.customize( 'easyjobs_landing_submit_btn_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light' ).css( 'fontSize', to + 'px');
        } );
    });
    wp.customize( 'easyjobs_landing_submit_btn_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light' ).css( 'color', to);
        } );
    });
    wp.customize( 'easyjobs_landing_submit_btn_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light' ).css( 'backgroundColor', to);
        } );
    });
    wp.customize( 'easyjobs_landing_submit_btn_hover_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover' ).css( 'color', to);
        } );
    });
    wp.customize( 'easyjobs_landing_submit_btn_hover_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover' ).css( 'backgroundColor', to);
        } );
    });

    // Job filter (Reset)
    wp.customize( 'easyjobs_landing_reset_btn_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn' ).css( 'fontSize', to + 'px');
        } );
    });
    wp.customize( 'easyjobs_landing_reset_btn_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn' ).css( 'color', to);
        } );
    });
    wp.customize( 'easyjobs_landing_reset_btn_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn' ).css( 'backgroundColor', to);
        } );
    });
    wp.customize( 'easyjobs_landing_reset_btn_hover_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover' ).css( 'color', to);
        } );
    });
    wp.customize( 'easyjobs_landing_reset_btn_hover_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover' ).css( 'backgroundColor', to);
        } );
    });

    /**
     * Details page
     */

    wp.customize( 'easyjobs_single_container_width', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'width', to + '%' );
        } );
    });
    wp.customize( 'easyjobs_single_container_max_width', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'maxWidth', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_container_padding_top', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'paddingTop', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_container_padding_right', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'paddingRight', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_container_padding_bottom', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'paddingBottom', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_container_padding_left', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'paddingLeft', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_page_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-frontend-wrapper.easyjobs-single-page' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_single_job_overview_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_single_job_overview_padding_top', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details' ).css( 'paddingTop', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_job_overview_padding_right', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details' ).css( 'paddingRight', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_job_overview_padding_bottom', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details' ).css( 'paddingBottom', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_job_overview_padding_left', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details' ).css( 'paddingLeft', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_company_name_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .ej-company-info .info .name' ).css( 'fontSize', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_company_location_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .ej-company-info .info .location' ).css( 'fontSize', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_job_info_list_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info span, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details p' ).css( 'fontSize', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_job_info_list_label_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item .ej-job-highlights-item-label, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info p, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p i, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p span' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_single_job_info_list_value_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item .ej-job-highlights-item-value, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info span, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_single_apply_btn_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button, .ej-template-elegant .ej-hero .job__infos__block .meta .button' ).css( 'fontSize', to + 'px' );
        } );
    });

    wp.customize( 'easyjobs_single_apply_btn_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button, .ej-template-elegant .ej-hero .job__infos__block .meta .button' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_single_apply_btn_text_color', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button, .ej-template-elegant .ej-hero .job__infos__block .meta .button' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_single_apply_btn_hover_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button:hover, .ej-template-elegant .ej-hero .job__infos__block .meta .button:hover' ).css( 'backgroundColor', to );
        } );
    });

    wp.customize( 'easyjobs_single_apply_btn_hover_text_color', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button:hover, .ej-template-elegant .ej-hero .job__infos__block .meta .button:hover' ).css( 'color', to );
        } );
    });

    wp.customize( 'easyjobs_single_social_sharing_icon_bg_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview-footer .ej-social-share ul li a, .easyjobs-frontend-wrapper .easyjobs-shortcode-wrapper .job__more__details .share__options ul li a' ).css({
                height: to +'px',
                width: to + 'px'
            });
            $('.easyjobs-frontend-wrapper .easyjobs-shortcode-wrapper .job__more__details .share__options ul li a i' ).css({
                lineHeight: to +'px'
            });
        } );
    });
    wp.customize( 'easyjobs_single_social_sharing_icon_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview-footer .ej-social-share ul li a svg' ).css({
                height: to +'px',
                width: to + 'px'
            });
            $('.easyjobs-frontend-wrapper .easyjobs-shortcode-wrapper .job__more__details .share__options ul li a i' ).css({
                fontSize: to +'px'
            });
        } );
    });
    wp.customize( 'easyjobs_single_h1_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h1' ).css( 'fontSize', to + 'px' );
            $('.easyjobs-single-page .ej-section .ej-section-title .ej-section-title-text' ).css( 'fontSize', to + 'px' );
        } );
    });
    wp.customize( 'easyjobs_single_h2_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h2' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_h3_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h3' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_h4_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h4' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_h5_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h5' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_h6_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block h6' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_text_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .easyjobs-details .ej-content-block p' ).css( 'fontSize', to + 'px' );
            $('.easyjobs-single-page .easyjobs-details .ej-content-block ul li' ).css( 'fontSize', to + 'px' );
            $('.easyjobs-single-page .easyjobs-details .ej-content-block ol li' ).css( 'fontSize', to + 'px' );
            $('.easyjobs-single-page .easyjobs-details .ej-label' ).css( 'fontSize', to + 'px' );

        } );
    });
    wp.customize( 'easyjobs_single_section_heading_font_size', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-single-page .ej-section .ej-section-title .ej-section-title-text' ).css( 'fontSize', to + 'px' );
        });
    });
    wp.customize( 'easyjobs_landing_content_max_width', function( value ) {
        value.bind( function( to ) {
            $('.easyjobs-frontend-wrapper.easyjobs-landing-page .easyjobs-content-wrapper .easyjobs-shortcode-wrapper' ).css( 'max-width', to + 'px' );
            $('.easyjobs-frontend-wrapper.easyjobs-landing-page .easyjobs-content-wrapper .easyjobs-shortcode-wrapper .ej-container' ).css( 'max-width', to + 'px' );
        });
    });

    /*********** end easyjobs ********/


} )( jQuery );
