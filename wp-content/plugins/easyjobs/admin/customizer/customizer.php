<?php
// No direct access, please
if (!defined('ABSPATH')) exit;
/**
 * Easyjobs Theme Customizer
 *
 * @package Easyjobs
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

/**
 * Check for WP_Customizer_Control existence before adding custom control because WP_Customize_Control
 * is loaded on customizer page only
 *
 * @see _wp_customize_include()
 */


function easyjobs_customize_register($wp_customize)
{
    
    // Get default customizer values
    $defaults = easyjobs_get_option_defaults();
    
    // Load custom controls
    require_once(EASYJOBS_ADMIN_DIR_PATH . 'customizer/controls.php');
    require_once(EASYJOBS_ADMIN_DIR_PATH . 'customizer/sanitize.php');
    
    // ******* Landing Page Settings Started ********** \\
    $wp_customize->add_section('easyjobs_landing_page_settings', array(
        'title' => __('Landing Page', 'easyjobs'),
        'priority' => 101
    ));
    
    // Container width
    $wp_customize->add_setting('easyjobs_landing_container_width', array(
        'default' => $defaults['easyjobs_landing_container_width'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_container_width', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_width',
        'label' => __('Container Width', 'easyjobs'),
        'priority' => 102,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => '%', //optional suffix
        ),
    )));
	// Custom container max width toggle

	$wp_customize->add_setting('easyjobs_landing_custom_max_width', array(
		'default' => $defaults['easyjobs_landing_custom_max_width'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_checkbox',
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_custom_max_width', array(
		'label' => esc_html__('Container custom max width', 'easyjobs'),
		'priority' => 102,
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_custom_max_width',
		'type' => 'light', // light, ios, flat
	)));
    // Container max width
    
    $wp_customize->add_setting('easyjobs_landing_container_max_width', array(
        'default' => $defaults['easyjobs_landing_container_max_width'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    
    $wp_customize->add_control(new Easyjobs_Number_Control(
        $wp_customize, 'easyjobs_landing_container_max_width', array(
        'type' => 'easyjobs-number',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_max_width',
        'label' => __('Container Max Width', 'easyjobs'),
        'priority' => 102,
        'input_attrs' => array(
            'min' => 0,
            'suffix' => 'px',
        ),
    
    )));

	// Custom content max width toggle

	$wp_customize->add_setting('easyjobs_landing_custom_content_max_width', array(
		'default' => $defaults['easyjobs_landing_custom_content_max_width'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_checkbox',
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_custom_content_max_width', array(
		'label' => esc_html__('Content custom max width', 'easyjobs'),
		'priority' => 103,
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_custom_content_max_width',
		'type' => 'light', // light, ios, flat
	)));

	// Content max width

	$wp_customize->add_setting('easyjobs_landing_content_max_width', array(
		'default' => $defaults['easyjobs_landing_content_max_width'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_integer'
	));

	$wp_customize->add_control(new Easyjobs_Number_Control(
		$wp_customize, 'easyjobs_landing_content_max_width', array(
		'type' => 'easyjobs-number',
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_content_max_width',
		'label' => __('Content Max Width', 'easyjobs'),
		'priority' => 103,
		'input_attrs' => array(
			'min' => 0,
			'suffix' => 'px',
		),

	)));
    
    // Container padding
    
    $wp_customize->add_setting('easyjobs_landing_container_padding', array(
        'default' => $defaults['easyjobs_landing_container_padding'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Title_Custom_Control(
        $wp_customize, 'easyjobs_landing_container_padding', array(
        'type' => 'easyjobs-title',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_padding',
        'label' => __('Container Padding', 'easyjobs'),
        'priority' => 104,
        'input_attrs' => array(
            'id' => 'easyjobs_landing_container_padding',
            'class' => 'easyjobs-dimension',
        ),
    )));
    //padding top
    $wp_customize->add_setting('easyjobs_landing_container_padding_top', array(
        'default' => $defaults['easyjobs_landing_container_padding_top'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_container_padding_top', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_padding_top',
        'label' => __('Top', 'easyjobs'),
        'priority' => 105,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_container_padding easyjobs-dimension',
        ),
    )));
    // padding right
    $wp_customize->add_setting('easyjobs_landing_container_padding_right', array(
        'default' => $defaults['easyjobs_landing_container_padding_right'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_container_padding_right', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_padding_right',
        'label' => __('Right', 'easyjobs'),
        'priority' => 106,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_container_padding easyjobs-dimension',
        ),
    )));
    
    //padding bottom
    $wp_customize->add_setting('easyjobs_landing_container_padding_bottom', array(
        'default' => $defaults['easyjobs_landing_container_padding_bottom'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_container_padding_bottom', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_padding_bottom',
        'label' => __('Bottom', 'easyjobs'),
        'priority' => 107,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_container_padding easyjobs-dimension',
        ),
    )));
    // padding left
    $wp_customize->add_setting('easyjobs_landing_container_padding_left', array(
        'default' => $defaults['easyjobs_landing_container_padding_left'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_container_padding_left', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_container_padding_left',
        'label' => __('Left', 'easyjobs'),
        'priority' => 108,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_container_padding easyjobs-dimension',
        ),
    )));
    
    // Page bg color
    
    $wp_customize->add_setting('easyjobs_landing_page_bg_color', array(
        'default' => $defaults['easyjobs_landing_page_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_page_bg_color',
            array(
                'label' => __('Page Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_page_bg_color',
                'priority' => 109
            ))
    );
    
    // Page section heading text color
    $wp_customize->add_setting('easyjobs_landing_section_heading_color', array(
        'default' => $defaults['easyjobs_landing_section_heading_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_section_heading_color',
            array(
                'label' => __('Page Section Heading Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_section_heading_color',
                'priority' => 110
            ))
    );
    
    // Page Section heading font size
    $wp_customize->add_setting('easyjobs_landing_section_heading_font_size', array(
        'default' => $defaults['easyjobs_landing_section_heading_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_section_heading_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_section_heading_font_size',
        'label' => __('Page Section Heading Font Size', 'easyjobs'),
        'priority' => 111,
        'input_attrs' => array(
            'min' => 0,
            'max' => 60,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Page section heading icon color
    $wp_customize->add_setting('easyjobs_landing_section_heading_icon_color', array(
        'default' => $defaults['easyjobs_landing_section_heading_icon_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_section_heading_icon_color',
            array(
                'label' => __('Page Section Heading Icon Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_section_heading_icon_color',
                'priority' => 112
            ))
    );
    
    // Page section heading icon background color
    $wp_customize->add_setting('easyjobs_landing_section_heading_icon_bg_color', array(
        'default' => $defaults['easyjobs_landing_section_heading_icon_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_section_heading_icon_bg_color',
            array(
                'label' => __('Page Section Heading Icon Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_section_heading_icon_bg_color',
                'priority' => 113
            ))
    );
    
    /**
     * Company overview
     */
    
    $wp_customize->add_setting('easyjobs_company_overeview_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize,
        'easyjobs_company_overeview_title',
        array(
        'label' => __('Company overview', 'easyjobs'),
        'priority' => 114,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_company_overeview_title',
    )));
    
    // background Color
    
    $wp_customize->add_setting('easyjobs_landing_company_overview_bg_color', array(
        'default' => $defaults['easyjobs_landing_company_overview_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_overview_bg_color',
            array(
                'label' => __('Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_overview_bg_color',
                'priority' => 115
            ))
    );
    
    // Box padding
    
    $wp_customize->add_setting('easyjobs_landing_company_overview_padding', array(
        'default' => $defaults['easyjobs_landing_company_overview_padding'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    
    $wp_customize->add_control(new Easyjobs_Title_Custom_Control(
        $wp_customize, 'easyjobs_landing_company_overview_padding', array(
        'type' => 'easyjobs-title',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_overview_padding',
        'label' => __('Content Padding', 'easyjobs'),
        'priority' => 116,
        'input_attrs' => array(
            'id' => 'easyjobs_landing_company_overview_padding',
            'class' => 'easyjobs-dimension',
        ),
    )));
    //padding top
    $wp_customize->add_setting('easyjobs_landing_company_overview_padding_top', array(
        'default' => $defaults['easyjobs_landing_company_overview_padding_top'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_company_overview_padding_top', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_overview_padding_top',
        'label' => __('Top', 'easyjobs'),
        'priority' => 117,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_company_overview_padding easyjobs-dimension',
        ),
    )));
    // padding right
    $wp_customize->add_setting('easyjobs_landing_company_overview_padding_right', array(
        'default' => $defaults['easyjobs_landing_company_overview_padding_right'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_company_overview_padding_right', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_overview_padding_right',
        'label' => __('Right', 'easyjobs'),
        'priority' => 118,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_company_overview_padding easyjobs-dimension',
        ),
    )));
    
    //padding bottom
    $wp_customize->add_setting('easyjobs_landing_company_overview_padding_bottom', array(
        'default' => $defaults['easyjobs_landing_company_overview_padding_bottom'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_company_overview_padding_bottom', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_overview_padding_bottom',
        'label' => __('Bottom', 'easyjobs'),
        'priority' => 119,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_company_overview_padding easyjobs-dimension',
        ),
    )));
    // padding left
    $wp_customize->add_setting('easyjobs_landing_company_overview_padding_left', array(
        'default' => $defaults['easyjobs_landing_company_overview_padding_left'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_company_overview_padding_left', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_overview_padding_left',
        'label' => __('Left', 'easyjobs'),
        'priority' => 120,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_company_overview_padding easyjobs-dimension',
        ),
    )));
    
    // Hide company info
    
    $wp_customize->add_setting('easyjobs_landing_hide_company_info', array(
        'default' => $defaults['easyjobs_landing_hide_company_info'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_company_info', array(
        'label' => esc_html__('Hide Company Info', 'easyjobs'),
        'priority' => 121,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_hide_company_info',
        'type' => 'light', // light, ios, flat
    )));
    
    // Hide company logo
    
    $wp_customize->add_setting('easyjobs_landing_hide_company_logo', array(
        'default' => $defaults['easyjobs_landing_hide_company_logo'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_company_logo', array(
        'label' => esc_html__('Hide Company Logo', 'easyjobs'),
        'priority' => 122,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_hide_company_logo',
        'type' => 'light', // light, ios, flat
    )));
    // Company name font size
    $wp_customize->add_setting('easyjobs_landing_company_name_font_size', array(
        'default' => $defaults['easyjobs_landing_company_name_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_company_name_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_name_font_size',
        'label' => __('Company Name Font Size', 'easyjobs'),
        'priority' => 123,
        'input_attrs' => array(
            'min' => 0,
            'max' => 60,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Company location font size
    $wp_customize->add_setting('easyjobs_landing_company_location_font_size', array(
        'default' => $defaults['easyjobs_landing_company_location_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_company_location_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_location_font_size',
        'label' => __('Company Location Font Size', 'easyjobs'),
        'priority' => 124,
        'input_attrs' => array(
            'min' => 0,
            'max' => 40,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    // Hide website button
    $wp_customize->add_setting('easyjobs_landing_hide_company_website_button', array(
        'default' => $defaults['easyjobs_landing_hide_company_website_button'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_company_website_button', array(
        'label' => esc_html__('Hide Company Website Button', 'easyjobs'),
        'priority' => 125,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_hide_company_website_button',
        'type' => 'light', // light, ios, flat
    )));
    
    // Website button font size
    $wp_customize->add_setting('easyjobs_landing_company_website_btn_font_size', array(
        'default' => $defaults['easyjobs_landing_company_website_btn_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_company_website_btn_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_website_btn_font_size',
        'label' => __('Company Website Button Font Size', 'easyjobs'),
        'priority' => 126,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    // Website button font color
    $wp_customize->add_setting('easyjobs_landing_company_website_btn_font_color', array(
        'default' => $defaults['easyjobs_landing_company_website_btn_font_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_website_btn_font_color',
            array(
                'label' => __('Website Button Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_website_btn_font_color',
                'priority' => 127
            ))
    );
    
    // Website button bg color
    $wp_customize->add_setting('easyjobs_landing_company_website_btn_bg_color', array(
        'default' => $defaults['easyjobs_landing_company_website_btn_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_website_btn_bg_color',
            array(
                'label' => __('Website Button Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_website_btn_bg_color',
                'priority' => 128
            ))
    );
    
    // Website button hover font color
    $wp_customize->add_setting('easyjobs_landing_company_website_btn_hover_font_color', array(
        'default' => $defaults['easyjobs_landing_company_website_btn_hover_font_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_website_btn_hover_font_color',
            array(
                'label' => __('Website Button Hover Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_website_btn_hover_font_color',
                'priority' => 129
            ))
    );
    
    // Website button hover bg color
    $wp_customize->add_setting('easyjobs_landing_company_website_btn_hover_bg_color', array(
        'default' => $defaults['easyjobs_landing_company_website_btn_hover_bg_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_website_btn_hover_bg_color',
            array(
                'label' => __('Website Button Hover Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_website_btn_hover_bg_color',
                'priority' => 130
            ))
    );
    // Hide Company description
    $wp_customize->add_setting('easyjobs_landing_hide_company_description', array(
        'default' => $defaults['easyjobs_landing_hide_company_description'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_company_description', array(
        'label' => esc_html__('Hide Company Description', 'easyjobs'),
        'priority' => 131,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_hide_company_description',
        'type' => 'light', // light, ios, flat
    )));
    
    // Company description font size
    $wp_customize->add_setting('easyjobs_landing_company_description_font_size', array(
        'default' => $defaults['easyjobs_landing_company_description_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_company_description_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_company_description_font_size',
        'label' => __('Company Description Font Size', 'easyjobs'),
        'priority' => 132,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Company desciption font color
    $wp_customize->add_setting('easyjobs_landing_company_description_color', array(
        'default' => $defaults['easyjobs_landing_company_description_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_company_description_color',
            array(
                'label' => __('Company Description Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_company_description_color',
                'priority' => 133
            ))
    );
    /**
     * Job list
     */
    
    $wp_customize->add_setting('easyjobs_landing_job_list_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize,
        'easyjobs_landing_job_list_title', array(
        'label' => __('Job List', 'easyjobs'),
        'priority' => 134,
        'settings' => 'easyjobs_landing_job_list_title',
        'section' => 'easyjobs_landing_page_settings',
    )));
    // Job list section title
    $wp_customize->add_setting('easyjobs_landing_job_list_heading', array(
        'default' => $defaults['easyjobs_landing_job_list_heading'],
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'easyjobs_landing_job_list_heading',
            array(
                'label' => __('Heading', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_list_heading',
                'type' => 'text',
                'priority'=> 135
            )
        )
    );
    
    // Job list column padding
    
    $wp_customize->add_setting('easyjobs_landing_job_list_column_padding', array(
        'default' => $defaults['easyjobs_landing_job_list_column_padding'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    $wp_customize->add_control(new Easyjobs_Title_Custom_Control(
        $wp_customize, 'easyjobs_landing_job_list_column_padding', array(
        'type' => 'easyjobs-title',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_list_column_padding',
        'label' => __('Column padding', 'easyjobs'),
        'priority' => 136,
        'input_attrs' => array(
            'id' => 'easyjobs_landing_job_list_column_padding',
            'class' => 'easyjobs-dimension',
        ),
    )));
    
    //padding top
    $wp_customize->add_setting('easyjobs_landing_job_list_column_padding_top', array(
        'default' => $defaults['easyjobs_landing_job_list_column_padding_top'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_job_list_column_padding_top', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_list_column_padding_top',
        'label' => __('Top', 'easyjobs'),
        'priority' => 137,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_job_list_column_padding easyjobs-dimension',
        ),
    )));
    // padding right
    $wp_customize->add_setting('easyjobs_landing_job_list_column_padding_right', array(
        'default' => $defaults['easyjobs_landing_job_list_column_padding_right'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_job_list_column_padding_right', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_list_column_padding_right',
        'label' => __('Right', 'easyjobs'),
        'priority' => 138,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_job_list_column_padding easyjobs-dimension',
        ),
    )));
    
    //padding bottom
    $wp_customize->add_setting('easyjobs_landing_job_list_column_padding_bottom', array(
        'default' => $defaults['easyjobs_landing_job_list_column_padding_bottom'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_job_list_column_padding_bottom', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_list_column_padding_bottom',
        'label' => __('Bottom', 'easyjobs'),
        'priority' => 139,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_job_list_column_padding easyjobs-dimension',
        ),
    )));
    // padding left
    $wp_customize->add_setting('easyjobs_landing_job_list_column_padding_left', array(
        'default' => $defaults['easyjobs_landing_job_list_column_padding_left'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_landing_job_list_column_padding_left', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_list_column_padding_left',
        'label' => __('Left', 'easyjobs'),
        'priority' => 140,
        'input_attrs' => array(
            'class' => 'easyjobs_landing_job_list_column_padding easyjobs-dimension',
        ),
    )));
    
    // Column separator color
    $wp_customize->add_setting('easyjobs_landing_job_column_separator_color', array(
        'default' => $defaults['easyjobs_landing_job_column_separator_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_column_separator_color',
            array(
                'label' => __('Column Separator Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_column_separator_color',
                'priority' => 141
            ))
    );
    
    // Job title font size
    $wp_customize->add_setting('easyjobs_landing_job_title_font_size', array(
        'default' => $defaults['easyjobs_landing_job_title_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_job_title_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_title_font_size',
        'label' => __('Job Title Font Size', 'easyjobs'),
        'priority' => 142,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Job title font color
    $wp_customize->add_setting('easyjobs_landing_job_title_color', array(
        'default' => $defaults['easyjobs_landing_job_title_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_title_color',
            array(
                'label' => __('Job Title Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_title_color',
                'priority' => 143
            ))
    );
    
    // Job title hover font color
    $wp_customize->add_setting('easyjobs_landing_job_title_hover_color', array(
        'default' => $defaults['easyjobs_landing_job_title_hover_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_title_hover_color',
            array(
                'label' => __('Job Title Hover Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_title_hover_color',
                'priority' => 144
            ))
    );
    
    // Hide job metas
    $wp_customize->add_setting('easyjobs_landing_hide_job_metas', array(
        'default' => $defaults['easyjobs_landing_hide_job_metas'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_job_metas', array(
        'label' => esc_html__('Hide Job Metas', 'easyjobs'),
        'priority' => 145,
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_hide_job_metas',
        'type' => 'light', // light, ios, flat
    )));
    
    // Job meta font size
    $wp_customize->add_setting('easyjobs_landing_job_meta_font_size', array(
        'default' => $defaults['easyjobs_landing_job_meta_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_job_meta_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_meta_font_size',
        'label' => __('Job Meta Font Size', 'easyjobs'),
        'priority' => 146,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    // Company link color
    $wp_customize->add_setting('easyjobs_landing_job_meta_company_link_color', array(
        'default' => $defaults['easyjobs_landing_job_meta_company_link_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_meta_company_link_color',
            array(
                'label' => __('Job Meta Company Link Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_meta_company_link_color',
                'priority' => 147
            ))
    );
    // Location color
    $wp_customize->add_setting('easyjobs_landing_job_meta_location_color', array(
        'default' => $defaults['easyjobs_landing_job_meta_location_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_meta_location_color',
            array(
                'label' => __('Job Meta Location Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_meta_location_color',
                'priority' => 148
            ))
    );
    // Job deadline font size
    $wp_customize->add_setting('easyjobs_landing_job_deadline_font_size', array(
        'default' => $defaults['easyjobs_landing_job_deadline_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_job_deadline_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_deadline_font_size',
        'label' => __('Job Deadline Font Size', 'easyjobs'),
        'priority' => 149,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Job deadline color
    $wp_customize->add_setting('easyjobs_landing_job_deadline_color', array(
        'default' => $defaults['easyjobs_landing_job_deadline_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_deadline_color',
            array(
                'label' => __('Job Deadline Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_deadline_color',
                'priority' => 150
            ))
    );
    
    // Job deadline font size
    $wp_customize->add_setting('easyjobs_landing_job_vacancy_font_size', array(
        'default' => $defaults['easyjobs_landing_job_vacancy_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_job_vacancy_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_job_vacancy_font_size',
        'label' => __('Job Vacancy Font Size', 'easyjobs'),
        'priority' => 151,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Job deadline color
    $wp_customize->add_setting('easyjobs_landing_job_vacancy_color', array(
        'default' => $defaults['easyjobs_landing_job_vacancy_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_job_vacancy_color',
            array(
                'label' => __('Job Vacancy Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_job_vacancy_color',
                'priority' => 152
            ))
    );
    
    // Apply button font size
    $wp_customize->add_setting('easyjobs_landing_apply_btn_font_size', array(
        'default' => $defaults['easyjobs_landing_apply_btn_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_landing_apply_btn_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_landing_page_settings',
        'settings' => 'easyjobs_landing_apply_btn_font_size',
        'label' => __('Apply Button Font Size', 'easyjobs'),
        'priority' => 153,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Apply button color
    $wp_customize->add_setting('easyjobs_landing_apply_btn_color', array(
        'default' => $defaults['easyjobs_landing_apply_btn_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_apply_btn_color',
            array(
                'label' => __('Apply Button Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_apply_btn_color',
                'priority' => 154
            ))
    );
    
    // Apply button bg color
    $wp_customize->add_setting('easyjobs_landing_apply_btn_bg_color', array(
        'default' => $defaults['easyjobs_landing_apply_btn_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_apply_btn_bg_color',
            array(
                'label' => __('Apply Button Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_apply_btn_bg_color',
                'priority' => 155
            ))
    );
    
    // Apply button hover color
    $wp_customize->add_setting('easyjobs_landing_apply_btn_hover_color', array(
        'default' => $defaults['easyjobs_landing_apply_btn_hover_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_apply_btn_hover_color',
            array(
                'label' => __('Apply Button Hover Text Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_apply_btn_hover_color',
                'priority' => 156
            ))
    );
    
    // Apply button hover bg color
    $wp_customize->add_setting('easyjobs_landing_apply_btn_hover_bg_color', array(
        'default' => $defaults['easyjobs_landing_apply_btn_hover_bg_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_landing_apply_btn_hover_bg_color',
            array(
                'label' => __('Apply Button Hover Background Color', 'easyjobs'),
                'section' => 'easyjobs_landing_page_settings',
                'settings' => 'easyjobs_landing_apply_btn_hover_bg_color',
                'priority' => 157
            ))
    );

	/**
	 * Job Filter section
	 */
	$wp_customize->add_setting('easyjobs_landing_job_filter', array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
	));
	$ej_admin_url = sprintf("%s", admin_url('admin.php?page=easyjobs-settings#show-job-filter'));
	$wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize,
	'easyjobs_landing_job_filter', array(
		'label' => __('Job Filter', 'easyjobs'),
		'priority' => 158,
		'description' => "<span style='display: block; padding-top: 8px;'><span style='color: red'>Note: </span>Please make sure to enable the <strong>Show job filter</strong> and <strong>location filter</strong> options on <strong>company page</strong> from <a target='_blank' href='". $ej_admin_url ."'>here</a>.</span>",
		'settings' => 'easyjobs_landing_job_filter',
		'section' => 'easyjobs_landing_page_settings',
	)));

	// Show search by title input field
	$wp_customize->add_setting('easyjobs_landing_hide_job_search_by_title', array(
		'default' => $defaults['easyjobs_landing_hide_job_search_by_title'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_checkbox',
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_job_search_by_title', array(
		'label' => esc_html__('Show Search by Title', 'easyjobs'),
		'priority' => 159,
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_hide_job_search_by_title',
		'type' => 'light', // light, ios, flat
	)));

	// Show search by category input field
	$wp_customize->add_setting('easyjobs_landing_hide_job_search_by_category', array(
		'default' => $defaults['easyjobs_landing_hide_job_search_by_category'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_checkbox',
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_job_search_by_category', array(
		'label' => esc_html__('Show Search by Category', 'easyjobs'),
		'priority' => 160,
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_hide_job_search_by_category',
		'type' => 'light', // light, ios, flat
	)));

    // Show search by location input field
	$wp_customize->add_setting('easyjobs_landing_hide_job_search_by_location', array(
		'default' => $defaults['easyjobs_landing_hide_job_search_by_location'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_checkbox',
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_landing_hide_job_search_by_location', array(
		'label' => esc_html__('Show Search by Location', 'easyjobs'),
		'priority' => 161,
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_hide_job_search_by_location',
		'type' => 'light', // light, ios, flat
	)));

	$wp_customize->add_control(new Easyjobs_Dimension_Control(
		$wp_customize, 'easyjobs_landing_submit_btn_padding_left', array(
		'type' => 'easyjobs-dimension',
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_submit_btn_padding_left',
		'label' => __('Left', 'easyjobs'),
		'priority' => 162,
		'input_attrs' => array(
			'class' => 'easyjobs_landing_job_list_column_padding easyjobs-dimension',
		),
	)));

	// Submit button font size
	$wp_customize->add_setting('easyjobs_landing_submit_btn_font_size', array(
		'default' => $defaults['easyjobs_landing_submit_btn_font_size'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_integer'
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
		$wp_customize, 'easyjobs_landing_submit_btn_font_size', array(
		'type' => 'easyjobs-range-value',
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_submit_btn_font_size',
		'label' => __('Submit Button Font Size', 'easyjobs'),
		'priority' => 163,
		'input_attrs' => array(
			'min' => 0,
			'max' => 50,
			'step' => 1,
			'suffix' => 'px', //optional suffix
		),
	)));

	// Submit button color
	$wp_customize->add_setting('easyjobs_landing_submit_btn_color', array(
		'default' => $defaults['easyjobs_landing_submit_btn_color'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_submit_btn_color',
			array(
				'label' => __('Submit Button Text Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_submit_btn_color',
				'priority' => 164
			))
	);

	// Submit button bg color
	$wp_customize->add_setting('easyjobs_landing_submit_btn_bg_color', array(
		'default' => $defaults['easyjobs_landing_submit_btn_bg_color'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_submit_btn_bg_color',
			array(
				'label' => __('Submit Button Background Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_submit_btn_bg_color',
				'priority' => 165
			))
	);

	// Submit button hover color
	$wp_customize->add_setting('easyjobs_landing_submit_btn_hover_color', array(
		'default' => $defaults['easyjobs_landing_submit_btn_hover_color'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_submit_btn_hover_color',
			array(
				'label' => __('Submit Button Hover Text Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_submit_btn_hover_color',
				'priority' => 166
			))
	);

	// Submit button hover bg color
	$wp_customize->add_setting('easyjobs_landing_submit_btn_hover_bg_color', array(
		'default' => $defaults['easyjobs_landing_submit_btn_hover_bg_color'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_submit_btn_hover_bg_color',
			array(
				'label' => __('Submit Button Hover Background Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_submit_btn_hover_bg_color',
				'priority' => 167
			))
	);

	// Reset button font size
	$wp_customize->add_setting('easyjobs_landing_reset_btn_font_size', array(
		'default' => $defaults['easyjobs_landing_reset_btn_font_size'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_integer'
	));

	$wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
		$wp_customize, 'easyjobs_landing_reset_btn_font_size', array(
		'type' => 'easyjobs-range-value',
		'section' => 'easyjobs_landing_page_settings',
		'settings' => 'easyjobs_landing_reset_btn_font_size',
		'label' => __('Reset Button Font Size', 'easyjobs'),
		'priority' => 168,
		'input_attrs' => array(
			'min' => 0,
			'max' => 50,
			'step' => 1,
			'suffix' => 'px', //optional suffix
		),
	)));

	// Reset button color
	$wp_customize->add_setting('easyjobs_landing_reset_btn_color', array(
		'default' => $defaults['easyjobs_landing_reset_btn_color'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_reset_btn_color',
			array(
				'label' => __('Reset Button Text Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_reset_btn_color',
				'priority' => 169
			))
	);

	// Reset button bg color
	$wp_customize->add_setting('easyjobs_landing_reset_btn_bg_color', array(
		'default' => $defaults['easyjobs_landing_reset_btn_bg_color'],
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_reset_btn_bg_color',
			array(
				'label' => __('Reset Button Background Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_reset_btn_bg_color',
				'priority' => 170
			))
	);

	// Reset button hover color
	$wp_customize->add_setting('easyjobs_landing_reset_btn_hover_color', array(
		'default' => $defaults['easyjobs_landing_reset_btn_hover_color'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_reset_btn_hover_color',
			array(
				'label' => __('Reset Button Hover Text Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_reset_btn_hover_color',
				'priority' => 171
			))
	);

	// Reset button hover bg color
	$wp_customize->add_setting('easyjobs_landing_reset_btn_hover_bg_color', array(
		'default' => $defaults['easyjobs_landing_reset_btn_hover_bg_color'],
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'easyjobs_sanitize_rgba',
	));

	$wp_customize->add_control(
		new Easyjobs_Customizer_Alpha_Color_Control(
			$wp_customize,
			'easyjobs_landing_reset_btn_hover_bg_color',
			array(
				'label' => __('Reset Button Hover Background Color', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_reset_btn_hover_bg_color',
				'priority' => 172
			))
	);


	/**
	 * Showcase section
	 */

	$wp_customize->add_setting('easyjobs_landing_showcase_title', array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize,
		'easyjobs_landing_showcase_title', array(
			'label' => __('Showcase Image', 'easyjobs'),
			'priority' => 173,
			'settings' => 'easyjobs_landing_showcase_title',
			'section' => 'easyjobs_landing_page_settings',
		)));
	// Showcase section title
	$wp_customize->add_setting('easyjobs_landing_showcase_heading', array(
		'default' => $defaults['easyjobs_landing_showcase_heading'],
		'capability'    => 'edit_theme_options',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'easyjobs_landing_showcase_heading',
			array(
				'label' => __('Heading', 'easyjobs'),
				'section' => 'easyjobs_landing_page_settings',
				'settings' => 'easyjobs_landing_showcase_heading',
				'type' => 'text',
				'priority'=> 174
			)
		)
	);

    // ******* Job Details Settings Started ********** \\
    $wp_customize->add_section('easyjobs_single_page_settings', array(
        'title' => __('Job Details Page', 'easyjobs'),
        'priority' => 201
    ));
    
    // Container width
    $wp_customize->add_setting('easyjobs_single_container_width', array(
        'default' => $defaults['easyjobs_single_container_width'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_container_width', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_width',
        'label' => __('Container Width', 'easyjobs'),
        'priority' => 202,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => '%', //optional suffix
        ),
    )));
    
    // Container max width
    
    $wp_customize->add_setting('easyjobs_single_container_max_width', array(
        'default' => $defaults['easyjobs_single_container_max_width'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    
    $wp_customize->add_control(new Easyjobs_Number_Control(
        $wp_customize, 'easyjobs_single_container_max_width', array(
        'type' => 'easyjobs-number',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_max_width',
        'label' => __('Container Max Width', 'easyjobs'),
        'priority' => 203,
        'input_attrs' => array(
            'min' => 0,
            'suffix' => 'px',
        ),
    
    )));
    
    // Container padding
    
    $wp_customize->add_setting('easyjobs_single_container_padding', array(
        'default' => $defaults['easyjobs_single_container_padding'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Title_Custom_Control(
        $wp_customize, 'easyjobs_single_container_padding', array(
        'type' => 'easyjobs-title',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_padding',
        'label' => __('Content Padding', 'easyjobs'),
        'priority' => 204,
        'input_attrs' => array(
            'id' => 'easyjobs_single_container_padding',
            'class' => 'easyjobs-dimension',
        ),
    )));
    //padding top
    $wp_customize->add_setting('easyjobs_single_container_padding_top', array(
        'default' => $defaults['easyjobs_single_container_padding_top'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_container_padding_top', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_padding_top',
        'label' => __('Top', 'easyjobs'),
        'priority' => 205,
        'input_attrs' => array(
            'class' => 'easyjobs_single_container_padding easyjobs-dimension',
        ),
    )));
    // padding right
    $wp_customize->add_setting('easyjobs_single_container_padding_right', array(
        'default' => $defaults['easyjobs_single_container_padding_right'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_container_padding_right', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_padding_right',
        'label' => __('Right', 'easyjobs'),
        'priority' => 206,
        'input_attrs' => array(
            'class' => 'easyjobs_single_container_padding easyjobs-dimension',
        ),
    )));
    
    //padding bottom
    $wp_customize->add_setting('easyjobs_single_container_padding_bottom', array(
        'default' => $defaults['easyjobs_single_container_padding_bottom'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_container_padding_bottom', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_padding_bottom',
        'label' => __('Bottom', 'easyjobs'),
        'priority' => 207,
        'input_attrs' => array(
            'class' => 'easyjobs_single_container_padding easyjobs-dimension',
        ),
    )));
    // padding left
    $wp_customize->add_setting('easyjobs_single_container_padding_left', array(
        'default' => $defaults['easyjobs_single_container_padding_left'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_container_padding_left', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_container_padding_left',
        'label' => __('Left', 'easyjobs'),
        'priority' => 208,
        'input_attrs' => array(
            'class' => 'easyjobs_single_container_padding easyjobs-dimension',
        ),
    )));
    
    // Page bg color
    
    $wp_customize->add_setting('easyjobs_single_page_bg_color', array(
        'default' => $defaults['easyjobs_single_page_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_page_bg_color',
            array(
                'label' => __('Page Background Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_page_bg_color',
                'priority' => 209
            ))
    );
    
    // Single job page Banner image display
    $wp_customize->add_setting('easyjobs_single_display_job_banner', array(
        'default' => $defaults['easyjobs_single_display_job_banner'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_display_job_banner', array(
        'label' => esc_html__('Display Banner Image?', 'easyjobs'),
        'priority' => 210,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_display_job_banner',
        'type' => 'light', // light, ios, flat
    )));
    
    /**
     * Start job overview
     */
    
    $wp_customize->add_setting('easyjobs_job_overeview_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_job_overeview_title', array(
        'label' => __('Job overview', 'easyjobs'),
        'priority' => 211,
        'settings' => 'easyjobs_job_overeview_title',
        'section' => 'easyjobs_single_page_settings',
    )));
    
    // background Color
    
    $wp_customize->add_setting('easyjobs_single_job_overview_bg_color', array(
        'default' => $defaults['easyjobs_single_job_overview_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_job_overview_bg_color',
            array(
                'label' => __('Background Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_job_overview_bg_color',
                'priority' => 212
            ))
    );
    
    // Box padding
    
    $wp_customize->add_setting('easyjobs_single_job_overview_padding', array(
        'default' => $defaults['easyjobs_single_job_overview_padding'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    
    $wp_customize->add_control(new Easyjobs_Title_Custom_Control(
        $wp_customize, 'easyjobs_single_job_overview_padding', array(
        'type' => 'easyjobs-title',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_overview_padding',
        'label' => __('Content Padding', 'easyjobs'),
        'priority' => 213,
        'input_attrs' => array(
            'id' => 'easyjobs_single_job_overview_padding',
            'class' => 'easyjobs-dimension',
        ),
    )));
    //padding top
    $wp_customize->add_setting('easyjobs_single_job_overview_padding_top', array(
        'default' => $defaults['easyjobs_single_job_overview_padding_top'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    ));
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_job_overview_padding_top', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_overview_padding_top',
        'label' => __('Top', 'easyjobs'),
        'priority' => 214,
        'input_attrs' => array(
            'class' => 'easyjobs_single_job_overview_padding easyjobs-dimension',
        ),
    )));
    // padding right
    $wp_customize->add_setting('easyjobs_single_job_overview_padding_right', array(
        'default' => $defaults['easyjobs_single_job_overview_padding_right'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_job_overview_padding_right', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_overview_padding_right',
        'label' => __('Right', 'easyjobs'),
        'priority' => 215,
        'input_attrs' => array(
            'class' => 'easyjobs_single_job_overview_padding easyjobs-dimension',
        ),
    )));
    
    //padding bottom
    $wp_customize->add_setting('easyjobs_single_job_overview_padding_bottom', array(
        'default' => $defaults['easyjobs_single_job_overview_padding_bottom'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_job_overview_padding_bottom', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_overview_padding_bottom',
        'label' => __('Bottom', 'easyjobs'),
        'priority' => 216,
        'input_attrs' => array(
            'class' => 'easyjobs_single_job_overview_padding easyjobs-dimension',
        ),
    )));
    // padding left
    $wp_customize->add_setting('easyjobs_single_job_overview_padding_left', array(
        'default' => $defaults['easyjobs_single_job_overview_padding_left'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Dimension_Control(
        $wp_customize, 'easyjobs_single_job_overview_padding_left', array(
        'type' => 'easyjobs-dimension',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_overview_padding_left',
        'label' => __('Left', 'easyjobs'),
        'priority' => 217,
        'input_attrs' => array(
            'class' => 'easyjobs_single_job_overview_padding easyjobs-dimension',
        ),
    )));
    
    // Hide company info
    
    $wp_customize->add_setting('easyjobs_single_hide_company_info', array(
        'default' => $defaults['easyjobs_single_hide_company_info'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_hide_company_info', array(
        'label' => esc_html__('Hide Company Info', 'easyjobs'),
        'priority' => 218,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_hide_company_info',
        'type' => 'light', // light, ios, flat
    )));
    
    // Hide company logo
    
    $wp_customize->add_setting('easyjobs_single_hide_company_logo', array(
        'default' => $defaults['easyjobs_single_hide_company_logo'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_hide_company_logo', array(
        'label' => esc_html__('Hide Company Logo', 'easyjobs'),
        'priority' => 219,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_hide_company_logo',
        'type' => 'light', // light, ios, flat
    )));
    // Company name font size
    $wp_customize->add_setting('easyjobs_single_company_name_font_size', array(
        'default' => $defaults['easyjobs_single_company_name_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_company_name_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_company_name_font_size',
        'label' => __('Company Name Font Size', 'easyjobs'),
        'priority' => 220,
        'input_attrs' => array(
            'min' => 0,
            'max' => 60,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Company location font size
    $wp_customize->add_setting('easyjobs_single_company_location_font_size', array(
        'default' => $defaults['easyjobs_single_company_location_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_company_location_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_company_location_font_size',
        'label' => __('Company Location Font Size', 'easyjobs'),
        'priority' => 221,
        'input_attrs' => array(
            'min' => 0,
            'max' => 40,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Job info list font size
    $wp_customize->add_setting('easyjobs_single_job_info_list_font_size', array(
        'default' => $defaults['easyjobs_single_job_info_list_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_job_info_list_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_job_info_list_font_size',
        'label' => __('Job Info List Font Size', 'easyjobs'),
        'priority' => 222,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Job info list label color
    $wp_customize->add_setting('easyjobs_single_job_info_list_label_color', array(
        'default' => $defaults['easyjobs_single_job_info_list_label_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_job_info_list_label_color',
            array(
                'label' => __('Job Info List Label Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_job_info_list_label_color',
                'priority' => 223
            ))
    );
    
    // Job info list value color
    $wp_customize->add_setting('easyjobs_single_job_info_list_value_color', array(
        'default' => $defaults['easyjobs_single_job_info_list_value_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_job_info_list_value_color',
            array(
                'label' => __('Job Info List Value Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_job_info_list_value_color',
                'priority' => 224
            ))
    );
    /**
     * End job overview
     */
    
    /**
     * Apply button
     */
    // button section title
    $wp_customize->add_setting('easyjobs_apply_btn_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_apply_btn_title', array(
        'label' => __('Apply Button', 'easyjobs'),
        'priority' => 225,
        'settings' => 'easyjobs_apply_btn_title',
        'section' => 'easyjobs_single_page_settings',
    )));
    
    // Apply button font size
    $wp_customize->add_setting('easyjobs_single_apply_btn_font_size', array(
        'default' => $defaults['easyjobs_single_apply_btn_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_apply_btn_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_apply_btn_font_size',
        'label' => __('Apply Button Font Size', 'easyjobs'),
        'priority' => 226,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Apply button bg color
    $wp_customize->add_setting('easyjobs_single_apply_btn_bg_color', array(
        'default' => $defaults['easyjobs_single_apply_btn_bg_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_apply_btn_bg_color',
            array(
                'label' => __('Apply Button Background Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_apply_btn_bg_color',
                'priority' => 227
            ))
    );
    
    // Apply button text color
    
    $wp_customize->add_setting('easyjobs_single_apply_btn_text_color', array(
        'default' => $defaults['easyjobs_single_apply_btn_text_color'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_apply_btn_text_color',
            array(
                'label' => __('Apply Button Text Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_apply_btn_text_color',
                'priority' => 228
            ))
    );
    
    // Apply button hover bg color
    $wp_customize->add_setting('easyjobs_single_apply_btn_hover_bg_color', array(
        'default' => $defaults['easyjobs_single_apply_btn_hover_bg_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_apply_btn_hover_bg_color',
            array(
                'label' => __('Apply Button Hover Background Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_apply_btn_hover_bg_color',
                'priority' => 229
            ))
    );
    
    // Apply button hover text color
    
    $wp_customize->add_setting('easyjobs_single_apply_btn_hover_text_color', array(
        'default' => $defaults['easyjobs_single_apply_btn_hover_text_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_rgba',
    ));
    
    $wp_customize->add_control(
        new Easyjobs_Customizer_Alpha_Color_Control(
            $wp_customize,
            'easyjobs_single_apply_btn_hover_text_color',
            array(
                'label' => __('Apply Button Hover Text Color', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_apply_btn_hover_text_color',
                'priority' => 230
            ))
    );
    /**
     * End Apply button
     */
    
    /**
     * Social sharing
     */
    // social sharing section title
    $wp_customize->add_setting('easyjobs_single_social_sharing_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_social_sharing_title', array(
        'label' => __('Social Sharing', 'easyjobs'),
        'priority' => 231,
        'settings' => 'easyjobs_single_social_sharing_title',
        'section' => 'easyjobs_single_page_settings',
    )));
    // disable social sharing
    $wp_customize->add_setting('easyjobs_single_disable_social_sharing', array(
        'default' => $defaults['easyjobs_single_disable_social_sharing'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_disable_social_sharing', array(
        'label' => esc_html__('Disable Social Sharing', 'easyjobs'),
        'priority' => 232,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_disable_social_sharing',
        'type' => 'light',
    )));
    
    // disable social sharing facebook
    $wp_customize->add_setting('easyjobs_single_disable_social_sharing_fb', array(
        'default' => $defaults['easyjobs_single_disable_social_sharing_fb'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_disable_social_sharing_fb', array(
        'label' => esc_html__('Disable Facebook', 'easyjobs'),
        'priority' => 233,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_disable_social_sharing_fb',
        'type' => 'light',
    )));
    
    // disable social sharing twitter
    $wp_customize->add_setting('easyjobs_single_disable_social_sharing_twitter', array(
        'default' => $defaults['easyjobs_single_disable_social_sharing_twitter'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_disable_social_sharing_twitter', array(
        'label' => esc_html__('Disable Twitter', 'easyjobs'),
        'priority' => 234,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_disable_social_sharing_twitter',
        'type' => 'light',
    )));
    
    // disable social sharing linkedin
    $wp_customize->add_setting('easyjobs_single_disable_social_sharing_linkedin', array(
        'default' => $defaults['easyjobs_single_disable_social_sharing_linkedin'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'easyjobs_sanitize_checkbox',
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Toggle_Control($wp_customize, 'easyjobs_single_disable_social_sharing_linkedin', array(
        'label' => esc_html__('Disable Linkedin', 'easyjobs'),
        'priority' => 235,
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_disable_social_sharing_linkedin',
        'type' => 'light',
    )));
    
    // Icon size
    $wp_customize->add_setting('easyjobs_single_social_sharing_icon_bg_size', array(
        'default' => $defaults['easyjobs_single_social_sharing_icon_bg_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_social_sharing_icon_bg_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_social_sharing_icon_bg_size',
        'label' => __('Social Icon Background Size', 'easyjobs'),
        'priority' => 236,
        'input_attrs' => array(
            'min' => 20,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // Icon size
    $wp_customize->add_setting('easyjobs_single_social_sharing_icon_size', array(
        'default' => $defaults['easyjobs_single_social_sharing_icon_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_social_sharing_icon_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_social_sharing_icon_size',
        'label' => __('Social Icon Size', 'easyjobs'),
        'priority' => 237,
        'input_attrs' => array(
            'min' => 10,
            'max' => 50,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    /**
     * End Social sharing
     */
    /**
     * Job Details
     */
    $wp_customize->add_setting('easyjobs_single_job_details_section', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_job_details_section', array(
        'label' => __('Job Details', 'easyjobs'),
        'priority' => 238,
        'settings' => 'easyjobs_single_job_details_section',
        'section' => 'easyjobs_single_page_settings',
    )));
    $wp_customize->add_setting('easyjobs_single_job_description_title', array(
        'default' => $defaults['easyjobs_single_job_description_title'],
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'easyjobs_single_job_description_title',
            array(
                'label' => __('Job Description Heading', 'easyjobs'),
                'section' => 'easyjobs_single_page_settings',
                'settings' => 'easyjobs_single_job_description_title',
                'type' => 'text',
                'priority'=> 239
            )
        )
    );

	/**
	 * Job Responsibility
	 */
	$wp_customize->add_setting('easyjobs_single_job_responsibility_section', array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_job_responsibility_section', array(
		'label' => __('Job Responsibility', 'easyjobs'),
		'priority' => 239,
		'settings' => 'easyjobs_single_job_responsibility_section',
		'section' => 'easyjobs_single_page_settings',
	)));
	$wp_customize->add_setting('easyjobs_single_job_responsibility_title', array(
		'default' => $defaults['easyjobs_single_job_responsibility_title'],
		'capability'    => 'edit_theme_options',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'easyjobs_single_job_responsibility_title',
			array(
				'label' => __('Job Description Heading', 'easyjobs'),
				'section' => 'easyjobs_single_page_settings',
				'settings' => 'easyjobs_single_job_responsibility_title',
				'type' => 'text',
				'priority'=> 239
			)
		)
	);

	/**
	 * Job Benefits
	 */
	$wp_customize->add_setting('easyjobs_single_job_benefits_section', array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_job_benefits_section', array(
		'label' => __('Job Benifits', 'easyjobs'),
		'priority' => 239,
		'settings' => 'easyjobs_single_job_benefits_section',
		'section' => 'easyjobs_single_page_settings',
	)));
	$wp_customize->add_setting('easyjobs_single_job_benefits_title', array(
		'default' => $defaults['easyjobs_single_job_benefits_title'],
		'capability'    => 'edit_theme_options',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'easyjobs_single_job_benefits_title',
			array(
				'label' => __('Job Benefits Heading', 'easyjobs'),
				'section' => 'easyjobs_single_page_settings',
				'settings' => 'easyjobs_single_job_benefits_title',
				'type' => 'text',
				'priority'=> 239
			)
		)
	);

	/**
	 * Showcase image
	 */
	$wp_customize->add_setting('easyjobs_single_showcase_section', array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_showcase_section', array(
		'label' => __('Showcase Image', 'easyjobs'),
		'priority' => 239,
		'settings' => 'easyjobs_single_showcase_section',
		'section' => 'easyjobs_single_page_settings',
	)));
	$wp_customize->add_setting('easyjobs_single_showcase_title', array(
		'default' => $defaults['easyjobs_single_showcase_title'],
		'capability'    => 'edit_theme_options',
		'sanitize_callback' => 'esc_html',
	));

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'easyjobs_single_showcase_title',
			array(
				'label' => __('Showcase Heading', 'easyjobs'),
				'section' => 'easyjobs_single_page_settings',
				'settings' => 'easyjobs_single_showcase_title',
				'type' => 'text',
				'priority'=> 239
			)
		)
	);
    
    
    /**
     * Page Typography
     */
    // page typography section title
    $wp_customize->add_setting('easyjobs_single_page_typography_title', array(
        'default' => '',
        'sanitize_callback' => 'esc_html',
    ));
    
    $wp_customize->add_control(new Easyjobs_Separator_Custom_Control($wp_customize, 'easyjobs_single_page_typography_title', array(
        'label' => __('Page Typography', 'easyjobs'),
        'priority' => 239,
        'settings' => 'easyjobs_single_page_typography_title',
        'section' => 'easyjobs_single_page_settings',
    )));
    
    // H1 font size
    $wp_customize->add_setting('easyjobs_single_h1_font_size', array(
        'default' => $defaults['easyjobs_single_h1_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h1_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h1_font_size',
        'label' => __('H1 Font Size', 'easyjobs'),
        'priority' => 239,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // H2 font size
    $wp_customize->add_setting('easyjobs_single_h2_font_size', array(
        'default' => $defaults['easyjobs_single_h2_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h2_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h2_font_size',
        'label' => __('H2 Font Size', 'easyjobs'),
        'priority' => 240,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // H3 font size
    $wp_customize->add_setting('easyjobs_single_h3_font_size', array(
        'default' => $defaults['easyjobs_single_h3_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h3_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h3_font_size',
        'label' => __('H3 Font Size', 'easyjobs'),
        'priority' => 241,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // H4 font size
    $wp_customize->add_setting('easyjobs_single_h4_font_size', array(
        'default' => $defaults['easyjobs_single_h3_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h4_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h4_font_size',
        'label' => __('H4 Font Size', 'easyjobs'),
        'priority' => 242,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // H5 font size
    $wp_customize->add_setting('easyjobs_single_h5_font_size', array(
        'default' => $defaults['easyjobs_single_h5_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h5_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h5_font_size',
        'label' => __('H5 Font Size', 'easyjobs'),
        'priority' => 243,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    // H6 font size
    $wp_customize->add_setting('easyjobs_single_h6_font_size', array(
        'default' => $defaults['easyjobs_single_h6_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_h6_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_h6_font_size',
        'label' => __('H6 Font Size', 'easyjobs'),
        'priority' => 244,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    $wp_customize->add_setting('easyjobs_single_section_heading_font_size', array(
        'default' => $defaults['easyjobs_single_section_heading_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_section_heading_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_section_heading_font_size',
        'label' => __('Section Heading Font Size', 'easyjobs'),
        'priority' => 245,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
    
    $wp_customize->add_setting('easyjobs_single_text_font_size', array(
        'default' => $defaults['easyjobs_single_text_font_size'],
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'easyjobs_sanitize_integer'
    
    ));
    
    $wp_customize->add_control(new Easyjobs_Customizer_Range_Value_Control(
        $wp_customize, 'easyjobs_single_text_font_size', array(
        'type' => 'easyjobs-range-value',
        'section' => 'easyjobs_single_page_settings',
        'settings' => 'easyjobs_single_text_font_size',
        'label' => __('Body Content Font Size', 'easyjobs'),
        'priority' => 245,
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'suffix' => 'px', //optional suffix
        ),
    )));
	do_action('easyjobs_details_customizer_controls', $wp_customize, 'easyjobs_single_page_settings');

    // ******* Job Details Settings End ********** \\
    
    
    // Create custom panels
    
    $wp_customize->add_panel('easyjobs_customize_options', array(
        'priority' => 30,
        'theme_supports' => '',
        'title' => __('Easyjobs', 'easyjobs'),
        'description' => __('Controls the design settings for Easyjobs pages.', 'easyjobs'),
    ));
    
    // Assign sections to panels
    $wp_customize->get_section('easyjobs_single_page_settings')->panel = 'easyjobs_customize_options';
    $wp_customize->get_section('easyjobs_landing_page_settings')->panel = 'easyjobs_customize_options';
}

add_action('customize_register', 'easyjobs_customize_register');

require_once(EASYJOBS_ADMIN_DIR_PATH . 'customizer/output-css.php');
