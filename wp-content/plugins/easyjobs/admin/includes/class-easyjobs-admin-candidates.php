<?php
/**
 * Class Easyjobs_Admin_Candidates
 * Handles all functionality for candidates in admin area
 *
 * @since 1.0.0
 */
class Easyjobs_Admin_Candidates {

    /**
     * Easyjobs_Admin_Candidates constructor.
     */
    public function __construct() {
		add_action( 'wp_ajax_easyjobs_search_filter_candidates', array( $this, 'search_filter_candidates' ) );
        add_action( 'wp_ajax_easyjobs_search_filter_all_candidates', array( $this, 'search_filter_all_candidates' ) );
        add_action( 'wp_ajax_easyjobs_export_job_candidates', array( $this, 'export_job_candidates' ) );
        add_action( 'wp_ajax_easyjobs_get_invited_candidates', array( $this, 'get_invited_candidates' ) );
        add_action( 'wp_ajax_easyjobs_save_candidate_note', array( $this, 'save_candidate_note' ) );
        add_action( 'wp_ajax_easyjobs_delete_candidate_note', array( $this, 'delete_candidate_note' ) );
        add_action( 'wp_ajax_easyjobs_delete_candidate', array( $this, 'delete_candidate' ) );
        add_action( 'wp_ajax_easyjobs_get_pending_candidates', array( $this, 'get_pending_candidates' ) );
        add_action( 'wp_ajax_easyjobs_delete_pending_candidate', array( $this, 'delete_pending_candidate' ) );
        add_action( 'wp_ajax_easyjobs_get_candidates', array( $this, 'get_candidates' ) );
        add_action( 'wp_ajax_easyjobs_get_company_jobs', array( $this, 'get_company_jobs' ) );
        add_action( 'wp_ajax_easyjobs_candidate_details', array( $this, 'show_details' ) );
        add_action( 'wp_ajax_easyjobs_get_job_candidates', array( $this, 'get_job_candidates' ) );
        add_action( 'wp_ajax_easyjobs_get_candidates_id', array( $this, 'get_ids' ) );
    }

    /**
     * Show all candidates
     *
     * @since 1.0.0
     * @param int $job_id
     * @return void
     */
    public function show_job_candidates( $job_id ) {
        $data       = $this->get_job_candidates_data( $job_id );
        if($data){
			$candidates = $data->candidates;
			$pipelines  = $data->job->pipeline;
		}
        $job        = Easyjobs_Helper::get_job( $job_id );
        $ai_enabled = Easyjobs_Helper::is_ai_enabled();
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-candidates-display.php';
    }
    public function get_job_candidates_data( $job_id ) {
        $candidates = Easyjobs_Api::get_by_id( 'job', $job_id, 'candidates' );
		Easyjobs_Helper::check_reload_required( $candidates );
        if ( $candidates && $candidates->status == 'success' ) {
            return $candidates->data;
        }
        return false;
    }
    /**
     * Get job candidates
     *
     * @since 1.0.0
     * @param int $job_id
     * @return object | bool
     */
    public function get_job_candidates() {
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
        $job_id = isset($_POST['job_id']) ? $_POST['job_id'] : 4;
        $candidates = $this->get_job_candidates_data($job_id);

        if($candidates){
			echo wp_json_encode(Easyjobs_Helper::get_success_response('success', $candidates));
		}else{
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Unable to get candidates'));
		}
		wp_die();
    }

    /**
     * Ajax callback for 'easyjobs_search_filter_candidates'
     * Handles search and filter candidates
     *
     * @since 1.0.0
     * @return void
     */
    public function search_filter_candidates() {
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

		if ( ! isset( $_POST['job_id'] ) || ! isset( $_POST['parameters'] ) ) {
            return;
		};
        $post = $this->build_search_keyword( json_decode( wp_unslash( $_POST['parameters'] ) ));
        echo wp_json_encode(
            $this->get_results(
                sanitize_text_field( wp_unslash( $_POST['job_id'] ) ),
                $post
            )
        );
        wp_die();
    }

    /**
	 * Show job details
     *
     * @param int $id
	 * @return void
     */
    public function show_details() {
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
		if(!isset($_POST['id'])){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Candidate id not provided'));
			wp_die();
		}
        $data = $this->get_details( sanitize_text_field($_POST['id']) );
		if(!empty($data)){
			$data->global_ai_enabled = Easyjobs_Helper::is_ai_enabled();
			$data->notes = $this->get_notes( sanitize_text_field($_POST['id']) );
			echo wp_json_encode(Easyjobs_Helper::get_success_response('success', $data));
		}else{
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Unable to get candidate details'));
		}
        wp_die();

    }

    /**
     * Show all candidates
     *
     * @param array $parameters
     * @return void
     */
    public function show_all_candidates( $parameters ) {
        $candidates   = array();
        $total_page   = 1;
        $current_page = 1;

        $jobs                = $this->get_company_jobs();
        $ai_enabled          = Easyjobs_Helper::is_ai_enabled();
        $candidates_response = $this->get_company_candidates( $parameters );

        if ( ! empty( $candidates_response->data ) ) {
            $candidates     = $candidates_response->data;
            $total_page     = (int) ceil( $candidates_response->total / $candidates_response->per_page );
            $current_page   = (int) $candidates_response->current_page;
            $paginate_data  = Easyjobs_Helper::paginate(["current" => $current_page, "max" => $total_page]);
            $pages_to_show  = $paginate_data['items'];
            $length         = count($pages_to_show);
        }

        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-all-candidates.php';
    }

