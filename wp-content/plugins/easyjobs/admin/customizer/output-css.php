<?php
/**
 * Easyjobs Theme Customizer outout for layout Settings
 *
 * @package Easyjobs
 */

/**
 * This function adds some styles to the WordPress Customizer
 */
function easyjobs_customizer_styles() { ?>
	<style type="text/css">
		.customize-control-easyjobs-title .easyjobs-select,
		.customize-control-easyjobs-title .easyjobs-dimension{
			display: flex;
		}
		.customize-control-easyjobs-range-value {
			display: flex;
		}
		.customize-control-easyjobs-range-value .customize-control-title,
		.customize-control-easyjobs-number .customize-control-title {
			float: left;
		}
		.easyjobs-customize-control-separator {
			display: block;
			margin: 0 -12px;
			border: 1px solid #ddd;
			border-left: 0;
			border-right: 0;
			padding: 15px;
			font-size: 11px;
			font-weight: 600;
			letter-spacing: 2px;
			line-height: 1;
			text-transform: uppercase;
			color: #555;
			background-color: #fff;
		}
		.customize-control.customize-control-easyjobs-dimension,
		.customize-control-easyjobs-select {
			width: 25%;
			float: left !important;
			clear: none !important;
			margin-top: 0;
			margin-bottom: 12px;
		}
		.customize-control.customize-control-easyjobs-dimension .customize-control-title,
		.customize-control-easyjobs-select .customize-control-title{
			font-size: 11px;
			font-weight: normal;
			color: #888b8c;
			margin-top: 0;
		}
		.easyjobs-customizer-reset {
			font-size: 22px;
    		line-height: 26px;
    		margin-left: 5px;
			transition: unset;
		}
		.easyjobs-customizer-reset svg {
			width: 16px;
			fill: #FE1F4A;
		}
		.customize-control-title .customize-control-title {
			margin-bottom: 0;
		}
	</style>
	<?php

}
add_action( 'customize_controls_print_styles', 'easyjobs_customizer_styles', 999 );

if(!function_exists('print_css_property')){

    function print_css_property($property, $name, $output, $suffix=''){
        if(!empty($output[$name])){
            if((strpos($property, 'color') !== -1) && trim($output[$name]) == 'rgba(0,0,0,0)'){
                return '';
            }
            return $property . ':' . $output[$name] . $suffix . ';';
        }
        return '';
    }
}

