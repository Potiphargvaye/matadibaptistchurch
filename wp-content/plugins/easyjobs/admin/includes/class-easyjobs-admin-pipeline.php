<?php
/**
 * This class is responsible for all job pipeline functionality
 *
 * @since 1.0.0
 */
class Easyjobs_Admin_Pipeline {
	/**
	 *  Easyjobs_Admin_Pipeline constructor
	 */
	public function __construct() {
        add_action( 'wp_ajax_easyjobs_change_pipeline', array( $this, 'change_pipeline' ) );
        add_action( 'wp_ajax_easyjobs_save_pipeline', array( $this, 'save_pipeline' ) );
        add_action( 'wp_ajax_easyjobs_get_pipeline', array( $this, 'get_pipeline' ) );
        add_action( 'wp_ajax_easyjobs_get_job_pipeline', array( $this, 'get_job_pipeline' ) );
        add_action( 'wp_ajax_easyjobs_get_pipeline_templates', array( $this, 'get_pipeline_template' ) );
        add_action( 'wp_ajax_easyjobs_get_evaluation_question', array( $this, 'get_evaluation_question' ) );
        add_action( 'wp_ajax_easyjobs_delete_evaluation_question', array( $this, 'delete_evaluation_question' ) );
        add_action( 'wp_ajax_easyjobs_save_question', array( $this, 'save_evaluation_question' ) );
        add_action( 'wp_ajax_easyjobs_get_question', array( $this, 'get_question_set' ) );
        add_action( 'wp_ajax_easyjobs_duplicate_question', array( $this, 'duplicate_question_set' ) );
        add_action( 'wp_ajax_easyjobs_save_assessment', array( $this, 'save_assessment' ) );
        add_action( 'wp_ajax_easyjobs_get_assessment', array( $this, 'get_assessment' ) );
    }

    /**
     * Show pipelines
     *
     * @since 1.0.0
     * @param  int $job_id
     * @return void
     */
    public function show( $job_id ) {
        $pipelines          = $this->get_pipelines( $job_id );
        $job                = Easyjobs_Helper::get_job( $job_id );
        $pipeline_templates = $this->get_pipeline_templates();
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-pipeline-display.php';
    }

    /**
     * Get pipelines
     *
     * @since 1.0.0
     * @param  int $job_id
     * @return object | bool
     */
    public function get_pipelines( $job_id ) {
        $pipelines = Easyjobs_Api::get_by_id( 'job', $job_id, 'pipeline' );
        Easyjobs_Helper::check_reload_required( $pipelines );
        if ( $pipelines && $pipelines->status == 'success' ) {
            return $pipelines->data;
        }
        return false;
    }