    /**
     * Search and filter candidates
     *
	 * @return void
	 */
    public function search_filter_all_candidates() {
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
		$parameters = array();
        if ( isset( $_POST['parameters'] ) ) {
            foreach ( $_POST['parameters'] as $key => $value ) {
                $parameters[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
            }
        };
        echo wp_json_encode( Easyjobs_Api::get( 'company_candidates', $parameters ) );
        wp_die();
    }

    /**
     * ajax callback for export candidates
     *
     * @since 1.3.1
     */
    public function export_job_candidates() {
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

		if ( ! isset( $_POST['job_id'] ) || empty( $_POST['job_id'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job id not provided' ) );
			wp_die();
		}

        echo wp_json_encode( Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::search_within_job(
                    abs( sanitize_text_field( $_POST['job_id'] ) ),
                    '',
                    $this->build_search_keyword( $_POST['keywords'] ),
                    EASYJOBS_APP_URL . '/api/v1/job/' . abs( sanitize_text_field( $_POST['job_id'] ) ) . '/candidates/export'
                )
            )
        );

        wp_die();
    }

	/**
	 *
	 */
	public function get_invited_candidates() {
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
		if ( ! isset( $_POST['job_id'] ) || empty( $_POST['job_id'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job id not provided' ) );
			wp_die();
		}
		$response = Easyjobs_Api::get_by_id(
			'job',
			abs( sanitize_text_field( $_POST['job_id'] ) ),
			'invitations'
		);
		Easyjobs_Helper::check_reload_required( $response );
        echo wp_json_encode( Easyjobs_Helper::get_generic_response(
                $response
            )
        );
        wp_die();
    }

	/**
	 * Ajax callback for save candidate note
     *
	 * @return void
	 * @since 1.3.7
	 */
	public function save_candidate_note() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['candidate_id'] ) || empty( $_POST['candidate_id'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Candidate id not provided' ) );
			wp_die();
		}
		$data = array();

        $form_data = json_decode(wp_unslash($_POST['form_data']), true);
		foreach ( $form_data as $d ) {
			if ( $d['name'] == 'note' ) {
				if ( empty( $d['value'] ) ) {
					echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Please write some note' ) );
					wp_die();
				}
				$data['note'] = sanitize_text_field( $d['value'] );
			}
			if ( $d['name'] == 'tag_select' ) {
				$data['tags'][] = $d['value'];
			}
		}

        if(!empty($data['tags'])){		
            $data['tags'] = wp_json_encode($data['tags']);
        }
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post(
                    'save_candidate_note',
                    abs( sanitize_text_field( $_POST['candidate_id'] ) ),
                    $data
                )
            )
        );
		wp_die();

    }
	/**
	 * Ajax callback for delete candidate note
     *
	 * @return void
	 * @since 1.3.7
	 */
	public function delete_candidate_note() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['candidate_id'] ) || empty( $_POST['candidate_id'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Candidate id not provided' ) );
			wp_die();
		}
		if ( ! isset( $_POST['note_id'] ) || empty( $_POST['note_id'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Note id not provided' ) );
			wp_die();
		}
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post_custom( EASYJOBS_API_URL . 'job/applicants/' . abs( $_POST['candidate_id'] ) . '/note/' . abs( $_POST['note_id'] ) . '/delete' )
            )
        );
		wp_die();
    }


