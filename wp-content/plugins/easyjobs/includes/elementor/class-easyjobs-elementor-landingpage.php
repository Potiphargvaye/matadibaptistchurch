<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Widget_Base;
use \Elementor\Plugin;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;

class Easyjobs_Elementor_Landingpage extends Widget_Base {
	use Easyjobs_Elementor_Template;

	protected $is_editor;
	protected $company_info;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->is_editor = Plugin::$instance->editor->is_edit_mode();
	}
	
	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_name() {
		return 'easyjobs-landingpage';
	}

	public function get_title() {
		return esc_html__( 'Easyjobs Landing Page', 'easyjobs' );
	}

	public function get_icon() {
		return 'eicon-post-info';
	}

	public function get_categories() {
		return array( 'easyjobs' );
	}

	public function get_keywords() {
		return array(
			'easyjobs',
			'jobs',
		);
	}

	public function get_custom_help_url() {
		return 'https://easy.jobs/docs/';
	}

	/**
	 * Get company info from api
	 *
	 * @return object|bool
	 * @since 1.0.0
	 */
	private function get_company_info() {

		// if ( ! $this->is_editor ) {
		// 	$company_info = Easyjobs_Api::get( 'company' );

		// 	return ( ! empty( $company_info ) && $company_info->status == 'success' ) ? $company_info->data : array();
		// }

		// // cache only editor mode
		// $key     = 'elej_company_' . md5( $this->get_token() );
		// $company = ''; //get_transient( $key );
		// if ( empty( $company ) ) {
		// 	$company_info = Easyjobs_Api::get( 'company' );
		// 	if ( ! empty( $company_info ) && $company_info->status == 'success' ) {
		// 		$company = $company_info->data;
		// 		set_transient( $key, $company, 0.5 * HOUR_IN_SECONDS );
		// 	}
		// }
		$company = Easyjobs_Helper::get_company_details(true);

		return $company;
	}

	/**
	 * Get published job from api
	 *
	 * @param  array $arg
	 *
	 * @return object|false
	 * @since 1.0.0
	 */
	private function get_published_jobs( $arg = array() ) {

		$query_param = wp_parse_args(
            $arg,
            array(
				'rows'   => 10,
				// 'orderby' => 'id:desc',
				'orderby' => 'title',
				'order' => 'desc',
				'paginate' => true
			)
        );
		if ( ! $this->is_editor ) {
			$job_info = Easyjobs_Api::get( 'published_jobs', $query_param );
	
			return $job_info->status == 'success' ? $job_info->data : array();
		}

		// cache only editor mode
		$arg = array(
			'key'     => $this->get_token(),
			'rows'   => $query_param['rows'],
			'orderby' => $query_param['orderby'],
			'order' => $query_param['order'],
            'status' => $query_param['status'],
		);

		$key  = 'elej_job_' . md5( implode( '', $arg ) );
		$jobs = get_transient( $key );
		if ( empty( $jobs ) ) {
			$job_info = Easyjobs_Api::get( 'published_jobs', $query_param );
			if ( $job_info->status === 'success' ) {
				$jobs = $job_info->data;
				set_transient( $key, $jobs, 0.5 * HOUR_IN_SECONDS );
			}
		}

		return $jobs;
	}

	/**
	 * Get published job location from api
	 *
	 * @param  array $arg
	 *
	 * @return object|false
	 * @since 2.4.1
	 */
	private function get_job_locations() {
		$locations = Easyjobs_Api::get( 'job_locations' );
		
		return $locations->status == 'success' ? $locations->data : array();
	}

	private function get_token() {
		$settings = EasyJobs_DB::get_settings();

		return ! empty( $settings['easyjobs_api_key'] ) ? $settings['easyjobs_api_key'] : false;
	}


	public function check_token() {

		$this->start_controls_section(
			'easyjobs_api_warning',
			array(
				'label' => __( 'Warning!', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_api_warning_text',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<span style="line-height: 1.5; font-size: 14px">' . __('Please ','easyjobs') .
					'<strong><a href="' . admin_url( 'admin.php?page=easyjobs-admin' ) . '" target="_blank">'.__('Connect your EasyJobs Account','easyjobs').'</a> </strong> ' . __('from <b>WordPress Dashboard > EasyJobs > Get Started > Sign In / Connect via API</b>. For more info, ', 'easyjobs') . '<a href="' . esc_url( 'https://easy.jobs/docs/easyjobs-wordpress/' ) . '" target="_blank">'.__('Click here','easyjobs').'</a></span>',
				'content_classes' => 'elej-warning',
			)
		);

		$this->end_controls_section();

	}

	protected function register_controls() {

		if ( ! $this->get_token() ) {
			$this->check_token();

			return;
		}

		// content tab
		$this->content_company_image_control();
		$this->content_job_filter_control();
		$this->content_job_list_control();
		$this->content_cange_text();

		// style tab
		$this->style_general_controls();
		$this->style_section_controls();
		$this->style_company_info_control();
		$this->style_job_list_filter_control();
		$this->style_job_list_control();
	}

	public function content_company_image_control() {
		$this->start_controls_section(
			'section_easyjobs_info_box',
			array(
				'label' => 'EasyJobs',
			)
		);

		$this->add_control(
			'easyjobs_company_details_control',
			array(
				'label'        => __( 'Hide Company Details', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'easyjobs_cover_image_control',
			array(
				'label'        => __( 'Change Cover Image', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'condition'    => array(
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);

		$this->add_control(
			'easyjobs_cover_image',
			array(
				'label'     => __( 'Upload Cover Image', 'easyjobs' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'easyjobs_cover_image_control'      => 'yes',
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'easyjobs_cover_image',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'easyjobs_cover_image_control'      => 'yes',
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);

		$this->add_control(
			'easyjobs_logo_control',
			array(
				'label'        => __( 'Change Logo', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'condition'    => array(
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);
		$this->add_control(
			'easyjobs_logo',
			array(
				'label'     => __( 'Upload Cover Image', 'easyjobs' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'easyjobs_logo_control'             => 'yes',
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'easyjobs_logo',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'easyjobs_logo_control'             => 'yes',
					'easyjobs_company_details_control!' => 'yes',
				),
			)
		);

		$this->add_control(
			'easyjobs_job_list_control',
			array(
				'label'        => __( 'Hide Job List', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'easyjobs_company_gallery_control',
			array(
				'label'        => __( 'Hide Company Gallery', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	public function content_job_filter_control() {
		$this->start_controls_section(
			'easyjobs_job_filter_content',
			array(
				'label' => __( 'Job Filter', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$ejel_admin_url_landing = sprintf("%s", admin_url('admin.php?page=easyjobs-settings#show-job-filter'));
		$this->add_control(
			'important_note_landing',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => "<strong style='color: red'>Note: </strong><i>" . esc_html__( "Please make sure to enable the Show job filter and location filter options on company page from ", 'easyjobs' ) . "<a href='$ejel_admin_url_landing' target='_blank'>here</a></i>",
				'content_classes' => 'your-class',
			]
		);

		$this->add_control(
			'easyjobs_job_filter_by_title_control',
			array(
				'label'        => __( 'Show Search by Title', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'easyjobs_job_filter_by_category_control',
			array(
				'label'        => __( 'Show Search by Category', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'easyjobs_job_filter_by_location_control',
			array(
				'label'        => __( 'Show Search by Location', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	public function content_job_list_control() {
		$this->start_controls_section(
			'easyjobs_job_list_query',
			array(
				'label'     => __( 'Job List', 'easyjobs' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'easyjobs_job_list_control!' => 'yes',
				),
			)
		);

		$this->add_control(
			'easyjobs_job_list_order_by',
			array(
				'label'   => __( 'Order BY', 'easyjobs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'title'        => __( 'Title', 'easyjobs' ),
					'published_at' => __( 'Published Date', 'easyjobs' ),
					'expired_at' => __( 'Expired Date', 'easyjobs' ),
					'created_at'   => __( 'Created Date', 'easyjobs' ),
				),
			)
		);

		$this->add_control(
			'easyjobs_job_list_sort_by',
			array(
				'label'   => __( 'Sort BY', 'easyjobs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'asc',
				'options' => array(
					'asc'  => __( 'ASC', 'easyjobs' ),
					'desc' => __( 'DESC', 'easyjobs' ),
				),
			)
		);

		$this->add_control(
			'easyjobs_jobs_per_page',
			array(
				'label'   => esc_html__( 'Show Jobs', 'easyjobs' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 2,
				),
            )
        );

		$this->add_control(
			'easyjobs_show_open_job',
			array(
				'label'        => __( 'Show Open Job Only', 'easyjobs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easyjobs' ),
				'label_off'    => __( 'No', 'easyjobs' ),
				'return_value' => 'yes',
				'default'      => 'no',

			)
		);

		$this->end_controls_section();
	}

	public function content_cange_text() {
		$this->start_controls_section(
			'easyjobs_section_text_cahnge',
			array(
				'label' => __( 'Text Change', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'easyjobs_company_name',
			array(
				'label'       => __( 'Company Name', 'easyjobs' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '', 'easyjobs' ),
				'placeholder' => __( 'Enter Company Name', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_website_link_text',
			array(
				'label'       => __( 'Website Link Text', 'easyjobs' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Explore company website', 'easyjobs' ),
				'placeholder' => __( 'Explore company website', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_joblist_heading',
			array(
				'label'       => __( 'Job List Section Title', 'easyjobs' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Open job positions', 'easyjobs' ),
				'placeholder' => __( 'Open job positions', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_joblist_apply_button_text',
			array(
				'label'       => __( 'Apply Button Text', 'easyjobs' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Apply', 'easyjobs' ),
				'placeholder' => __( 'Apply', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_gallery_section_text',
			array(
				'label'       => __( 'Gallery Section Title', 'easyjobs' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Life at', 'easyjobs' ),
				'placeholder' => __( 'Life at', 'easyjobs' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * It prints controls for managing general style of Easyjobs landing page
	 */
	public function style_general_controls() {
		$this->start_controls_section(
            'section_style_general',
            array(
				'label' => __( 'General', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
        );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'easyjobs_landingpage_background',
				'label'    => __( 'Background', 'easyjobs' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .easyjobs-elementor,{{WRAPPER}} .easyjobs-elementor .ej-header,{{WRAPPER}} .easyjobs-elementor .ej-job-list-item',
			)
		);

		$this->add_responsive_control(
			'easyjobs_landingpage_alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'easyjobs' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'easyjobs' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'easyjobs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'easyjobs' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'ej-landingpage-alignment-',
				'default'      => 'center',
			)
		);

		$this->add_responsive_control(
			'easyjobs_landingpage_width',
			array(
				'label'      => esc_html__( 'Width', 'easyjobs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1300,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-elementor' => 'width: {{SIZE}}{{UNIT}};',
				),
            )
        );

		$this->add_control(
			'easyjobs_landingpage_margin',
			array(
				'label'      => __( 'Margin', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-elementor' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
            )
        );

		$this->add_control(
			'easyjobs_landingpage_padding',
			array(
				'label'      => __( 'Form Padding', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
            )
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'easyjobs_landingpage_boxshadow',
				'selector' => '{{WRAPPER}} .easyjobs-elementor',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * It prints controls for managing section heading
	 */
	public function style_section_controls() {
		$this->start_controls_section(
            'style_easyjobs_section',
            array(
				'label' => __( 'Section', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
        );

		$this->add_control(
			'easyjobs_section_heading_margin',
			array(
				'label'      => __( 'Margin', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ej-section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ej-template-elegant .ej-col .section__header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
            )
        );

		$this->add_control(
			'easyjobs_section_heading_padding',
			array(
				'label'      => __( 'Padding', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ej-section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ej-template-elegant .ej-col .section__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
            )
        );

		$this->add_control(
			'easyjobs_landingpage_section_heading',
			array(
				'label'     => __( 'Section Heading', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_section_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title .ej-section-title-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ej-template-elegant .ej-col .section__header h2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_section_heading_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-section-title .ej-section-title-text, {{WRAPPER}} .ej-template-elegant .ej-col .section__header h2',
			)
		);

		$this->add_control(
			'easyjobs_landingpage_section_icon',
			array(
				'label'     => __( 'Section Icon', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_landingpage_section_icon_width',
			array(
				'label'     => __( 'Width', 'easyjobs' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_landingpage_section_icon_height',
			array(
				'label'     => __( 'Height', 'easyjobs' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title-icon' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_section_icon_background',
			array(
				'label'     => esc_html__( 'Background', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title span.ej-section-title-icon' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_section_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title span.ej-section-title-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_landingpage_section_icon_size',
			array(
				'label'     => __( 'Icon Size', 'easyjobs' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .ej-section-title i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * prints controls for managing Company details
	 */
	public function style_company_info_control() {
		$this->start_controls_section(
            'section_style_company_info',
            array(
				'label' => __( 'Company Info', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
        );
		$this->add_control(
			'easyjobs_landingpage_company_title',
			array(
				'label'     => __( 'Title', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_company_title_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-company-info .name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_company_title_text_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-company-info .name',
			)
		);

		$this->add_control(
			'easyjobs_landingpage_company_location',
			array(
				'label'     => __( 'Location', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_company_location_color',
			array(
				'label'     => esc_html__( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-company-info .location' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ej-template-classic .ej-company-info .location span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_company_location_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-company-info .location, {{WRAPPER}} .ej-template-classic .ej-company-info .location span',
			)
		);

		$this->style_company_link_button();

		$this->add_control(
			'easyjobs_landingpage_company_description',
			array(
				'label'     => __( 'Description', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'easyjobs_company_description_align',
			array(
				'label'     => __( 'Alignment', 'easyjobs' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'easyjobs' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'easyjobs' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'easyjobs' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'easyjobs' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ej-company-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ej-company-description h2' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ej-company-description p' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ej-company-description ul li' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ej-company-description ol li' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .ej-company-description blockquote' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_company_description_text_color',
			array(
				'label'     => esc_html__( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ej-company-description' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description h1' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description h2' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description p' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description ul li' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description ol li' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-company-description blockquote' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_company_description_text_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-company-description, {{WRAPPER}} .ej-company-description h2, {{WRAPPER}} .ej-company-description p, {{WRAPPER}} .ej-company-description ul li, {{WRAPPER}} .ej-company-description ol li, {{WRAPPER}} .ej-company-description blockquote',
			)
		);

		$this->end_controls_section();
	}


	/**
	 * prints controls for managing Button style
	 */
	public function style_company_link_button() {

		$this->add_control(
			'easyjobs_landingpage_company_button',
			array(
				'label'     => __( 'Website Link Button', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_company_button_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-header-tools .ej-info-btn, {{WRAPPER}} .ej-template-classic .carrier__company > a.button__success',
			)
		);

		$this->start_controls_tabs( 'easyjobs_company_tabs_button_style' );

		$this->start_controls_tab(
			'easyjobs_company_tab_button_normal',
			array(
				'label' => __( 'Normal', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_button_color',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1fb6d7',
				'selectors' => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'easyjobs_company_tab_button_hover',
			array(
				'label' => __( 'Hover', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_button_color_hover',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_company_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1fb6d7',
				'selectors' => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn:hover' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'easyjobs_company_button_border',
				'selector'  => '{{WRAPPER}} .ej-header-tools .ej-info-btn, {{WRAPPER}} .ej-template-classic .carrier__company > a.button__success',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_company_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'easyjobs_company_button_box_shadow',
				'selector' => '{{WRAPPER}} .ej-header-tools .ej-info-btn, {{WRAPPER}} .ej-template-classic .carrier__company > a.button__success',
			)
		);

		$this->add_responsive_control(
			'easyjobs_company_button_box_padding',
			array(
				'label'      => __( 'Padding', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ej-header-tools .ej-info-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ej-template-classic .carrier__company > a.button__success' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);
	}

	/**
	 * Style controls for managing Job list filter snubmit & reset button
	 */

	public function style_job_list_filter_control() {
		$this->start_controls_section(
			'section_style_job_list_filter_landing',
			array(
				'label' => __( 'Job Filter', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'easyjobs_landingpage_submit_button',
			array(
				'label'     => __( 'Submit Button', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_landing_submit_button_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light',
			)
		);

		$this->start_controls_tabs( 'easyjobs_landing_submit_button_style' );

		$this->start_controls_tab(
			'easyjobs_company_submit_button_normal',
			array(
				'label' => __( 'Normal', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_submit_button_color',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff9635',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_landing_submit_button_background_color',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff96351a',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'easyjobs_company_filter_submit_tab_button_hover',
			array(
				'label' => __( 'Hover', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_submit_button_color_hover',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff9635',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_submit_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff96351a',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-info-btn-light:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();




		$this->add_control(
			'easyjobs_landingpage_reset_button',
			array(
				'label'     => __( 'Reset Button', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_landing_reset_button_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn',
			)
		);

		$this->start_controls_tabs( 'easyjobs_landing_reset_button_style' );

		$this->start_controls_tab(
			'easyjobs_company_reset_button_normal',
			array(
				'label' => __( 'Normal', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_reset_button_color',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff5f74',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_landing_reset_button_background_color',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,95,116,0.1)',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'easyjobs_company_filter_reset_tab_button_hover',
			array(
				'label' => __( 'Hover', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_reset_button_color_hover',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff5f74',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_company_landing_reset_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,95,116,0.1)',
				'selectors' => array(
					'{{WRAPPER}} .ej-job-filter-wrap .ej-job-filter-form .ej-danger-btn:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * prints controls for managing Job list box
	 */
	public function style_job_list_control() {

		$this->start_controls_section(
            'section_style_job_list',
            array(
				'label' => __( 'Job List', 'easyjobs' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
        );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'easyjobs_joblist_background_color',
				'label'    => __( 'Background', 'easyjobs' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card',
			)
		);

		$this->add_control(
			'easyjobs_joblist_bar_color',
			array(
				'label'     => __( 'Separator Color ', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col' => 'border-right-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'easyjobs_joblist_box_padding',
			array(
				'label'      => __( 'Padding', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'easyjobs_joblist_box_margin',
			array(
				'label'      => __( 'Margin', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'easyjobs_joblist_border',
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card',
			)
		);

		$this->add_control(
			'easyjobs_joblist__border_radius',
			array(
				'label'      => __( 'Border Radius', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'easyjobs_joblist_box_shadow',
				'selector'  => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card',
				'separator' => 'before',
			)
		);

		// Title section
		$this->add_control(
			'easyjobs_joblist_title_section',
			array(
				'label'     => __( 'Job Title', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_joblist_title_color',
			array(
				'label'     => __( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card h3 a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_joblist_title_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title a, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card h3 a',
			)
		);

		$this->add_responsive_control(
			'easyjobs_joblist_title_space',
			array(
				'label'      => __( 'Space', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-title' => 'padding-bottom:{{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card h3' => 'padding-bottom:{{BOTTOM}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		// Category section
		$this->add_control(
			'easyjobs_joblist_category_section',
			array(
				'label'     => __( 'Company Name', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_joblist_category_color',
			array(
				'label'     => __( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name i,{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__name, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__name i' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__name, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__name i' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_joblist_category_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name i,{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-company-name a, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__name, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__name i, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__name, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__name i',
			)
		);

		// Location section
		$this->add_control(
			'easyjobs_joblist_location_section',
			array(
				'label'     => __( 'Job Location', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_joblist_location_color',
			array(
				'label'     => __( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location i,{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location span' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__location span, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__location i' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__location span, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__location i' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_joblist_location_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location i,{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-info-block.ej-job-list-location span, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__location span, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-info-block.office__location i, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__location span, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .office__location i',
			)
		);

		// Deadline section
		$this->add_control(
			'easyjobs_joblist_deadline_section',
			array(
				'label'     => __( 'Job Deadline', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_joblist_deadline_color',
			array(
				'label'     => __( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-item-col .ej-deadline' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .deadline' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_joblist_deadline_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-item-col .ej-deadline, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .deadline',
			)
		);

		// vacancies section
		$this->add_control(
			'easyjobs_joblist_vacancies_section',
			array(
				'label'     => __( 'Job Vacancies', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_joblist_vacancies_color',
			array(
				'label'     => __( 'Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-item .job__vacancy h4' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-item .job__vacancy p' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__vacancy h4' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__vacancy p' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_joblist_vacancies_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-list-item-inner .ej-job-list-item-col .ej-list-sub, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-item .job__vacancy h4, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-classic .ej-job-list-item .job__vacancy p, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__vacancy h4, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__vacancy p',
			)
		);

		$this->style_job_apply_button();

		$this->end_controls_section();
	}

	/**
	 * prints controls for managing Job list apply button
	 */
	public function style_job_apply_button() {

		$this->add_control(
			'easyjobs_job_apply_button',
			array(
				'label'     => __( 'Job Apply Button', 'easyjobs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'easyjobs_job_apply_button_typography',
				'label'    => __( 'Typography', 'easyjobs' ),
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary',
			)
		);

		$this->add_responsive_control(
			'easyjobs_job_apply_btn_alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'easyjobs' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'easyjobs' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'easyjobs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'easyjobs' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'ej-job-apply-btn-alignment-',
				'default'      => 'center',
			)
		);

		$this->start_controls_tabs( 'easyjobs_job_apply_tabs_button_style' );

		$this->start_controls_tab(
			'easyjobs_job_apply_tab_button_normal',
			array(
				'label' => __( 'Normal', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_job_apply_button_color',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_job_apply_background_color',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'easyjobs_job_apply_tab_button_hover',
			array(
				'label' => __( 'Hover', 'easyjobs' ),
			)
		);

		$this->add_control(
			'easyjobs_job_apply_button_color_hover',
			array(
				'label'     => __( 'Text Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'easyjobs_job_apply_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'easyjobs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'easyjobs_job_apply_button_border',
				'selector'  => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'easyjobs_job_apply_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'easyjobs_job_apply_button_box_shadow',
				'selector' => '{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success, {{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary',
			)
		);

		$this->add_responsive_control(
			'easyjobs_job_apply_button_box_padding',
			array(
				'label'      => __( 'Padding', 'easyjobs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .ej-job-apply-btn .ej-info-btn-light' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list .ej-job-list-item .job__apply .button__success' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .easyjobs-shortcode-wrapper .ej-job-list-elegant.ej-job-list .job__card .job__apply .button__primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);
	}

	protected function render() {
		$error_msg = __( 'No Jobs Found', 'easyjobs' );
		if ( ! $this->get_token() && ! $this->is_editor ) {
			printf( "<div class='elej-error-msg'>%s</div>", "Whoops! It seems you didn't connect the EasyJobs Account. You can easily connect your account from <b>WordPress Dashboard > EasyJobs > Get Started > Sign In / Connect via API</b>" );
			
			return;
		}
		if ( ! $this->get_token() && $this->is_editor ) {
			printf( "<h3 class='elej-error-msg-editor'>%s</h3>", "Please Connect your EasyJobs Account" );
			
			return;
		}
		$settings = $this->get_settings_for_display();
		$company  = $this->get_company_info();
		if ( empty( $company ) ) {
			printf( "<h2 class='elej-error-msg'>%s</h2>", $error_msg );

			return;
		}
		$this->add_render_attribute( 'easyjobs-elementor', 'class', 'easyjobs-elementor' );

		// change cover image
		if ( $settings['easyjobs_cover_image_control'] === 'yes' && $settings['easyjobs_cover_image']['url'] !== '' ) {
			$company->cover_photo[0] = Group_Control_Image_Size::get_attachment_image_src(
                $settings['easyjobs_cover_image']['id'],
                'easyjobs_cover_image',
                $settings
            );
		}

		// change logo
		if ( $settings['easyjobs_logo_control'] === 'yes' && $settings['easyjobs_logo']['url'] !== '' ) {
			$company->logo = Group_Control_Image_Size::get_attachment_image_src(
                $settings['easyjobs_logo']['id'],
                'easyjobs_logo',
                $settings
            );
		}

		$this->company_info = $company;
		
		$company->ejel_enabled = 1;
		$company->ejel_job_list_enabled = 1;
		$company->selected_template = isset( $company->selected_template ) ? $company->selected_template : 'default';
		$company->ejel_hide_company_details = isset( $settings['easyjobs_company_details_control'] ) ? $settings['easyjobs_company_details_control'] : '';
		$company->ejel_hide_job_list = isset( $settings['easyjobs_job_list_control'] ) ? $settings['easyjobs_job_list_control'] : '';
		$company->ejel_hide_company_gallery = isset( $settings['easyjobs_company_gallery_control'] ) ? $settings['easyjobs_company_gallery_control'] : '';
		
		$company->name = ( empty( $company_name = trim( $settings['easyjobs_company_name'] ) ) && ! empty( $company->name ) ) ? $company->name : esc_html( $company_name );
		$company->ejel_website_link_text = ! empty( $website_link_text = trim( $settings['easyjobs_website_link_text'] ) ) ? esc_html( $website_link_text ) : '';		
		$company->ejel_joblist_heading = ! empty( $joblist_heading = trim( $settings['easyjobs_joblist_heading'] ) ) ? esc_html( $joblist_heading ) : '';
		$company->ejel_apply_button_text = ! empty( $apply_button_text = trim( $settings['easyjobs_joblist_apply_button_text'] ) ) ? esc_html( $apply_button_text ) : '';
		$company->ejel_galelry_section_title = ! empty( $galelry_section_title = trim( $settings['easyjobs_gallery_section_text'] ) ) ? esc_html( $galelry_section_title ) : '';
		
		$content = "<div {$this->get_render_attribute_string('easyjobs-elementor')}>";
		ob_start();
		include Easyjobs_Helper::get_path_by_template( esc_html( $company->selected_template ) , 'landing');
		$content .= ob_get_clean();
		$content .= '</div>';
		echo $content;

		if( $this->is_editor && 'classic' === $company->selected_template ){
			?>
			<script>
				jQuery(document).ready(function($) { 
					$('.office__gallery__slider').owlCarousel({
						center: true,
						loop:true,
						margin:30,
						nav:false,
						dots: false,
						responsive:{
							0:{
								items:1
							},
							575:{
								items:3
							},
							992:{
								items:4
							}
						}
					});
				});
			</script>
			<?php
		}
	}
}
