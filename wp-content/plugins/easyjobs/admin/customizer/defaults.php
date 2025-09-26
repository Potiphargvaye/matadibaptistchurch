<?php
/**
 *
 * @package Easyjobs
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'easyjobs_get_option_defaults' ) ) :
/**
 * Set default options
 */
function easyjobs_get_option_defaults() {
    // if(!is_page_template( 'easyjobs-template.php' )) {
	// 	return;
	// }
    $company_info = Easyjobs_Helper::get_company_info(true);
    $company_name = !empty($company_info->name) ? $company_info->name : '';

	$easyjobs_defaults = array(
	    /**
         * Landing page
         */
        'easyjobs_landing_container_width' => '100',
		'easyjobs_landing_custom_max_width' => false,
        'easyjobs_landing_container_max_width' => '1400',
        'easyjobs_landing_custom_content_max_width' => false,
        'easyjobs_landing_content_max_width' => '1140',
        'easyjobs_landing_container_padding' => '50',
        'easyjobs_landing_container_padding_top' => '50',
        'easyjobs_landing_container_padding_right' => '50',
        'easyjobs_landing_container_padding_bottom' => '50',
        'easyjobs_landing_container_padding_left' => '50',
        'easyjobs_landing_page_bg_color' => '',
        'easyjobs_landing_section_heading_color' => '',
        'easyjobs_landing_section_heading_font_size' => '32',
        'easyjobs_landing_section_heading_icon_color' => '',
        'easyjobs_landing_section_heading_icon_bg_color' => '',
	    'easyjobs_landing_company_overview_bg_color' => 'fff',
	    'easyjobs_landing_company_overview_padding' => '50',
	    'easyjobs_landing_company_overview_padding_top' => '50',
	    'easyjobs_landing_company_overview_padding_right' => '50',
	    'easyjobs_landing_company_overview_padding_bottom' => '50',
	    'easyjobs_landing_company_overview_padding_left' => '50',
	    'easyjobs_landing_hide_company_info' => false,
	    'easyjobs_landing_hide_company_logo' => false,
	    'easyjobs_landing_company_name_font_size' => '24',
	    'easyjobs_landing_company_location_font_size' => '14',
	    'easyjobs_landing_hide_company_website_button' => false,
	    'easyjobs_landing_company_website_btn_font_size' => '14',
	    'easyjobs_landing_company_website_btn_font_color' => '',
	    'easyjobs_landing_company_website_btn_bg_color' => '',
	    'easyjobs_landing_company_website_btn_hover_font_color' => '',
	    'easyjobs_landing_company_website_btn_hover_bg_color' => '',
	    'easyjobs_landing_hide_company_description' => false,
	    'easyjobs_landing_company_description_font_size' => '14',
	    'easyjobs_landing_company_description_color' => '',
	    'easyjobs_landing_job_list_heading' => __('Open Job Positions', 'easyjobs'),
	    'easyjobs_landing_job_list_column_padding' => '30',
	    'easyjobs_landing_job_list_column_padding_top' => '25',
	    'easyjobs_landing_job_list_column_padding_right' => '30',
	    'easyjobs_landing_job_list_column_padding_bottom' => '25',
	    'easyjobs_landing_job_list_column_padding_left' => '25',
	    'easyjobs_landing_job_column_separator_color' => '',
	    'easyjobs_landing_job_title_font_size' => '24',
	    'easyjobs_landing_job_title_color' => '',
	    'easyjobs_landing_job_title_hover_color' => '',
	    'easyjobs_landing_hide_job_metas' => false,
	    'easyjobs_landing_job_meta_font_size' => '14',
	    'easyjobs_landing_job_meta_company_link_color' => '',
	    'easyjobs_landing_job_meta_location_color' => '',
	    'easyjobs_landing_job_deadline_font_size' => '18',
	    'easyjobs_landing_job_deadline_color' => '',
	    'easyjobs_landing_job_vacancy_font_size' => '14',
	    'easyjobs_landing_job_vacancy_color' => '',
	    'easyjobs_landing_apply_btn_font_size' => '14',
	    'easyjobs_landing_apply_btn_color' => '',
	    'easyjobs_landing_apply_btn_bg_color' => '',
	    'easyjobs_landing_apply_btn_hover_color' => '',
	    'easyjobs_landing_apply_btn_hover_bg_color' => '',
	    'easyjobs_landing_showcase_heading' => __('Life at ', 'easyjobs') . $company_name,
        'easyjobs_landing_hide_job_search_by_title' => true,
        'easyjobs_landing_hide_job_search_by_category' => true,
        'easyjobs_landing_hide_job_search_by_location' => true,
		'easyjobs_landing_submit_btn_font_size' => '14',
        'easyjobs_landing_submit_btn_color' => '',
        'easyjobs_landing_submit_btn_bg_color' => '',
        'easyjobs_landing_submit_btn_hover_color' => '',
        'easyjobs_landing_submit_btn_hover_bg_color' => '',
        'easyjobs_landing_reset_btn_font_size' => '14',
        'easyjobs_landing_reset_btn_color' => '',
        'easyjobs_landing_reset_btn_bg_color' => '',
        'easyjobs_landing_reset_btn_hover_color' => '',
        'easyjobs_landing_reset_btn_hover_bg_color' => '',
	    /**
         * Job details page
         */
	    'easyjobs_single_container_width' => '100',
	    'easyjobs_single_container_max_width' => '1400',
	    'easyjobs_single_container_padding' => '50',
	    'easyjobs_single_container_padding_top' => '50',
	    'easyjobs_single_container_padding_right' => '50',
	    'easyjobs_single_container_padding_bottom' => '50',
	    'easyjobs_single_container_padding_left' => '50',
	    'easyjobs_single_page_bg_color' => '',
        'easyjobs_single_display_job_banner' => true,
	    'easyjobs_single_job_overview_bg_color' => '',
	    'easyjobs_single_job_overview_padding' => '50',
	    'easyjobs_single_job_overview_padding_top' => '50',
	    'easyjobs_single_job_overview_padding_right' => '50',
	    'easyjobs_single_job_overview_padding_bottom' => '50',
	    'easyjobs_single_job_overview_padding_left' => '50',
        'easyjobs_single_hide_company_info' => false,
	    'easyjobs_single_hide_company_logo' => false,
	    'easyjobs_single_company_name_font_size' => '24',
	    'easyjobs_single_company_location_font_size' => '14',
	    'easyjobs_single_job_info_list_font_size' => '16',
	    'easyjobs_single_job_info_list_label_color' => '',
	    'easyjobs_single_job_info_list_value_color' => '',
	    'easyjobs_single_apply_btn_font_size' => '14',
	    'easyjobs_single_apply_btn_bg_color' => '',
	    'easyjobs_single_apply_btn_text_color' => '',
        'easyjobs_single_apply_btn_hover_bg_color' => '',
	    'easyjobs_single_apply_btn_hover_text_color' => '',
	    'easyjobs_single_disable_social_sharing' => false,
	    'easyjobs_single_disable_social_sharing_fb' => false,
	    'easyjobs_single_disable_social_sharing_twitter' => false,
	    'easyjobs_single_disable_social_sharing_linkedin' => false,
	    'easyjobs_single_social_sharing_icon_bg_size' => '40',
	    'easyjobs_single_social_sharing_icon_size' => '18',
	    'easyjobs_single_h1_font_size' => '32',
	    'easyjobs_single_h2_font_size' => '28',
	    'easyjobs_single_h3_font_size' => '24',
	    'easyjobs_single_h4_font_size' => '21',
	    'easyjobs_single_h5_font_size' => '18',
	    'easyjobs_single_h6_font_size' => '16',
	    'easyjobs_single_section_heading_font_size' => '32',
	    'easyjobs_single_text_font_size' => '14',
        'easyjobs_single_job_description_title' => __('So If You Are Someone Who Has', 'easyjobs'),
        'easyjobs_single_job_responsibility_title' => __('Job Responsibilities', 'easyjobs'),
        'easyjobs_single_job_benefits_title' => __('Benefits', 'easyjobs'),
        'easyjobs_single_showcase_title' => __('Life at ', 'easyjobs') . $company_name
	);
	
	return apply_filters( 'easyjobs_option_defaults', $easyjobs_defaults );
}
endif;