	public function delete_candidate() {
        if ( ! Easyjobs_Helper::verified_request($_POST) || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if ( ! isset( $_POST['candidates'] ) || empty( $_POST['candidates'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Candidates not provided' ) );
			wp_die();
		}
		if ( ! isset( $_POST['job'] ) || empty( $_POST['job'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job not provided' ) );
			wp_die();
		}
		$candidates = array();
		foreach (json_decode( $_POST['candidates'] ) as $data ) {
			$candidates[] = sanitize_text_field( $data );
		}
		$response = Easyjobs_Api::post(
            'delete_candidate',
            abs( sanitize_text_field( $_POST['job'] ) ),
            array(
				'candidates' => $candidates,
			)
        );
		if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
			echo wp_json_encode(
                array(
					'status'  => 'success',
					'message' => __( 'Candidate deleted successfully', 'easyjobs' ),
                )
            );
		} else {
			echo wp_json_encode(
                array(
					'status'  => 'error',
					'message' => ! empty( $response->data->message ) ? $response->data->message : __( 'Unable to delete candidate', 'easyjobs' ),
                )
            );
		}
		wp_die();
    }

	/**
	 * Ajax callback for getting pending candidates
	 * @return void
	 * @since 1.5.0
	 */
	public function get_pending_candidates() {
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

		if ( ! isset( $_POST['job_id'] ) || empty( $_POST['job_id'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job id not provided' ) );
			wp_die();
		}
		$response = Easyjobs_Api::get_by_id(
			'job',
			abs( sanitize_text_field( $_POST['job_id'] ) ),
			'candidate/pending'
		);
		Easyjobs_Helper::check_reload_required( $response );
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(
			$response
		)
		);
		wp_die();
	}

	/**
	 * Ajax callback for delete pending candidate
	 * @return void
	 * @since 1.5.0
	 */
	public function delete_pending_candidate() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if (empty( $_POST['job_id'] )) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job not provided' ) );
			wp_die();
		}
		if (empty( $_POST['candidate'] )) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Candidate not provided' ) );
			wp_die();
		}
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(
			Easyjobs_Api::post(
				'delete_pending_candidate',
				abs( sanitize_text_field( $_POST['job_id'] ) ),
				array('candidates' => [sanitize_text_field($_POST['candidate'])])
			)
		)
		);
		wp_die();
	}

	/**
	 * ajax callback for get all company jobs
	 * @since 2.0.0
	 * @return void
	 */
	public function get_candidates(){
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
		$params = [
			'job_id',
			'page',
			'rating',
			'pipeline',
			'candidate_name'
		];
		$args = [];
		foreach ($params as $param){
			if(isset($_POST[$param])){
				$args[$param] = sanitize_text_field($_POST[$param]);
			}
		}
		$candidates = $this->get_company_candidates( $args );
		if($candidates){
			echo wp_json_encode(Easyjobs_Helper::get_success_response('success', $candidates));
		}else{
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Unable to get candidates'));
		}
		wp_die();
	}

	/**
	 * Get company jobs
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function get_company_jobs() {
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
		$results = Easyjobs_Api::get( 'company_jobs' );
		Easyjobs_Helper::check_reload_required( $results );
		if ( $results && $results->status == 'success' ) {
			echo wp_json_encode(Easyjobs_Helper::get_success_response('success', $results->data));
		}else{
			echo wp_json_encode(Easyjobs_Helper::get_error_response('unable to get company jobs'));
		}
		wp_die();
	}

	/******* private methods *********/
    /**
     * @param $id
     * @return mixed
     */
    private function get_details( $id ) {
        $candidate_details = Easyjobs_Api::get_by_id( 'candidate', $id );
		Easyjobs_Helper::check_reload_required( $candidate_details );
        if ( $candidate_details == null ) {
            return false;
        }
        if ( $candidate_details->status == 'success' ) {
            return $candidate_details->data;
        }
        return false;
    }

    /**
     * Get search and filtered candidates from api
     *
     * @since 1.0.0
     * @param int    $job_id
     * @param string $keywords
     * @return bool|object
     */
    private function get_results( $job_id, $keywords ) {
        $results = Easyjobs_Api::search_within_job( $job_id, 'job_candidates', $keywords );
		Easyjobs_Helper::check_reload_required( $results );
        if ( $results && $results->status == 'success' ) {
            return (object) array(
                'status'     => 'success',
                'candidates' => $results->data->candidates,
            );
        }
        return false;
    }

    /**
     * Get company candidates
     *
     * @param array $parameters
     * @return object|bool
     * @since 2.0.0
     */

    private function get_company_candidates( array $parameters ) {
        $results = Easyjobs_Api::get( 'company_candidates', $parameters );
		Easyjobs_Helper::check_reload_required( $results );
        if ( $results && $results->status == 'success' ) {

            return $results->data;
        }
        return false;
    }

    private function build_search_keyword( $parameters ) {
        $keywords_arr = array();
        foreach ( $parameters as $k => $val ) {
            if ( $k == 'filter' ) {
                foreach ( $val as $v ) {
                    $keywords_arr[] = 'basic[]=' . sanitize_text_field($v);
                }
            } else {
                $value = sanitize_text_field( $val );
                $key   = sanitize_text_field( $k );
                if ( ! empty( $value ) || $value == 0 ) {
                    if ( $key == 'search' ) {
                        $keywords_arr[] = $key . '=' . rawurlencode( $value );
                    } else {
                        $keywords_arr[] = $key . '=' . $value;
                    }
                }
            }
        }
        return implode( '&', $keywords_arr );
    }

	private function get_notes( $candidate_id ) {
		$notes = Easyjobs_Api::get_by_id( 'candidate_note', $candidate_id, 'note' );
		if ( $notes == null ) {
			return null;
		}
		if ( Easyjobs_Helper::is_success_response( $notes->status ) ) {
			return $notes->data;
		}
		return null;
    }

    /**
	 * Ajax callback for getting candidates ID
	 * @return void
	 * @since 1.5.0
	 */
	public function get_ids() {
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
		if ( ! isset( $_POST['id'] ) || empty( $_POST['id'] ) ) {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Job id not provided' ) );
			wp_die();
		}
		$response = Easyjobs_Api::get_by_id(
			'candidate_ids',
			absint( sanitize_text_field( $_POST['id'] ) )
		);
		Easyjobs_Helper::check_reload_required( $response );
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(
			$response
		)
		);
		wp_die();
	}

}