    /**
     * Ajax callback for 'easyjobs_save_pipeline' action
     * Save new pipeline stage in app through api
     *
     * @since 1.0.0
     * @return void
     */
    public function save_pipeline() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if ( ! isset( $_POST['form_data'] ) && ! isset( $_POST['job_id'] ) ) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Empty form data or job id',
                )
            );
            wp_die();
        }
        $data     = json_decode(wp_unslash($_POST['form_data']), true);
        $response = Easyjobs_Api::post( 'save_pipeline', absint( sanitize_text_field( $_POST['job_id'] ) ), $data );
        echo wp_json_encode( $response );
        wp_die();
    }

    /**
     * Ajax callback for 'easyjobs_change_pipeline' action
     * Handles candidates pipeline stage change
     *
     * @since 1.0.0
     * @return void
     */
    public function change_pipeline() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
		Easyjobs_Helper::check_verified_request();
		if(!isset( $_POST['pipeline_id'] ) && !isset( $_POST['applicants_id'] ) && !isset( $_POST['job_id'] )){
			echo wp_json_encode( Easyjobs_Helper::get_error_response('Invalid data') );
			wp_die();
		}
        
		$applicants = array();
		foreach ( json_decode(wp_unslash($_POST['applicants_id'])) as $applicant ) {
			$applicants[] = sanitize_text_field( $applicant );
		}
        
		echo wp_json_encode( Easyjobs_Helper::get_generic_response(Easyjobs_Api::post(
			'change_pipeline',
			sanitize_text_field( $_POST['job_id'] ),
			array(
				'applicants'  => $applicants,
				'pipeline_id' => sanitize_text_field( $_POST['pipeline_id'] ),
			)))
		);
        wp_die();
    }

    /**
     * Ajax callback for easyjobs_get_pipeline
     * Get all pipeline for a job
     *
     * @since 1.1.2
     * @return void
     */

    public function get_pipeline() {
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
		if ( isset( $_POST['job_id'] ) ) {
            $job_id    = sanitize_text_field( $_POST['job_id'] );
            $pipelines = $this->get_pipelines( $job_id );
            $job       = Easyjobs_Helper::get_job( $job_id );
            if ( empty( $pipelines ) ) {
                echo wp_json_encode(
                    array(
						'status' => 'error',
                    )
                );
            } else {
                echo wp_json_encode(
                    array(
						'status' => 'success',
						'data'   => $pipelines,
						'job'   => $job,
                    )
                );
            }
		}
        wp_die();
    }

    /**
     * Get all pipeline templates
     *
     * @since 1.4.5
     * @return mixed
     */
    public function get_pipeline_templates() {
        if(!Easyjobs_Helper::verified_request($_POST)){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
            wp_die();
		}
        $templates = Easyjobs_Api::get( 'settings_pipeline' );
        Easyjobs_Helper::check_reload_required( $templates );
        if ( Easyjobs_Helper::is_success_response( $templates->status ) ) {
            return $templates->data;
        }
        return false;
    }

    /**
     * Get all pipeline templates for ajax 
     *
     * @since 1.4.5
     * @return mixed
     */
    public function get_pipeline_template() {
        // Easyjobs_Helper::check_verified_request();
        // echo wp_json_encode( Easyjobs_Helper::get_generic_response(get_pipeline_templates()));
        // wp_die();
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
        $templates = $this->get_pipeline_templates();
        if ( empty( $templates ) ) {
            echo wp_json_encode(
                array(
                    'status' => 'error',
                )
            );
        } else {
            echo wp_json_encode(
                array(
                    'status' => 'success',
                    'data'   => $templates,
                )
            );
        }
        wp_die();
    }

    public function get_evaluation_question() {
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
			'name',
			'sort_by',
		];
		$args = [];
        if (isset($_POST['type'])) {
            $eval_type = $_POST['type'];
        } else {
            $eval_type = 'evaluation_question';
        }
		foreach ($params as $param){
			if(isset($_POST[$param])){
				$args[$param] = sanitize_text_field($_POST[$param]);
			}
		}
        $response = Easyjobs_Api::get( $eval_type, $args );
        
        Easyjobs_Helper::check_reload_required( $response );

        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            if (isset($response->data)) {
                $questions = $response->data;
            }
        }
        if ( empty( $questions ) ) {
            echo wp_json_encode(
                array(
                    'status' => 'error',
                )
            );
        } else {
            echo wp_json_encode(
                array(
                    'status' => 'success',
                    'data'   => $questions,
                )
            );
        }
        wp_die();
    }

    public function delete_evaluation_question() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if ( ! Easyjobs_Helper::verified_request($_POST) ) {
            echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
			wp_die();
		}
		if ( ! isset( $_POST['id'] ) ) {
			echo wp_json_encode(
                array(
					'status'  => 'error',
					'message' => 'Id not provided',
                )
            );
			wp_die();
		}
        $id = absint( sanitize_text_field($_POST['id']) );
        if (isset($_POST['type'])) {
            $eval_type = $_POST['type'];
        } else {
            $eval_type = 'delete_question';
        }
        $response = Easyjobs_Api::post( $eval_type, $id, array() );
		if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
			echo wp_json_encode(Easyjobs_Helper::get_success_response( __( 'Deleted successfully', 'easyjobs' ) ));
		} else {
			echo wp_json_encode(Easyjobs_Helper::get_error_response( __( 'Failed to delete, please try again or contact support', 'easyjobs' ) ));
		}

        wp_die();
    }

    private function sanitize_form_fields( $post_data, $fields ) {
        $data          = array();
        $editor_fields = array( 'details', 'responsibilities' );
        $checkboxes = array( 'is_remote', 'hideCoverPhoto' );
        $booleans = array('isMultiple', 'isValid');
        foreach ( $post_data as $key => $value ) {
            if ( in_array( $key, $fields ) ) {
                if ( Easyjobs_Helper::is_iterable( $value ) ) {
                    $data[ sanitize_text_field( $key ) ] = $value;
                } else {
                    if ( $key === 'id' ) {
                        if ( ! empty( $value ) ) {
                            $data[ sanitize_text_field( $key ) ] = absint( $value );
                        } else {
                            $data[ sanitize_text_field( $key ) ] = null;
                        }
					} else if( $key === 'title' ) {
                        $data[ sanitize_text_field( $key ) ] = $value;
                    } else {
                        if ( in_array( $key, $editor_fields ) ) {
                            $data[ sanitize_text_field( $key ) ] = wp_kses_post( $value );
						} else {
							if(in_array($key, $checkboxes)){
								$data[ sanitize_text_field( $key ) ] = $value == 1 ? 1 : 0;
							}elseif( in_array( $key, $booleans ) ) {
                                $data[$key] = $value;
                            }
                            else{
								$data[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
							}

						}
					}
                }
			}
        }
        return $data;
    }

    public function save_evaluation_question() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $set_type = json_decode( wp_unslash( $_POST['set_type'] ) );
        $set_type = json_decode(json_encode($set_type), true);
        $set_name = sanitize_text_field ($_POST['set_name']);
        $internal_note = sanitize_text_field( $_POST['internal_note'] );
        $note = sanitize_text_field( $_POST['note'] );
        $questions = json_decode( wp_unslash( $_POST['questions'] ) );
        $questions = json_decode(json_encode($questions), true);
        $sanitized = array();
		foreach ( $questions as $question ) {
			$sanitized[] = $this->sanitize_form_fields( $question, array( 'id', 'title', 'type', 'options', 'answers', 'isMultiple', 'isValid', 'errors' ) );
		}
        $data = array(
            'id'                => null,
            'set_type'          => $set_type,
            'set_name'          => $set_name,
            'internal_note'     => $internal_note,
            'note'              => $note,
            'questions'         => $sanitized,
        );
        
        if ( isset( $_POST['qs_id'] ) ) {
            $qs_id = absint( sanitize_text_field($_POST['qs_id']));
            $d = [
                
                'id'                => $qs_id,
                'set_type'          => $set_type,
                'set_name'          => $set_name,
                'internal_note'     => $internal_note,
                'note'              => $note,
                'questions'         => $sanitized,
                'exam_duration'     => null,
                'marks_per_question'=> null,
                
            ];
            $response = Easyjobs_Api::post(
                'update_question',
                $qs_id,
                $d
            );
        } else {
            $response = Easyjobs_Api::post(
                'save_question',
                null,
                $data
            );
        }
        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            echo wp_json_encode(
                array(
					'status' => 'success',
					'message'   => $response->message,
                )
            );
        } else {
            echo wp_json_encode(
                array(
					'status'  => 'error',
					'message' => $response->message,
                )
            );
        }
        wp_die();
    }

    public function get_question_set() {
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
        $id = absint(sanitize_text_field($_POST['id']));
        $response = Easyjobs_Api::get_by_id( 'question_set', $id, 'edit' );
        Easyjobs_Helper::check_reload_required( $response );
        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            if ( isset( $response->data ) ) {
                $question = $response->data;
            }
        }
        if ( empty( $question ) ) {
            echo wp_json_encode(
                array(
                    'status' => 'error',
                )
            );
        } else {
            echo wp_json_encode(
                array(
                    'status' => 'success',
                    'data'   => $question,
                )
            );
        }
        wp_die();
    }

    public function duplicate_question_set() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if ( ! Easyjobs_Helper::verified_request($_POST) ) {
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
			wp_die();
		}
		if ( empty( $_POST['id'] )) {
			echo wp_json_encode(
				array(
					'status'  => 'error',
					'message' => 'Set id not provided',
				)
			);
			wp_die();
		}
        $response = Easyjobs_Api::get_by_id(
            'question_set_duplicate',
            absint(sanitize_text_field($_POST['id'])),
            'duplicate'
        );
        Easyjobs_Helper::check_reload_required( $response );
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
					'status'  => 'error',
					'message' => $response->message,
                )
            );
        }
		wp_die();
    }

    public function save_assessment() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $set_type = absint( sanitize_text_field( $_POST['set_type'] ) );
        $assessment_name = sanitize_text_field($_POST['assessment_name']);
        $internal_note = sanitize_text_field( $_POST['internal_note'] );
        $note = sanitize_text_field( $_POST['note'] );
        $exam_duration = sanitize_text_field( $_POST['exam_duration'] );
        $marks_per_question = sanitize_text_field( $_POST['marks_per_question'] );
        $questions = json_decode( wp_unslash( $_POST['questions'] ) );
        
        $sanitized = array();
		foreach ( $questions as $question ) {
			$sanitized[] = $this->sanitize_form_fields( $question, array( 'id', 'title', 'type', 'options', 'answers', 'isMultiple', 'isValid', 'errors' ) );
		}
        $data = array(
            'id'                => null,
            'set_type'          => $set_type,
            'assessment_name'   => $assessment_name,
            'internal_note'     => $internal_note,
            'note'              => $note,
            'set_name'          => 'Assessment',
            'questions'         => $sanitized,
            'exam_duration'     => $exam_duration,
            'marks_per_question'=> $marks_per_question
        );
        
        if ( isset( $_POST['id'] ) && $_POST['id'] != 'null' ) {
            $id = absint( sanitize_text_field($_POST['id']));
            $d = [
                'id'                => $id,
                'set_type'          => $set_type,
                'assessment_name'   => $assessment_name,
                'internal_note'     => $internal_note,
                'note'              => $note,
                'set_name'          => 'Assessment',
                'questions'         => $sanitized,
                'exam_duration'     => $exam_duration,
                'marks_per_question'=> $marks_per_question,
            ];
            $response = Easyjobs_Api::post(
                'update_assessment',
                $id,
                $d
            );
        } else {
            $response = Easyjobs_Api::post(
                'save_assessment',
                null,
                $data
            );
        }
        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            echo wp_json_encode(
                array(
					'status' => 'success',
					'message'   => $response->message
                )
            );
        } else {
            echo wp_json_encode(
                array(
					'status'  => 'error',
					'message' => $response->message
                )
            );
        }
        wp_die();
    }

    public function get_assessment() {
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
        $id = absint(sanitize_text_field($_POST['id']));
        $response = Easyjobs_Api::get_by_id( 'single_assessment', $id );
        Easyjobs_Helper::check_reload_required( $response );
        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            $assessment = $response->data;
        }
        if ( empty( $assessment ) ) {
            echo wp_json_encode(
                array(
                    'status' => 'error'
                )
            );
        } else {
            echo wp_json_encode(
                array(
                    'status' => 'success',
                    'data'   => $assessment
                )
            );
        }
        wp_die();
    }
}