/**
*  Get default customizer option
*/
if ( ! function_exists( 'easyjobs_get_option' ) ) :

	/**
	 * Get default customizer option
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function easyjobs_get_option( $key ) {

		$default_options = easyjobs_get_option_defaults();

		if ( empty( $key ) ) {
			return;
		}

		$theme_options = (array)get_theme_mods( 'theme_options' );
		$theme_options = wp_parse_args( $theme_options, $default_options );

		$value = null;

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;
	}

endif;


if( ! function_exists( 'easyjobs_generate_defaults' ) ) :

	function easyjobs_generate_defaults(){

		$default_options = easyjobs_get_option_defaults();
		$saved_options = get_theme_mods();

		$returned = [];

		if( ! $saved_options ) {
			return;
		}

		foreach( $default_options as $key => $option ) {
			if( array_key_exists( $key, $saved_options ) ) {
				$returned[ $key ] = get_theme_mod( $key );				
			} else {
				switch ( $key ) {
					default:
						$returned[ $key ] = $default_options[ $key ];
						break;
				}
			}
		}

		return $returned;

	}

endif;

if( ! function_exists( 'easyjobs_generate_output' ) ) :

	function easyjobs_generate_output(){
		// if(!is_page_template( 'easyjobs-template.php' )) {
		// 	return;
		// }

		$default_options = easyjobs_get_option_defaults();

		$returned = [];
		
		foreach( $default_options as $key => $option ) {
			$returned[ $key ] = get_theme_mod( $key, $option );	
		}

		return $returned;

	}

endif;