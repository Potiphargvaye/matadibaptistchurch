<?php

class Easyjobs_Api {

    /**
     * Api token
     *
     * @var string
     */
    private static $token;



    /**
     * Available get endpoints
     * for retriving data from easyjobs app
     *
     * @var array
     */
    private static $get_endpoints = array(
        'company'                   => EASYJOBS_API_URL . 'company/details',
        'company_info'              => EASYJOBS_API_URL . 'company/info',
        'published_jobs'            => EASYJOBS_API_URL . 'job/published',
        'draft_jobs'                => EASYJOBS_API_URL . 'job/draft',
        'all_jobs'                  => EASYJOBS_API_URL . 'job/all',
        'archived_jobs'             => EASYJOBS_API_URL . 'job/archive',
        'job_pipeline'              => EASYJOBS_API_URL . 'job/',
        'job_candidates'            => EASYJOBS_API_URL . 'job/',
        'job_details'               => EASYJOBS_API_URL . 'job/',
        'job'                       => EASYJOBS_API_URL . 'job/',
        'candidate'                 => EASYJOBS_API_URL . 'job/applicants/',
        'packages'                  => EASYJOBS_API_URL . 'subscription/packages/',
        'company_stats'             => EASYJOBS_API_URL . 'company/stats/',
        'company_recent_applicants' => EASYJOBS_API_URL . 'company/recent-applicants/',
        'company_recent_jobs'       => EASYJOBS_API_URL . 'company/recent-jobs/',
        'company_jobs'              => EASYJOBS_API_URL . 'company/jobs',
        'company_candidates'        => EASYJOBS_API_URL . 'company/candidates/',
        'company_metadata'          => EASYJOBS_API_URL . 'company-meta-data/',
        'categories'                => EASYJOBS_API_URL . 'category/',
        'job_metas'                 => EASYJOBS_API_URL . 'job/meta/',
        'skills'                    => EASYJOBS_API_URL . 'skill/',
        'country'                   => EASYJOBS_API_URL . 'country/',
        'states'                    => EASYJOBS_API_URL . 'state/custom/',
        'screening_question_meta'   => EASYJOBS_API_URL . 'job/screening-meta-data',
        'quiz_meta'                 => EASYJOBS_API_URL . 'job/quiz-meta-data',
        'settings_basic_info'       => EASYJOBS_API_URL . 'company/setting/basic-info',
        'settings_metadata'         => EASYJOBS_API_URL . 'company-meta-data',
        'verification_code'         => EASYJOBS_API_URL . 'company/setting/verification-code',
        'photos_and_colors'         => EASYJOBS_API_URL . 'company/setting/company-photo',
        'ai_setup'                  => EASYJOBS_API_URL . 'company/setting/ai-setup',
        'settings_pipeline'         => EASYJOBS_API_URL . 'company/setting/pipeline',
        'settings_categories'       => EASYJOBS_API_URL . 'company/setting/category',
        'settings_skills'           => EASYJOBS_API_URL . 'company/setting/skill',
        'apply_settings'            => EASYJOBS_API_URL . 'company/setting/apply-setting',
        'candidate_note'            => EASYJOBS_API_URL . 'job/applicants/',
        'job_templates'             => EASYJOBS_API_URL . 'job/templates/',
        'template_settings'         => EASYJOBS_API_URL . 'company/setting/templates/',
        'evaluation_question'       => EASYJOBS_API_URL . 'company/question/groups/',
        'question_set'              => EASYJOBS_API_URL . 'company/question/group/',
        'question_set_duplicate'    => EASYJOBS_API_URL . 'company/question/group/',
        'assessments'               => EASYJOBS_API_URL . 'company/assessments/',
        'single_assessment'         => EASYJOBS_API_URL . 'company/assessments/',
        'candidate_ids'             => EASYJOBS_API_URL . 'candidate/navigation/',
	    'analytics_info'            => EASYJOBS_API_URL . 'company/analytics',
        'job_analytics_stats'       => EASYJOBS_API_URL . 'company/recent-jobs/analytics',
        'company_active_jobs'       => EASYJOBS_API_URL . 'job/active',
        'job_locations'             => EASYJOBS_API_URL . 'job/locations',
    );

