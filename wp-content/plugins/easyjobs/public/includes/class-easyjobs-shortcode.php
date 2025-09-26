<?php

use RankMath\Helper;

/**
 * Class Easyjobs_Shortcode
 * Handles all public shortcodes for Easyjobs
 *
 * @since 1.0.0
 * @package easyjobs
 */

class Easyjobs_Shortcode {

    private static $printed = [
        'landing' => false,
        'list' => false,
    ];

	/**
	 * Easyjobs_Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'easyjobs', array( $this, 'render_easyjobs_shortcode' ) );
		add_shortcode( 'easyjobs_list', array( $this, 'render_easyjobs_list_shortcode' ) );
		add_shortcode( 'easyjobs_details', array( $this, 'render_easyjobs_details_shortcode' ) );
	}

	/**
	 * Render content for shortcode 'easyjobs'
	 *
	 * @since 1.0.0
	 * @return false|string
	 */
	public function render_easyjobs_shortcode() {
        if(self::$printed['landing']){
            return;
        }
        self::$printed['landing'] = true;
		$company = $this->get_company_info();

		ob_start();
		include Easyjobs_Helper::get_path_by_template($company->selected_template, 'landing');
		return ob_get_clean();
	}
	/**
	 * Render content for shortcode 'easyjobs_details'
	 *
	 * @since 1.0.0
	 * @return false|string
	 */
	public function render_easyjobs_details_shortcode( $atts ) {
		if ( empty( $atts['id'] ) ) {
			return '';
		}
		$company = $this->get_company_info();
		if ( ! empty( $company->company_analytics ) && ! empty( $company->company_analytics->id ) ) {
			$this->insert_analytics_script( $company->company_analytics );
		}
        $landing_page_link = get_the_permalink(get_option('easyjobs_parent_page'));
		$job = Easyjobs_Helper::get_job( $atts['id'] );
		ob_start();
        include Easyjobs_Helper::get_path_by_template($company->selected_template, 'details');
		return ob_get_clean();
	}

	/**
	 * Render content for shortcode 'easyjobs_list'
	 *
	 * @since 1.0.0
	 * @return false|string
	 */
	public function render_easyjobs_list_shortcode($atts) {
		if(self::$printed['list']){
			return;
		}
		self::$printed['list'] = true;
		if ( ! Easyjobs_Helper::is_api_connected() ) {
			return esc_html__( 'Api is not connected', 'easyjobs' );
		}
		$company = Easyjobs_Helper::get_company_info(true);
		if ( ! empty( $company->company_analytics ) && ! empty( $company->company_analytics->id ) ) {
			$this->insert_analytics_script( $company->company_analytics );
		}
		$ej_is_search						= false;
		$sanitized_get_data 		= $this->sanitize_get_data();
		$job_page 							= isset($sanitized_get_data['job_page']) ? $sanitized_get_data['job_page'] : 1;
		
		if ( isset( $sanitized_get_data ) && ( ! empty( $sanitized_get_data['job_title'] ) || ! empty( $sanitized_get_data['job_category'] ) || ! empty( $sanitized_get_data['job_location'] ) ) ) {
			$ej_is_search 			  = true;
		}

		$ej_all_datas             = $this->get_published_jobs($job_page, $ej_is_search, $sanitized_get_data);
		if(!empty($ej_all_datas)) {
			$jobs_data								= $ej_all_datas->jobs;
			$ej_categories						= (array) $ej_all_datas->categories;
			$ej_locations							= (array) $ej_all_datas->locations;
		}
		$paginate_data 						= [];
		if (isset($jobs_data) && !empty($jobs_data)) {
			if (isset($jobs_data->last_page) && $jobs_data->last_page > 1) {
				$permalink 						= get_the_permalink();
				$prev_page_num 				= ( $jobs_data->current_page ) == 1 ? 1 : ( $job_page - 1 );
				$next_page_num 				= ( $jobs_data->current_page ) == $jobs_data->last_page ? $jobs_data->last_page : ( $job_page + 1 );
				$prev_page_url				= $permalink . "?" . Easyjobs_Helper::get_pagination_url($sanitized_get_data, $prev_page_num);
				$next_page_url				= $permalink . "?" . Easyjobs_Helper::get_pagination_url($sanitized_get_data, $next_page_num);
				$paginate_data = Easyjobs_Helper::paginate(["current" => $jobs_data->current_page, "max" => $jobs_data->last_page]);
			}

			$jobs 							= isset($jobs_data->last_page) ? $jobs_data->data : $ej_all_datas->jobs;
			$jobs								= (array) $jobs;
			$job_with_page_id     = Easyjobs_Helper::sync_job_pages( $jobs );
			usort($jobs, function ($a, $b) {
				return $b->is_pinned - $a->is_pinned;
			});
		}
		
		ob_start();
		include Easyjobs_Helper::get_path_by_template($company->selected_template,'list');
		return ob_get_clean();
	}

	private function get_pagination_url($query_params, $page = null){
		if(!empty($page)){
			$query_params['job_page'] = $page;
		}
		return http_build_query($query_params);
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
			if(isset($_GET[$field])){
				if($field == 'job_page'){
					$sanitized[$field] = absint($_GET[$field]);
				}else{
					$sanitized[$field] = sanitize_text_field($_GET[$field]);
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Get published job from api
	 *
	 * @since 1.0.0
	 * @return object|false
	 */
	private function get_published_jobs($page = 1, $is_search = false, $search_params = array()) {
		$published_jobs_param 	= [ 'paginate' => true, 'status' => 'active', 'page' => $page ];
		if ( $is_search ) {
			$published_jobs_param = array_merge($published_jobs_param, [
				'title' => isset($search_params['job_title']) ? $search_params['job_title'] : '',
				'category' => isset($search_params['job_category']) ? $search_params['job_category'] : '',
				'location' => isset($search_params['job_location']) ? $search_params['job_location'] : '', 
			]);
		}
		$jobs = Easyjobs_Api::get('published_jobs', $published_jobs_param);
		if ( isset( $jobs->status ) && $jobs->status === 'success' ) {
			return $jobs->data;
		}
		return false;
	}

	/**
	 * Get company info from api
	 *
	 * @since 1.0.0
	 * @return object|bool
	 */
	private function get_company_info() {
		$company_info = Easyjobs_Helper::get_company_details( true );
		if ( ! empty( $company_info ) ) {
			return $company_info;
		}
		return false;
	}

    /**
     * Insert analytics script
     * @param object $analytics - analytics info of company
     * @return void
     */

	private function insert_analytics_script( $analytics ) {
		add_action(
			'wp_footer',
			function() use ( $analytics ) {
				?>
			<!-- Matomo -->
			<script type="text/javascript">
				var _paq = window._paq = window._paq || [];
				/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
				_paq.push(["setDomains", <?php echo wp_json_encode( wp_unslash( $analytics->urls ) ); ?>]);
				_paq.push(['trackPageView']);
				_paq.push(['enableLinkTracking']);
				(function() {
					var u="<?php echo esc_url( EASYJOBS_ANALYTICS_URL ); ?>";
					_paq.push(['setTrackerUrl', u+'matomo.php']);
					_paq.push(['setSiteId', <?php echo esc_attr( $analytics->id ); ?>]);
					var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
					g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
				})();
			</script>
			<noscript><p><img src="//matomo.easyjobs.dev/matomo.php?idsite=<?php echo esc_attr( $analytics->id ); ?>&amp;rec=1" style="border:0;" alt="" /></p></noscript>
			<!-- End Matomo Code -->
				<?php
			}
		);
	}
}
