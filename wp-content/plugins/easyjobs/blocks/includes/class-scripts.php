<?php

/**
 * Enqueue styles & scripts
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  	exit;
}

class EasyjobsBlocksScripts {

	public function __construct() {
		add_filter( 'block_categories_all', [ $this, 'easyjobs_reg_block_cat' ], 10, 2 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
		add_action( 'init', [ $this, 'render_frontend_for_job_blocks' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'easyjobs_enqueue_fontawesome' ] );
	}

	public function easyjobs_enqueue_fontawesome() {
		wp_enqueue_style( 'fontawesome', EASYJOBS_PUBLIC_URL . 'assets/vendor/all.min.css' );
	}

	public function block_editor_assets() {
		$modules_dep_path 	= EASYJOBS_ROOT_DIR_PATH . 'blocks/dist/modules.asset.php';
		$index_dep_path 	= EASYJOBS_ROOT_DIR_PATH . 'blocks/dist/index.asset.php';
		$dependencies 		= ['regenerator-runtime'];

		//Enqueue Modules
		if (file_exists($modules_dep_path))
		{
			$_dependencies = require $modules_dep_path;
			$dependencies = array_merge($dependencies, $_dependencies['dependencies']);
		}
		wp_register_script(
			'ej-blocks-modules', 
			EASYJOBS_URL . 'blocks/dist/modules.js', 
			$dependencies, 
			( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION
		);
		wp_register_style(
			'ej-blocks-style-modules-css', 
			EASYJOBS_URL . 'blocks/dist/style-modules.css', 
			[], 
			( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION
		);
		wp_register_style( 'fontawesome-ej', EASYJOBS_PUBLIC_URL . 'assets/vendor/all.min.css', array(), ( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION );
		wp_register_style(
			'ej-blocks-modules-css', 
			EASYJOBS_URL . 'blocks/dist/modules.css', 
			['ej-blocks-style-modules-css', 'dashicons', 'fontawesome-ej'], 
			( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION
		);

		//Enqueue Blocks Build File for editor
		if (file_exists($index_dep_path))
		{
			$dependencies = require $index_dep_path;
			$dependencies = $dependencies['dependencies'];
		}

		global $pagenow;
        $editor_type = false;
        if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
            $editor_type = 'edit-post';
        } elseif ( $pagenow == 'site-editor.php' || ( $pagenow == 'themes.php' && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'gutenberg-edit-site' ) ) {
            $editor_type = 'edit-site';
        } elseif ( $pagenow == 'widgets.php' ) {
            $editor_type = 'edit-widgets';
        }

		wp_enqueue_style( 'easyjobs-owl-blocks', EASYJOBS_PUBLIC_URL . 'assets/vendor/owl.carousel.min.css', array(), ( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION, 'all' );

		wp_enqueue_script( 'easyjobs-owl-blocks', EASYJOBS_PUBLIC_URL . 'assets/vendor/owl.carousel.min.js', array( 'jquery' ), ( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION, true );
        wp_enqueue_script( 'easyjobs-js', EASYJOBS_PUBLIC_URL . 'assets/dist/js/easyjobs-public.min.js', array( 'jquery', 'easyjobs-owl-blocks' ), ( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION, true );


		$localize_array = [
            'rest_rootURL'               => get_rest_url(),
            'ajax_url'                   => admin_url( 'admin-ajax.php' ),
			'image_url'					 => EASYJOBS_URL . 'public/assets/img',
			'ej_admin_url'				 => get_admin_url(),
            'responsiveBreakpoints'      => [
				'tablet' => 1024,
				'mobile' => 767
			]
        ];

		wp_localize_script('easyjobs-js', 'EasyJobsLocalize', $localize_array);
		wp_localize_script('easyjobs-js', 'eb_conditional_localize',
            $editor_type !== false ? [
                'editor_type' => $editor_type
            ] : []
		);
		
		wp_enqueue_script(
			'ej-blocks-editor', 
			EASYJOBS_URL . 'blocks/dist/index.js', 
			array_merge($dependencies, [
				'ej-blocks-modules',
				'easyjobs-js'
			]), 
			( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION
		);
		wp_localize_script(
			'ej-blocks-editor', 
			'EasyJobsBlocksJs', 
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'blocks_nonce'    => wp_create_nonce('easyjobs_blocks_nonce')
			]
		);
		wp_enqueue_style(
			'ej-editor-style', 
			EASYJOBS_PUBLIC_URL . 'assets/dist/css/easyjobs-public.min.css', 
			array('ej-blocks-modules-css'), 
			( defined( 'EASYJOBS_DEV' ) && ( EASYJOBS_DEV == true ) )  ? time() : EASYJOBS_VERSION
		);
	}

	private function get_token() {
		$settings = EasyJobs_DB::get_settings();

		return ! empty( $settings['easyjobs_api_key'] ) ? $settings['easyjobs_api_key'] : false;
	}

	public function render_frontend_for_job_blocks() {
		register_block_type( EASYJOBS_ROOT_DIR_PATH . 'blocks/blocks/job-list/block.json', [
			'render_callback' => [$this, 'render_list_frontend' ]
		] );

		register_block_type( EASYJOBS_ROOT_DIR_PATH . 'blocks/blocks/job-header/block.json', [
			'render_callback' => [$this, 'render_info_frontend' ]
		] );

		register_block_type( EASYJOBS_ROOT_DIR_PATH . 'blocks/blocks/job-footer/block.json', [
			'render_callback' => [$this, 'render_footer_frontend' ]
		] );
	}

	private $api_notice_shown = false;

	public function render_footer_frontend($atts, $con) {
		if ( ! $this->get_token() ) {
			ob_start();
			if(!$this->api_notice_shown){
				$this->api_notice_shown = true;
				return Easyjobs_Helper::err_view();
			}
			return ob_get_clean();
		}

		$default_atts = [
			'lifeAtTitle' => 'Life At'
		];

		$atts = wp_parse_args( $atts, $default_atts );
		$company  			= Easyjobs_Helper::get_company_info(true);
		$company_details  	= Easyjobs_Helper::get_company_details(true);

		$company_details->cover_photo[0] = isset( $atts['coverImgUrl'] ) ? $atts['coverImgUrl'] : $company_details->cover_photo[0];
		$company_details->logo = isset( $atts['logoImgUrl'] ) ? $atts['logoImgUrl'] : $company_details->logo;

		ob_start();
		return Easyjobs_Helper::view( 
			'footer',
			$company->selected_template, 
			[
				'company' 				=> $company,
				'details' 				=> $company_details,
				'atts' 					=> $atts
			] 
		);
		return ob_get_clean();
	}

	public function render_info_frontend($atts, $con) {
		if ( ! $this->get_token() ) {
			ob_start();
			if(!$this->api_notice_shown){
				$this->api_notice_shown = true;
				return Easyjobs_Helper::err_view();
			}
			return ob_get_clean();
		}
		
		$default_atts = [
			
		];

		$atts = wp_parse_args($atts, $default_atts);
		$company  			= Easyjobs_Helper::get_company_info(true);
		$company_details  	= Easyjobs_Helper::get_company_details(true);

		$company_details->cover_photo[0] = isset( $atts['coverImgUrl'] ) ? $atts['coverImgUrl'] : $company_details->cover_photo[0];
		$company_details->logo = isset( $atts['logoImgUrl'] ) ? $atts['logoImgUrl'] : $company_details->logo;
		$company_details->name = isset( $atts['companyName'] ) ? $atts['companyName'] : $company_details->name;

		ob_start();
		return Easyjobs_Helper::view( 
			'info',
			$company->selected_template, 
			[
				'company' 				=> $company,
				'details' 				=> $company_details,
				'atts' 					=> $atts
			] 
		);
		return ob_get_clean();
	}

	public function render_list_frontend($atts, $con) {
		if ( ! $this->get_token() ) {
			ob_start();
			if(!$this->api_notice_shown){
				$this->api_notice_shown = true;
				return Easyjobs_Helper::err_view();
			}
			return ob_get_clean();
		}
		$default_atts = [
			'hideIcon' 			=> false,
			'icon' 				=> 'fas fa-briefcase',
			'hideTitle' 		=> false,
			'titleText' 		=> 'Open Job Positions', 
			'filterByTitle'		=> true,
			'filterByCategory'	=> true,
			'filterByLocation'	=> true,
			'showCompanyName'	=> true,
			'showLocation'		=> true,
			'showDateLine'		=> true,
			'showNoOfJob'		=> true,
			'applyBtnText' 		=> 'Apply',
			'activeOnly'		=> true,
			'noOfJobs' 			=> 2,
			'orderby'			=> 'title',
			'order' 			=> 'asc',
		];

		$atts = wp_parse_args($atts, $default_atts);

		$error_msg 							= __( 'No Jobs Found', 'easyjobs' );
		$company  							= Easyjobs_Helper::get_company_info(true);
		$company->joblist_heading_icon 		= ( ! $atts['hideIcon'] && ! $atts['hideTitle'] ) ? $atts['icon'] : '';
		$company->joblist_heading 			= ! $atts['hideTitle'] ? $atts['titleText'] : '';
		$company->apply_button_text 		= $atts['applyBtnText'];
		// $atts['filterByTitle']			= $company->show_location_filter ? $company->show_location_filter : false;
		// $atts['filterByCategory']			= $company->show_location_filter ? $company->show_location_filter : false;
		// $atts['filterByLocation']			= $company->show_location_filter ? $company->show_location_filter : false;
		if ( empty( $company ) ) {
			printf( "<h2 class='elej-error-msg'>%s</h2>", $error_msg );
			return;
		}
		$ej_is_search				= false;
		$sanitized_get_data 		= $this->sanitize_get_data();
		$job_page 					= isset($sanitized_get_data['job_page']) ? $sanitized_get_data['job_page'] : 1;
		
		if ( isset( $sanitized_get_data ) && ( ! empty( $sanitized_get_data['job_title'] ) || ! empty( $sanitized_get_data['job_category'] ) || ! empty( $sanitized_get_data['job_location'] ) ) ) {
			$ej_is_search 			  = true;
		}
		$fetch_params 					= $this->build_fetch_params($atts, $sanitized_get_data);
		$ej_all_datas     				= $this->get_published_jobs($fetch_params);
		$jobs_data						= $ej_all_datas->jobs;
		$ej_categories					= (array) $ej_all_datas->categories;
		$ej_locations					= (array) $ej_all_datas->locations;
		$permalink 						= get_the_permalink();
		$prev_page_url					= '';
		$next_page_url					= '';
		$paginate_data					= [];
		if (isset($jobs_data) && !empty($jobs_data)) {
			if (isset($jobs_data->last_page) && $jobs_data->last_page > 1) {
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
		
        ob_start();
		return Easyjobs_Helper::view( 
			'list',
			$company->selected_template, 
			[
				'company' 				=> $company,
				'atts' 					=> $atts,
				'ej_is_search' 			=> $ej_is_search,
				'ej_categories' 		=> $ej_categories,
				'ej_locations' 			=> $ej_locations,
				'permalink' 			=> $permalink,
				'prev_page_url' 		=> $prev_page_url,
				'next_page_url' 		=> $next_page_url,
				'jobs' 					=> $jobs,
				'job_with_page_id' 		=> $job_with_page_id,
				'jobs_data' 			=> $jobs_data,
				'sanitized_get_data' 	=> $sanitized_get_data,
				'paginate_data'			=> $paginate_data,
			] 
		);
		return ob_get_clean();
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
				'orderby' => 'title',
				'order' => 'desc',
				'paginate' => true,
			)
        );
		$job_info = Easyjobs_Api::get( 'published_jobs', $query_param );
		return $job_info->status == 'success' ? $job_info->data : array();
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

	private function build_fetch_params($settings, $search_params) {
		// dd($settings['noOfJob']);
		return array_merge(
			[
				'rows'   => isset($settings['noOfJob']) ? $settings['noOfJob'] : 2,
				'orderby' => isset($settings['orderBy']) ? $settings['orderBy'] : 'title',
				'order' => isset($settings['sortBy']) ? $settings['sortBy'] : 'asc',
				'status' => isset( $settings['activeOnly'] ) && $settings['activeOnly'] ? 'active' : '',
			], 
			[
				'title' => isset($search_params['job_title']) ? $search_params['job_title'] : '',
				'category' => isset($search_params['job_category']) ? $search_params['job_category'] : '',
				'location' => isset($search_params['job_location']) ? $search_params['job_location'] : '', 
				'page' => isset($search_params['job_page']) ? $search_params['job_page'] : 1
			]
		);
	}

	public function easyjobs_reg_block_cat( $block_categories, $editor_context ) {
		if ( ! empty( $editor_context->post ) ) {
			array_push(
				$block_categories,
				array(
					'slug'  => 'easyjobs',
					'title' => __( 'EasyJobs', 'easyjobs' ),
					'icon'  => null,
				)
			);
		}
		return $block_categories;
	}
}

new EasyjobsBlocksScripts();