function easyjobs_customize_css() {
    // if(!is_page_template( 'easyjobs-template.php' )) {
	// 	return;
	// }
	$output = easyjobs_generate_output();
    ?>
	<style type="text/css">

        /*********** Easyjobs dynamic css started *************/
        .easyjobs-frontend-wrapper.easyjobs-landing-page{
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_page_bg_color', $output ); ?>
            width: <?php echo $output['easyjobs_landing_container_width'] ?>%;
            max-width: <?php echo $output['easyjobs_landing_custom_max_width'] ? $output['easyjobs_landing_container_max_width'] . 'px': '100%' ?>;
            padding-top: <?php echo $output['easyjobs_landing_container_padding_top'] ?>px;
            padding-right: <?php echo $output['easyjobs_landing_container_padding_right'] ?>px;
            padding-bottom: <?php echo $output['easyjobs_landing_container_padding_bottom'] ?>px;
            padding-left: <?php echo $output['easyjobs_landing_container_padding_left'] ?>px;
        }

        .easyjobs-frontend-wrapper.easyjobs-landing-page .ej-header, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company, .easyjobs-frontend-wrapper.easyjobs-landing-page .about__company{
            background-color: <?php echo $output['easyjobs_landing_company_overview_bg_color'] ?>;
            padding-top: <?php echo $output['easyjobs_landing_company_overview_padding_top'] ?>px;
            padding-right: <?php echo $output['easyjobs_landing_company_overview_padding_right'] ?>px;
            padding-bottom: <?php echo $output['easyjobs_landing_company_overview_padding_bottom'] ?>px;
            padding-left: <?php echo $output['easyjobs_landing_company_overview_padding_right'] ?>px;
        }

        .easyjobs-landing-page .ej-header .ej-company-info .info .name{
            font-size: <?php echo $output['easyjobs_landing_company_name_font_size']?>px;
        }
        .easyjobs-landing-page .ej-header .ej-company-info .info .location{
            font-size: <?php echo $output['easyjobs_landing_company_location_font_size']?>px;
        }

        .easyjobs-landing-page .ej-header .ej-header-tools .ej-btn, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn {
            <?php echo print_css_property('font-size','easyjobs_landing_company_website_btn_font_size', $output, 'px');?>
            <?php echo print_css_property('color','easyjobs_landing_company_website_btn_font_color', $output);?>
            <?php echo print_css_property('background-color','easyjobs_landing_company_website_btn_bg_color', $output);?>
        }
        .easyjobs-landing-page .ej-header .ej-header-tools .ej-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .carrier__company .button:hover, .easyjobs-shortcode-wrapper.ej-template-elegant .ej-company-info .ej-btn:hover{
            <?php echo print_css_property('color','easyjobs_landing_company_website_btn_hover_font_color', $output);?>
            <?php echo print_css_property('background-color','easyjobs_landing_company_website_btn_hover_bg_color', $output);?>
        }
        .easyjobs-landing-page .ej-company-description, .easyjobs-landing-page .ej-company-description p, .easyjobs-landing-page .ej-company-description p span, .easyjobs-landing-page .ej-company-description ul li, .easyjobs-landing-page .ej-company-description a{
            font-size: <?php echo $output['easyjobs_landing_company_description_font_size'];?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_company_description_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col{
            padding-top: <?php echo $output['easyjobs_landing_job_list_column_padding_top'] ?>px;
            padding-right: <?php echo $output['easyjobs_landing_job_list_column_padding_right'] ?>px;
            padding-bottom: <?php echo $output['easyjobs_landing_job_list_column_padding_bottom'] ?>px;
            padding-left: <?php echo $output['easyjobs_landing_job_list_column_padding_left'] ?>px;
            <?php echo print_css_property( 'border-color', 'easyjobs_landing_job_column_separator_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-title{
            font-size: <?php echo $output['easyjobs_landing_job_title_font_size']?>px;
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-title a{
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_title_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-title a:hover{
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_title_hover_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block{
            font-size: <?php echo $output['easyjobs_landing_job_meta_font_size']?>px;
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block a{
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_meta_company_link_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-job-list-info .ej-job-list-info-block span{
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_meta_location_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-deadline{
            font-size: <?php echo $output['easyjobs_landing_job_deadline_font_size']?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_deadline_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-list-sub{
            font-size: <?php echo $output['easyjobs_landing_job_vacancy_font_size']?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_job_vacancy_color', $output ); ?>
        }

        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-btn.ej-info-btn-light{
            font-size: <?php echo $output['easyjobs_landing_apply_btn_font_size']?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_apply_btn_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_apply_btn_bg_color', $output ); ?>
        }

        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner
        .ej-job-list-item-col .ej-btn.ej-info-btn-light:hover{
            <?php echo print_css_property( 'color', 'easyjobs_landing_apply_btn_hover_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_apply_btn_hover_bg_color', $output ); ?>
        }

        .easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-text{
            <?php echo print_css_property( 'color', 'easyjobs_landing_section_heading_color', $output ); ?>
            font-size: <?php echo $output['easyjobs_landing_section_heading_font_size']?>px;
        }
        .easyjobs-landing-page .ej-section .ej-section-title .ej-section-title-icon{
            <?php echo print_css_property( 'color', 'easyjobs_landing_section_heading_icon_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_section_heading_icon_bg_color', $output ); ?>
        }
        /* Details page */
        
        .easyjobs-frontend-wrapper.easyjobs-single-page{
            width: <?php echo $output['easyjobs_single_container_width'] ?>%;
            <?php echo print_css_property( 'background-color', 'easyjobs_single_page_bg_color', $output); ?>
            max-width: <?php echo $output['easyjobs_single_container_max_width'] ?>px;
            padding-top: <?php echo $output['easyjobs_single_container_padding_top'] ?>px;
            padding-right: <?php echo $output['easyjobs_single_container_padding_right'] ?>px;
            padding-bottom: <?php echo $output['easyjobs_single_container_padding_bottom'] ?>px;
            padding-left: <?php echo $output['easyjobs_single_container_padding_left'] ?>px;
        }
        
        .easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details {
            padding-top: <?php echo $output['easyjobs_single_job_overview_padding_top'] ?>px;
            padding-right: <?php echo $output['easyjobs_single_job_overview_padding_right'] ?>px;
            padding-bottom: <?php echo $output['easyjobs_single_job_overview_padding_bottom'] ?>px;
            padding-left: <?php echo $output['easyjobs_single_job_overview_padding_left'] ?>px;
			<?php if(!empty(get_theme_mod('easyjobs_single_job_overview_bg_color'))) { ?>
			background-color: <?php echo get_theme_mod('easyjobs_single_job_overview_bg_color') ?>;
			<?php } else { ?>
                <?php echo print_css_property( 'background-color', 'easyjobs_single_job_overview_bg_color', $output ); ?>
			<?php } ?>
		}
        .easyjobs-single-page .ej-company-info .info .name{
            font-size: <?php echo $output['easyjobs_single_company_name_font_size'];?>px;
        }

        .easyjobs-single-page.ej-company-info .info .location{
            font-size: <?php echo $output['easyjobs_single_company_location_font_size'];?>px;
        }

        .easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info span, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info p, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p{
            font-size: <?php echo $output['easyjobs_single_job_info_list_font_size'];?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item .ej-job-highlights-item-label, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info p, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p i, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p span{
            <?php echo print_css_property( 'color', 'easyjobs_single_job_info_list_label_color', $output); ?>
        }
        .easyjobs-single-page.easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview .ej-job-highlights .ej-job-highlights-item .ej-job-highlights-item-value, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details .infos .info span, .easyjobs-shortcode-wrapper.ej-template-classic .ej-container div.job__more__details > p{
            <?php echo print_css_property( 'color', 'easyjobs_single_job_info_list_value_color', $output); ?>
        }
        .easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button, .ej-template-elegant .ej-hero .job__infos__block .meta .button{
            font-size: <?php echo $output['easyjobs_single_apply_btn_font_size'];?>px;
            <?php echo print_css_property( 'background-color', 'easyjobs_single_apply_btn_bg_color', $output ); ?>
            <?php echo print_css_property( 'color', 'easyjobs_single_apply_btn_text_color', $output ); ?>
        }
        .easyjobs-single-page .ej-apply-link .ej-btn.ej-info-btn:hover, .easyjobs-shortcode-wrapper.ej-template-classic .job__more__details > a.button:hover, .ej-template-elegant .ej-hero .job__infos__block .meta .button:hover{
            <?php echo print_css_property( 'background-color', 'easyjobs_single_apply_btn_hover_bg_color', $output ); ?>
            <?php echo print_css_property( 'color', 'easyjobs_single_apply_btn_hover_text_color', $output ); ?>
        }
        .easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview-footer .ej-social-share ul li a, .easyjobs-frontend-wrapper .easyjobs-shortcode-wrapper .job__more__details .share__options ul li a{
            width: <?php echo $output['easyjobs_single_social_sharing_icon_bg_size'];?>px;
            height: <?php echo $output['easyjobs_single_social_sharing_icon_bg_size'];?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-job-header .ej-job-header-left .ej-job-overview-footer .ej-social-share ul li a svg{
            width: <?php echo $output['easyjobs_single_social_sharing_icon_size'];?>px;
            height: <?php echo $output['easyjobs_single_social_sharing_icon_size'];?>px;
        }
        .easyjobs-frontend-wrapper .easyjobs-shortcode-wrapper .job__more__details .share__options ul li a i{
            font-size: <?php echo $output['easyjobs_single_social_sharing_icon_size'];?>px;
            line-height: <?php echo $output['easyjobs_single_social_sharing_icon_bg_size'];?>px;
        }

        .easyjobs-single-page .easyjobs-details .ej-content-block h1{
            font-size: <?php echo $output['easyjobs_single_h1_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block h2{
            font-size: <?php echo $output['easyjobs_single_h2_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block h3{
            font-size: <?php echo $output['easyjobs_single_h3_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block h4{
            font-size: <?php echo $output['easyjobs_single_h4_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block h5{
            font-size: <?php echo $output['easyjobs_single_h5_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block h6{
            font-size: <?php echo $output['easyjobs_single_h6_font_size']?>px;
        }
        .easyjobs-single-page .easyjobs-details .ej-content-block p,
        .easyjobs-single-page .easyjobs-details .ej-content-block ul li,
        .easyjobs-single-page .easyjobs-details .ej-content-block ol li,
        .easyjobs-single-page .easyjobs-details .ej-label{
            font-size: <?php echo $output['easyjobs_single_text_font_size']?>px;
        }
        .easyjobs-single-page .ej-section .ej-section-title .ej-section-title-text{
            font-size: <?php echo $output['easyjobs_single_section_heading_font_size']?>px;
        }
        <?php if($output['easyjobs_landing_custom_content_max_width']): ?>
            .easyjobs-frontend-wrapper.easyjobs-landing-page .easyjobs-content-wrapper .easyjobs-shortcode-wrapper,
            .easyjobs-frontend-wrapper.easyjobs-landing-page .easyjobs-content-wrapper .easyjobs-shortcode-wrapper .ej-container{
            max-width: <?php echo $output['easyjobs_landing_content_max_width']?>px;
            margin: 0 auto;
        }
        <?php endif;?>
        /*Job filter css*/
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light {
            font-size: <?php echo $output['easyjobs_landing_submit_btn_font_size']?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_submit_btn_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_submit_btn_bg_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover {
            <?php echo print_css_property( 'color', 'easyjobs_landing_submit_btn_hover_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_submit_btn_hover_bg_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn {
            font-size: <?php echo $output['easyjobs_landing_reset_btn_font_size']?>px;
            <?php echo print_css_property( 'color', 'easyjobs_landing_reset_btn_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_reset_btn_bg_color', $output ); ?>
        }
        .easyjobs-landing-page .easyjobs-shortcode-wrapper .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover {
            <?php echo print_css_property( 'color', 'easyjobs_landing_reset_btn_hover_color', $output ); ?>
            <?php echo print_css_property( 'background-color', 'easyjobs_landing_reset_btn_hover_bg_color', $output ); ?>
        }
        /****** end easy jobs dynamic css *******/
	</style>
    <?php
}
add_action( 'wp_head', 'easyjobs_customize_css');