<?php
/**
 * This class is responsible for all job functionality in admin area
 *
 * @since 1.1.0
 */
class Easyjobs_Admin_Dashboard {

	public function __construct()
	{
		add_action('wp_ajax_easyjobs_company_stats', array($this, 'company_stats'));
		add_action('wp_ajax_easyjobs_recent_applicants', array($this, 'recent_applicants'));
		add_action('wp_ajax_easyjobs_recent_jobs', array($this, 'recent_jobs'));
		add_action('wp_ajax_easyjobs_analytics_info', array($this, 'get_analytics_info'));
		add_action('wp_ajax_easyjobs_job_analytics_stats', array($this, 'get_analytics_stats'));
		add_action('wp_ajax_easyjobs_get_active_jobs', array($this, 'active_jobs'));

	}

	public function company_stats(){
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }

        if ( ! Easyjobs_Helper::verified_request( $_POST ) ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'error_type' => 'invalid_nonce',
					'message'    => 'Bad request !!',
				)
			);
			wp_die();
        }
		$stats = $this->get_company_stats();
		if($stats){
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $stats,
				)
			);
		}else{
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Unable to get company statistics' ) );
		}
		wp_die();
	}

	public function recent_applicants()
	{
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Bad request' ) );
			wp_die();
		}
		$stats = $this->get_recent_applicants();
		if($stats){
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $stats,
				)
			);
		}else{
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Unable to get recent applicants' ) );
		}
		wp_die();
	}

	public function active_jobs()
	{
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Bad request' ) );
			wp_die();
		}
		$jobs = $this->get_active_jobs();
		if($jobs){
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $jobs,
				)
			);
		}else{
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Unable to get active jobs' ) );
		}
		wp_die();
	}

	public function recent_jobs()
	{
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Bad request' ) );
			wp_die();
		}

		$jobs = $this->get_recent_jobs(isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1);
		$job_with_page_id       = Easyjobs_Helper::sync_job_pages($jobs->data);
		foreach ($jobs->data as $job){
			$job->view_url = esc_url(get_the_permalink($job_with_page_id[$job->id]));
		}
		if($jobs){
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $jobs,
				)
			);
		}else{
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Unable to get recent applicants' ) );
		}
		wp_die();
	}

	/**
     * Show dashboard
     *
     * @since 1.1.0
	 * @param int $recent_jobs_page
     * @return void
     */

    public function show_dashboard( $recent_jobs_page = 1 ) {

        $company_stats     = $this->get_company_stats();
        $recent_applicants = $this->get_recent_applicants();
        $recent_jobs       = $this->get_recent_jobs( $recent_jobs_page );
        $total_page        = 1;
        $job_page_ids      = array();
        $ai_enabled        = Easyjobs_Helper::is_ai_enabled();
        if ( ! empty( $recent_jobs->data ) ) {
            $total_page           = (int) ceil( $recent_jobs->total / $recent_jobs->per_page );
            $current_page         = (int) $recent_jobs->current_page;
            $paginate_data        = Easyjobs_Helper::paginate(["current" => $current_page, "max" => $total_page]);
            $pages_to_show        = $paginate_data['items'];
            $length               = count($pages_to_show);
            $job_with_page_id     = Easyjobs_Helper::get_job_with_page( $recent_jobs->data );
            $new_job_with_page_id = Easyjobs_Helper::create_pages_if_required( $recent_jobs->data, $job_with_page_id );
            $job_page_ids         = $job_with_page_id + $new_job_with_page_id;
        }
        
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-dashboard-display.php';
    }

    /**
     * Get company statistics
     *
     * @since 1.1.0
     * @return object|bool
     */
    public function get_company_stats() {
		$response = Easyjobs_Api::get( 'company_stats' );
		Easyjobs_Helper::check_reload_required( $response );
        if ( ! empty( $response ) && $response->status == 'success' ) {
            return $response->data;
        }

        return false;
    }
    /**
     * Get company recent applicants
     *
     * @since 1.1.0
     * @return object|bool
     */
    public function get_recent_applicants() {
        $response = Easyjobs_Api::get( 'company_recent_applicants' );
		Easyjobs_Helper::check_reload_required( $response );
		if ( ! empty( $response ) && $response->status == 'success' ) {
			return $response->data;
		}

        return false;
    }

	/**
     * Get company active jobs
     *
     * @since 1.1.0
     * @return object|bool
     */
    public function get_active_jobs() {
		$response = Easyjobs_Api::get( 'company_active_jobs' );
		Easyjobs_Helper::check_reload_required( $response );
		if ( ! empty( $response ) && $response->status == 'success' ) {
			return $response->data;
		}

	   return false;
   	}

	/**
	 * Get company recent jobs
	 *
	 * @param $page
	 * @return object|bool
	 * @since 1.1.0
	 */
    public function get_recent_jobs( $page ) {
        $response = Easyjobs_Api::get( 'company_recent_jobs', array_merge( ['page' => $page], ['rows' => 1] ) );
		Easyjobs_Helper::check_reload_required( $response );
        if ( ! empty( $response ) && $response->status == 'success' ) {
            return $response->data;
        }

        return false;
    }

	/**
	* Get analytics info
	*
	* @param $params
	* @return object|bool
	* @since 2.1.2
	*/
	public function get_analytics_info() {
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
			wp_die();
		}
		$params = ['period', 'noOfDays'];
		$post_params = json_decode( wp_unslash( $_POST['params'] ), true );
		$args = [];
		foreach ($params as $param){
			if(isset($post_params[$param])){
				$args[$param] = sanitize_text_field($post_params[$param]);
			}
		}
		$analytics = Easyjobs_Api::get( 'analytics_info', $args );
		Easyjobs_Helper::check_reload_required( $analytics );
		if ( Easyjobs_Helper::is_success_response( $analytics->status ) ) {
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $analytics->data,
				)
			);
		} else {
			echo wp_json_encode(
				array(
					'status' => 'error',
					'message'=> $analytics->message,
				)
			);
		}
		wp_die();
	}

	/**
	* Get analytics stats of jobs
	*
	* @param 
	* @return object|bool
	* @since 2.1.2
	*/
	public function get_analytics_stats() {
		if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
			wp_die();
		}
		
		$response = Easyjobs_Api::get( 'job_analytics_stats' );
		if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
			echo wp_json_encode(
				array(
					'status' => 'success',
					'data'   => $response->data,
				)
			);
		} else {
			echo wp_json_encode(
				array(
					'status' => 'error',
					'message'=> $response->message,
				)
			);
		}
		wp_die();
	}
}