    /**
     * Available post endpoints
     *
     * @var array
     */
    private static $post_endpoints = array(
        'change_pipeline'     => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/candidate/pipeline',
        ),
        'save_pipeline'       => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/pipeline/update',
        ),
        'sign_in'             => array(
            'url' => EASYJOBS_API_URL . 'sign-in/',
        ),
        'sign_up'             => array(
            'url' => EASYJOBS_API_URL . 'sign-up/',
        ),
        'create_company'      => array(
            'url' => EASYJOBS_API_URL . 'create-company/',
        ),
        'save_job_info'       => array(
            'url' => EASYJOBS_API_URL . 'job/create/',
        ),
        'save_questions'      => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/screening',
        ),
        'save_quiz'           => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/quiz',
        ),
        'change_status'       => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/change-status',
        ),
        'update_job_info'     => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/update',
        ),
        'required_fields'     => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/required-fields',
        ),
        'delete_job'          => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/delete',
        ),
        'settings_basic_info' => array(
            'url' => EASYJOBS_API_URL . 'company/setting/basic-info',
        ),
        'settings_verify'     => array(
            'url' => EASYJOBS_API_URL . 'company/setting/verify',
        ),
        'company_photo'       => array(
            'url' => EASYJOBS_API_URL . 'company/setting/company-photo',
        ),
        'brand_color'         => array(
            'url' => EASYJOBS_API_URL . 'company/setting/brand-color',
        ),
        'show_life'           => array(
            'url' => EASYJOBS_API_URL . 'company/setting/show-life',
        ),
        'ai_setup'            => array(
            'url' => EASYJOBS_API_URL . 'company/setting/ai-setup',
        ),
        'create_pipeline'     => array(
            'url' => EASYJOBS_API_URL . 'company/setting/pipeline',
        ),
        'update_pipeline'     => array(
            'url'  => EASYJOBS_API_URL . 'company/setting/pipeline/',
            'part' => '/update',
        ),
        'delete_pipeline'     => array(
            'url'  => EASYJOBS_API_URL . 'company/setting/pipeline/',
            'part' => '/delete',
        ),
        'save_category'       => array(
            'url' => EASYJOBS_API_URL . 'company/setting/category/save',
        ),
        'save_skill'          => array(
            'url' => EASYJOBS_API_URL . 'company/setting/skill/save',
        ),
        'delete_category'     => array(
            'url'  => EASYJOBS_API_URL . 'company/setting/category/',
            'part' => '/delete',
        ),
        'delete_skill'        => array(
            'url'  => EASYJOBS_API_URL . 'company/setting/skill/',
            'part' => '/delete',
        ),
        'delete_custom_field' => array(
            'url'  => EASYJOBS_API_URL . 'company/setting/apply-setting/',
            'part' => '/delete',
        ),
        'save_apply_settings' => array(
            'url' => EASYJOBS_API_URL . 'company/setting/apply-setting/',
        ),
        'invite_candidate'    => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/candidate/add',
        ),
        'delete_invitation'   => array(
            'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/invitations/delete',
        ),
        'rate_candidate'      => array(
            'url'  => EASYJOBS_API_URL . 'job/applicants/',
            'part' => '/rating',
        ),
	    'save_candidate_note' => array(
		    'url'  => EASYJOBS_API_URL . 'job/applicants/',
		    'part' => '/note',
	    ),
	    'delete_candidate'    => array(
		    'url'  => EASYJOBS_API_URL . 'job/',
		    'part' => '/candidate/delete',
	    ),
        'analytics_url'       => array(
			'url' => EASYJOBS_API_URL . 'company/setting/analytics-url',
        ),
		'pending_candidate'    => array(
			'url'  => EASYJOBS_API_URL . 'job/',
			'part' => '/candidate/pending/invite',
		),
		'delete_pending_candidate'    => array(
			'url'  => EASYJOBS_API_URL . 'job/',
			'part' => '/candidate/delete',
		),
		'job_duplicate'    => array(
			'url'  => EASYJOBS_API_URL . 'job/',
			'part' => '/duplicate',
		),
		'template_settings'       => array(
			'url' => EASYJOBS_API_URL . 'company/setting/templates',
		),
		'delete_question'    => array(
			'url'  => EASYJOBS_API_URL . 'company/question/group/',
			'part' => '/delete',
		),
        'save_question'    => array(
			'url'  => EASYJOBS_API_URL . 'company/question/group/create',
		),
        'update_question'    => array(
			'url'  => EASYJOBS_API_URL . 'company/question/group/',
			'part' => '/update',
		),
        'delete_assessment'    => array(
			'url'  => EASYJOBS_API_URL . 'company/assessments/',
			'part' => '/delete',
		),
        'save_assessment'    => array(
			'url'  => EASYJOBS_API_URL . 'company/assessments/create',
		),
        'update_assessment'    => array(
			'url'  => EASYJOBS_API_URL . 'company/assessments/',
			'part' => '/update',
		),
        'remove_photos'    => array(
			'url'  => EASYJOBS_API_URL . 'company/setting/company-photo',
		),
        'show_hide_cover_photo'    => array(
			'url'  => EASYJOBS_API_URL . 'company/setting/remove-cover-photo',
		),
        'pin_unpin_job'    => array(
			'url'  => EASYJOBS_API_URL . 'job/',
            'part' => '/pinned'
		),
        'update_login_attachment'    => array(
			'url'  => EASYJOBS_API_URL . 'company/setting/apply-setting/update',
		),
        'save_state'    => array(
			'url'  => EASYJOBS_API_URL . 'state/',
		),
        'save_city'    => array(
			'url'  => EASYJOBS_API_URL . 'city/',
		),
    );

    /**
     * Check if api key is valid
     *
     * @param string $api_key
     * @return bool
     */
    public static function authenticate( $api_key ) {
        if ( empty( $api_key ) ) {
            return false;
        }
        $response = self::remote_get( self::$get_endpoints['company'], $api_key );
        if ( $response->status === 'success' ) {
            return true;
        }
        return false;
    }

	/**
	 * Get data for specific endpoints
	 *
	 * @param         $type
	 * @param  array $params
	 *
	 * @return mixed
	 */
    public static function get( $type, $params = array() ) {
        if ( empty( $type ) ) {
            return false;
        }
        if ( ! array_key_exists( $type, self::$get_endpoints ) ) {
            return false;
        }
        if ( empty( $params ) ) {
            return self::remote_get( self::$get_endpoints[ $type ] );
        }

        $url = rtrim( self::$get_endpoints[ $type ], '/' );

        $url = $url . '?';
        foreach ( $params as $key => $param ) {
            $url .= $key . '=' . $param . '&';
        }
        $url = rtrim( $url, '&' );
        return self::remote_get( $url );
    }

    /**
     * Get data from api with id
     *
     * @param string $type
     * @param int    $id
     * @param string $suffix
     * @return bool|object
     */
    public static function get_by_id( $type, $id, $suffix = '' ) {
        if ( empty( $type ) || ! array_key_exists( $type, self::$get_endpoints ) ) {
            return false;
        }
        if ( empty( $id ) ) {
            return false;
        }
        $url = self::$get_endpoints[ $type ] . $id;
        if ( ! empty( $suffix ) ) {
            $url .= '/' . $suffix;
        }
        return self::remote_get( $url );
    }

    /**
     * Get data from api with id
     *
     * @param string $url
     * @param array  $params
     * @return bool|object
     * @since 1.1.3
     */
    public static function get_custom( $url, $params = array() ) {
        if ( empty( $params ) ) {
            return self::remote_get( $url );
        }

        $url = rtrim( $url, '/' );

        $url = $url . '?';
        foreach ( $params as $key => $param ) {
            $url .= $key . '=' . $param . '&';
        }
        $url = rtrim( $url, '&' );

        return self::remote_get( $url );
    }

    /**
     * @param $type
     * @id int
     * @param $data
     * @return array|bool|mixed|object|string
     */
    public static function post( $type, $id, $data = array(), $token = true, $headers = array() ) {
        if ( empty( $type ) ) {
            return false;
        }
        if ( ! array_key_exists( $type, self::$post_endpoints ) ) {
            return 'Invalid request. No endpoints for ' . $type;
        }

        $url = self::$post_endpoints[ $type ]['url'];

        if ( ! empty( $id ) || (string) $id === '0' ) {
            $url .= $id;
        }

        if ( ! empty( self::$post_endpoints[ $type ]['part'] ) ) {
            $url .= self::$post_endpoints[ $type ]['part'];
        }
        return self::remote_post( $url, $data, $headers, $token );
    }

	public static function post_custom( $url, $data = array(), $token = true, $headers = array() ) {
		if ( empty( $url ) ) {
			return false;
		}
		return self::remote_post( $url, $data, $headers, $token );
	}


    /**
     * Get search results based on job type
     *
     * @param $type
     * @return mixed
     */
    public static function search( $type, $keyord, $page = 1 ) {
        if ( empty( $type ) ) {
            return false;
        }
        $url = self::$get_endpoints[ $type ] . '?search=' . rawurlencode( $keyord ) . '&page=' . $page;
        return self::remote_get( $url );

    }

    /**
     * @param int      $job_id
     * @param string   $type
     * @param $keywords
     * @param string   $request_url @since 1.3.1
     * @return array|bool|mixed|object
     */
    public static function search_within_job( $job_id, $type, $keywords, $request_url = '' ) {
        if ( empty( $job_id ) && empty( $type ) && empty( $keyords ) ) {
            return false;
        }
        if ( ! empty( $request_url ) ) {
			$url = $request_url . '?' . $keywords;
        } else {
            $url = self::$get_endpoints[ $type ] . $job_id . '/' . str_replace( 'job_', '', $type ) . '?' . $keywords;
        }

        return self::remote_get( $url );

    }

    public static function post_with_basic_auth( $type, $data, $credentials ) {
        $return_object = array( 'status' => 'error' );
        if ( empty( $type ) ) {
            $return_object['message'] = 'Request endpoint type is not provided';
            return (object) $return_object;
        }
        if ( ! array_key_exists( $type, self::$post_endpoints ) ) {
            $return_object['message'] = 'Invalid request. No endpoints for ' . $type;
            return (object) $return_object;
        }
        if ( empty( $data ) ) {
            $return_object['message'] = 'Invalid request. Data not provided';
            return (object) $return_object;
        }
        if ( empty( $credentials ) ) {
            $return_object['message'] = 'Invalid request. Credentials not provided';
            return (object) $return_object;
        }
        $request = array(
            'body' => $data,
        );

        $request['headers']['Authorization'] = 'Basic ' . base64_encode( $credentials['email'] . ':' . $credentials['password'] );
		$request['headers']['x-plugin-version'] = EASYJOBS_VERSION;
        $request['timeout'] = 30;

        $response = wp_remote_post( self::$post_endpoints[ $type ]['url'], $request );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return (object) array(
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $error_message,
            );
        }
        return json_decode( $response['body'] );
    }

	/**
	 * @param $type
	 * @param $data
	 * @param $file
	 * @return array
	 */
	public static function post_with_file($type, $data, $file ) {
        $boundary = wp_generate_password( 24 );
        $headers  = array(
            'content-type'  => 'multipart/form-data; boundary=' . $boundary,
            'Authorization' => 'Bearer ' . self::get_token(),
			'x-plugin-version' => EASYJOBS_VERSION
        );
        $payload  = '';
        foreach ( $data as $name => $value ) {
            $payload .= '--' . $boundary;
            $payload .= "\r\n";
            $payload .= 'Content-Disposition: form-data; name="' . $name .
                '"' . "\r\n\r\n";
            $payload .= $value;
            $payload .= "\r\n";
        }
        if ( $file ) {
            $payload .= '--' . $boundary;
            $payload .= "\r\n";
            $payload .= 'Content-Disposition: form-data; name="' . 'file' .
                '"; filename="' . $file['name'] . '"' . "\r\n";
            $payload .= "\r\n";
            $payload .= file_get_contents( $file['tmp_name'] );
            $payload .= "\r\n";
        }
        $payload .= '--' . $boundary . '--';
        $response = wp_remote_post(
            self::$post_endpoints[ $type ]['url'],
            array(
                'headers' => $headers,
                'body'    => $payload,
                'timeout' => 30,
            )
        );
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return array(
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $error_message,
            );
        }

        return json_decode( $response['body'] );
    }

    /**
     * Get data from api
     *
     * @param string $url
     * @param string $api_key
     * @param array  $options
     * @return object
     */
    private static function remote_get( $url, $api_key = '', $options = array() ) {
        if ( ! empty( $api_key ) ) {
            $token = $api_key;
        } else {
            $token = self::get_token();
        }

        // add_filter('https_ssl_verify', '__return_false');
        // dd($url);
        $response = wp_remote_get(
            $url,
            array_merge(
                array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $token,
						'x-plugin-version' => EASYJOBS_VERSION,
                        'x-state-version'  => (int) Easyjobs_Helper::get_state_version(),
                    ),
                    'timeout' => 30
                ),
                $options
            )
        );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return (object) array(
                'status'     => 'error',
                'error_type' => 'fetch error',
                'message'    => 'Something went wrong. Details: ' . $error_message,
            );
        }

        // dd($response);
        // 5 steps for checking state version
        // 1. Check if state version error
        // 2. If error then refetch company data
        // 3. Update company data in db and update state version
        // 4. Remove transaint
        // 5. Recall the same api
        if ( Easyjobs_Helper::is_state_version_error( json_decode( $response['body'] ) ) ) {
            // dd($response);
            Easyjobs_Helper::update_cache();
            // var_dump($response);
            // return self::remote_get( $url, $api_key, array_merge( $options, ['company_cache_updated' => true] ) );
            return (object) array(
                'reload_required'     => true,
            );
        }
        
        if ( is_array( $response ) ) {
            $res = json_decode( $response['body'] );
            // if( is_object( $res ) && !empty( $options['company_cache_updated'] ) ){
            //     $res->status = 'reload';
            // }
            return $res; 
        }
        return (object) array(
            'status'     => 'error',
            'error_type' => 'fetch error',
            'message'    => 'Something went wrong. Try again later',
        );
    }

    /**
     * Post data to api
     *
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @param bool   $token
     * @return array|mixed|object
     */
    private static function remote_post( $url, $data, $headers = array(), $token = true ) {

        $request = array();

        if ( ! empty( $data ) ) {
            $request = array(
                'body' => $data,
            );
        }

        if ( $token ) {
            $request['headers']['Authorization'] = 'Bearer ' . self::get_token();
            $request['headers']['x-plugin-version'] = EASYJOBS_VERSION;
        }

        if ( ! empty( $headers ) ) {
            $request['headers'] = array_merge( $headers, $request['headers'] );
        }
        $request['timeout'] = 30;
        $response = wp_remote_post( $url, $request );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return array(
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $error_message,
            );
        }
        return json_decode( $response['body'] );
    }

    /**
     * Get token from database if not set
     *
     * @return string
     */
    private static function get_token() {
		if ( ! empty( self::$token ) ) {
            return self::$token;
		}
        $settings    = EasyJobs_DB::get_settings();
		self::$token = ! empty( $settings['easyjobs_api_key'] ) ? $settings['easyjobs_api_key'] : '';
		return self::$token;
    }

}
