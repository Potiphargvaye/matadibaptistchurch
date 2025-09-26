<?php
/**
 * This class is responsible for all Settings things happening in EasyJobs Plugin
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    EasyJobs
 * @subpackage EasyJobs/admin
 */
class EasyJobs_Settings {
    /**
     * @var string
     */
    private static $prefix = 'easyjobs_meta_';

    public static function init() {
        add_action( 'easyjobs_settings_header', array( __CLASS__, 'header_template' ), 10 );
        add_action( 'wp_ajax_easyjobs_general_settings', array( __CLASS__, 'general_settings_ac' ), 10 );
        add_action( 'wp_ajax_easyjobs_save_basic_info', array( __CLASS__, 'save_basic_info' ), 10 );
        add_action( 'wp_ajax_easyjobs_get_api_key', array( __CLASS__, 'get_api_key' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_api_key', array( __CLASS__, 'update_api_key' ), 10 );
        add_action( 'wp_ajax_easyjobs_disconnect_api_key', array( __CLASS__, 'disconnect_api_key' ), 10 );
        add_action( 'wp_ajax_easyjobs_get_customizer_link', array( __CLASS__, 'get_customizer_link' ), 10 );
        add_action( 'wp_ajax_easyjobs_verify_company', array( __CLASS__, 'verify_company' ), 10 );
        add_action( 'wp_ajax_easyjobs_get_shortcodes', array( __CLASS__, 'get_shortcodes' ), 10 );
        add_action( 'wp_ajax_easyjobs_upload_company_image', array( __CLASS__, 'upload_company_image' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_brand_color', array( __CLASS__, 'update_brand_color' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_show_life', array( __CLASS__, 'update_show_life' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_ai_setup', array( __CLASS__, 'update_ai_setup' ), 10 );
        add_action( 'wp_ajax_easyjobs_create_pipeline', array( __CLASS__, 'create_pipeline' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_pipeline', array( __CLASS__, 'update_pipeline' ), 10 );
        add_action( 'wp_ajax_easyjobs_delete_pipeline', array( __CLASS__, 'delete_pipeline' ), 10 );
        add_action( 'wp_ajax_easyjobs_save_category', array( __CLASS__, 'save_category' ), 10 );
        add_action( 'wp_ajax_easyjobs_save_apply_settings', array( __CLASS__, 'save_apply_settings' ), 10 );
        add_action( 'wp_ajax_easyjobs_get_minimal_company_info', array( __CLASS__, 'get_company_info' ), 10 );
        add_action( 'wp_ajax_easyjobs_save_template_settings', array( __CLASS__, 'save_template_settings' ), 10 );
        add_action( 'wp_ajax_easyjobs_delete_company_image', array( __CLASS__, 'delete_company_image' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_show_cover_photo', array( __CLASS__, 'update_show_cover_photo' ), 10 );
        add_action( 'wp_ajax_easyjobs_update_login_attachment', array( __CLASS__, 'update_login_attachment' ), 10 );
    }

    /**
     * This function is responsible for settings page header
     *
     * @hooked easyjobs_settings_header
     * @return void
     */
    public static function header_template() {
        include EASYJOBS_ADMIN_DIR_PATH . '/partials/easyjobs-admin-header.php';
    }

    /**
     * This function is responsible to update login options and email attachment
     *
     * @return mixed
     */
    public static function update_login_attachment() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if ( ! isset( $_POST['data'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Data not provided' ) );
            wp_die();
		}

        $data = json_decode(wp_unslash($_POST['data']), true);
        
        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post(
                'update_login_attachment',
                null,
                $data
            )
        );
        
        // if(Easyjobs_Helper::is_success_response($response['status'])){
        //     self::update_company_cache();
        // }

        echo wp_json_encode($response);
        wp_die();
    }

    /**
     * This function is responsible to update show hide cover photo in photos & colors
     *
     * @return mixed
     */
    public static function update_show_cover_photo() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if ( ! isset( $_POST['show_hide_cover_photo'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Status not provided' ) );
		}

        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post(
                'show_hide_cover_photo',
                null,
                array( 'remove_cover_photo' => sanitize_text_field( $_POST['show_hide_cover_photo'] ) == 'true' ? 1 : 0 )
            )
        );
        
        if(Easyjobs_Helper::is_success_response($response['status'])){
            self::update_company_cache();
        }

        echo wp_json_encode($response);
        wp_die();
    }

    /**
     * This function is responsible for remove photos in photos & colors
     *
     * @return mixed
     */
    public static function delete_company_image() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if ( ! isset( $_POST['target'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Data not provided' ) );
		}

        $_method    = sanitize_text_field( $_POST['_method'] );
        $target     = sanitize_text_field( $_POST['target'] );
        $order      = sanitize_text_field( $_POST['order'] );

        if ($order === 'null') {
            $order = null;
        }
        $params     = [ '_method' => $_method, 'target' => $target, 'order' => $order ];
        $response = Easyjobs_Api::post(
            'remove_photos',
            null,
            $params
        );

        if(Easyjobs_Helper::is_success_response($response->status)){
            self::update_company_cache();
        }
        
        echo wp_json_encode( $response );
        wp_die();
    }

    /**
     * Get all Settings fields
     *
     * @param  array $settings
     * @return array
     */
    private static function get_settings_fields( $settings ) {
        $new_fields = array();

        foreach ( $settings as $setting ) {
            $sections = isset( $setting['sections'] ) ? $setting['sections'] : array();
            if ( ! empty( $sections ) ) {
                foreach ( $sections as $section ) {
                    $fields = isset( $section['fields'] ) ? $section['fields'] : array();
                    if ( ! empty( $fields ) ) {
                        foreach ( $fields as $id => $field ) {
                            if ( isset( $field['type'] ) && $field['type'] === 'title' ) {
                                continue;
                            }
                            $new_fields[ $id ] = $field;
                        }
                    }
                }
            }
        }

        return apply_filters( 'easyjobs_settings_fields', $new_fields );
    }

    /**
     * Get the whole settings array
     *
     * @return mixed
     */
    public static function settings_args() {
        if ( ! function_exists( 'easyjobs_settings_args' ) ) {
            require EASYJOBS_ADMIN_DIR_PATH . 'includes/easyjobs-settings-page-helper.php';
        }
        do_action( 'easyjobs_before_settings_load' );
        return easyjobs_settings_args();
    }

    /**
     * Render the settings page
     *
     * @return void
     */
    public static function settings_page() {
        $settings_args = self::settings_args();
        $value         = EasyJobs_DB::get_settings();
    }

    /**
     * This function is responsible for render settings field
     *
     * @param  string $key
     * @param  array  $field
     * @return void
     */
    public static function render_field( $key = '', $field = array() ) {
        $post_id   = '';
        $name      = $key;
        $id        = self::get_row_id( $key );
        $file_name = isset( $field['type'] ) ? $field['type'] : 'text';

        if ( 'template' === $file_name ) {
            $default = isset( $field['defaults'] ) ? $field['defaults'] : array();
        } else {
            $default = isset( $field['default'] ) ? $field['default'] : '';
        }

        $saved_value = EasyJobs_DB::get_settings();
        if ( isset( $saved_value[ $name ] ) ) {
            $value = $saved_value[ $name ];
        } else {
            $value = $default;
        }

        $class     = 'easyjobs-settings-field';
        $row_class = self::get_row_class( $file_name );

        $attrs = '';

        if ( isset( $field['toggle'] ) && in_array( $file_name, array( 'checkbox', 'select', 'toggle', 'theme' ) ) ) {
            $attrs .= ' data-toggle="' . esc_attr( wp_json_encode( $field['toggle'] ) ) . '"';
        }

        if ( isset( $field['hide'] ) && $file_name == 'select' ) {
            $attrs .= ' data-hide="' . esc_attr( wp_json_encode( $field['hide'] ) ) . '"';
        }

        $field_id = $name;

        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-field-display.php';
    }

    /**
     * Get the row id ready
     *
     * @param  string $key
     * @return string
     */
    public static function get_row_id( $key ) {
        return str_replace( '_', '-', self::$prefix ) . $key;
    }

    /**
     * Get the row id ready
     *
     * @param  string $key
     * @return string
     */
    public static function get_row_class( $file ) {
        $prefix = str_replace( '_', '-', self::$prefix );

        switch ( $file ) {
            case 'group':
                $row_class = $prefix . 'group-row';
                break;
            case 'colorpicker':
                $row_class = $prefix . 'colorpicker-row';
                break;
            case 'message':
                $row_class = $prefix . 'info-message-wrapper';
                break;
            case 'theme':
                $row_class = $prefix . 'theme-field-wrapper';
                break;
            default:
                $row_class = $prefix . $file;
                break;
        }

        return $row_class;
    }

    /**
     * This function is responsible for
     * save all settings data, including checking the disable field to prevent
     * users manipulation.
     *
     * @param  array $values
     * @return void
     */
    public static function save_settings( $posted_fields = array() ) {
        $settings_args = self::settings_args();
        $fields        = self::get_settings_fields( $settings_args );
        $data          = array();

        $fields_keys = array_fill_keys( array_keys( $fields ), 0 );
        foreach ( $posted_fields as $posted_field ) {
            $posted_field['name'] = str_replace( '[]', '', $posted_field['name'] );
            if ( array_key_exists( $posted_field['name'], $fields ) ) {
                unset( $fields_keys[ $posted_field['name'] ] );
                if ( empty( $posted_field['value'] ) ) {
                    $posted_value = isset( $fields[ $posted_field['name'] ]['default'] ) ? $fields[ $posted_field['name'] ]['default'] : '';
                }
                if ( isset( $fields[ $posted_field['name'] ]['disable'] ) && $fields[ $posted_field['name'] ]['disable'] === true ) {
                    $posted_value = isset( $fields[ $posted_field['name'] ]['default'] ) ? $fields[ $posted_field['name'] ]['default'] : '';
                }
                $posted_value = EasyJobs_Helper::sanitize_field( $fields[ $posted_field['name'] ], $posted_field['value'] );
                if ( isset( $data[ $posted_field['name'] ] ) ) {
                    if ( is_array( $data[ $posted_field['name'] ] ) ) {
                        $data[ $posted_field['name'] ][] = $posted_value;
                    } else {
                        $data[ $posted_field['name'] ] = array( $posted_value, $data[ $posted_field['name'] ] );
                    }
                } else {
                    $data[ $posted_field['name'] ] = $posted_value;
                }
            }
        }

        $data = array_merge( $fields_keys, $data );

        EasyJobs_DB::update_settings( $data );
    }

    public static function general_settings_ac() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        /**
         * Verify the Nonce
         */
        if ( ( ! isset( $_POST['nonce'] ) && ! isset( $_POST['key'] ) ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'easyjobs_' . sanitize_text_field( $_POST['key'] ) . '_nonce' ) ) {
            return;
        }

        if ( isset( $_POST['form_data'] ) ) {
            self::save_settings( $_POST['form_data'] );
            echo 'success';
        } else {
            echo 'error';
        }

        die;
    }

    public static function save_basic_info() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['form_data'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Form data not found' ) );
            wp_die();
		}
        $row_data = (array) json_decode( wp_unslash( $_POST['form_data'] ) );
        $sanitized_data = self::sanitize_company_object($row_data);
        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post(
                'settings_basic_info',
                null,
                $sanitized_data
            )
        );
        if(Easyjobs_Helper::is_success_response($response['status'])){
            self::update_company_cache();
        }
        echo wp_json_encode($response);

        wp_die();
    }

    private static function sanitize_company_object($data) {
        if (is_object($data)) {
            // Iterate over each property in the object
            foreach ($data as $key => $value) {
                if ($key === 'description' || $key === 'benefits') {
                    // Sanitize with wp_kses_post() for description and benefits
                    $data->$key = wp_kses_post($value);
                } elseif (is_string($value)) {
                    // Sanitize strings with sanitize_text_field()
                    $data->$key = sanitize_text_field($value);
                } elseif (is_int($value)) {
                    // Sanitize integers with absint()
                    $data->$key = absint($value);
                } elseif (is_bool($value)) {
                    // Ensure booleans are properly cast
                    $data->$key = (bool) $value;
                } elseif (is_object($value) || is_array($value)) {
                    // Recursively sanitize objects and arrays
                    $data->$key = self::sanitize_company_object($value);
                }
            }
        } elseif (is_array($data)) {
            // Iterate over each element in the array
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize_company_object($value);
            }
        }
    
        return $data;
    }
    
    

    public static function update_company_cache()
    {
        try{
            $company_info = Easyjobs_Api::get( 'company_info' );
            if ( Easyjobs_Helper::is_success_response( $company_info->status ) ) {
                update_option( 'easyjobs_company_info', serialize( $company_info->data ) );
            }

            $state_version = ( !empty( $company_info->data ) && isset( $company_info->data->state_version ) ) ? $company_info->data->state_version : 0;
			set_transient( 'easyjobs_state_version', $state_version );

            $company_details = Easyjobs_Api::get( 'company' );
            if ( Easyjobs_Helper::is_success_response( $company_details->status ) ) {
                update_option( 'easyjobs_company_details', serialize( $company_details->data ) );
            }
            return true;
        }catch (Exception $e){
            error_log($e->getMessage());
            return false;
        }
    }

    public static function get_api_key() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if( ! Easyjobs_Helper::verified_request($_POST) ){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
            wp_die();
		}
        $key = EasyJobs_DB::get_settings( 'easyjobs_api_key' );
        if ( $key ) {
            echo wp_json_encode(
                array(
					'status' => 'success',
					'data'   => array(
						'api_key' => $key,
					),
                )
            );
        } else {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Api key not found' ) );
        }
        wp_die();
    }

    public static function update_api_key() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( empty( $_POST['api_key'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Api key not found' ) );
            wp_die();
		}
        $api_key = sanitize_text_field( $_POST['api_key'] );
		if ( Easyjobs_Api::authenticate( $api_key )) {
			try {
				self::save_settings(
                    array(
						array(
							'name'  => 'easyjobs_api_key',
							'value' => $api_key,
						),
                    )
                );
				echo wp_json_encode(
					Easyjobs_Helper::get_success_response(
                        'Successfully authenticated',
                        array(
							'api_key' => $api_key,
                        )
                    )
                );
			} catch ( Exception $e ) {
				echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Failed to save api key, please tyr again or contact support' ) );
			}
		} else {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Api key is not valid' ) );
		}
        wp_die();
    }

    public static function get_customizer_link() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if( ! Easyjobs_Helper::verified_request($_POST) ){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
            wp_die();
		}
        echo wp_json_encode(
            Easyjobs_Helper::get_success_response(
                'Request success',
                array(
					'link' => Easyjobs_Helper::customizer_link(),
                )
            )
        );

        wp_die();
    }

    public static function disconnect_api_key() {
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
		if ( Easyjobs_Helper::after_disconnect_api() ) {
            echo wp_json_encode(
                array(
					'status' => 'success',
                )
			);
		} else {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Api disconnect failed' ) );
		}

        wp_die();
    }

    public static function verify_company() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['is_verified'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Verification status not sent.' ) );
            wp_die();
		}
        $response = Easyjobs_Api::post(
            'settings_verify',
            null,
            array( 'is_verified' => sanitize_text_field( wp_unslash($_POST['is_verified']) ) === 'true' )
        );
        set_transient( 'easyjobs_company_verification_status', $response->data->is_verified ? 'yes' : 'no', 3600 );
        echo wp_json_encode( Easyjobs_Helper::get_generic_response( $response ) );
        wp_die();
    }

    public static function get_shortcodes() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if( ! Easyjobs_Helper::verified_request($_POST) ){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
            wp_die();
		}
        $jobs = Easyjobs_Api::get( 'published_jobs', ['rows' => 1000]);
        Easyjobs_Helper::check_reload_required( $jobs );
        if ( $jobs->status != 'success' ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Unable to get jobs from api' ) );
        }
        $shortcodes = array(
			array(
				'name' => 'Landing page with profile',
				'code' => '[easyjobs]',
			),
			array(
				'name' => 'All jobs list',
				'code' => '[easyjobs_list]',
			),
        );

        if ( is_array( $jobs->data->data ) ) {
            foreach ( $jobs->data->data as $job ) {
                $shortcodes[] = array(
                    'name' => $job->title,
                    'code' => '[easyjobs_details id=' . $job->id . ']',
                );
            }
        }

        echo wp_json_encode(
            array(
				'status' => 'success',
				'data'   => $shortcodes,
            )
        );

        wp_die();
    }

    public static function upload_company_image() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post_with_file(
                'company_photo',
                array(
                    'target' => isset($_POST['target']) ? sanitize_text_field( $_POST['target'] ) : '',
                ),
                $_FILES['file']
            )
        );

        if(Easyjobs_Helper::is_success_response($response['status'])){
            self::update_company_cache();
        }

        echo wp_json_encode($response);
        wp_die();
    }

    public static function update_brand_color() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['brand_color'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Color not provided' ) );
		}

        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post(
                'brand_color',
                null,
                array( 'brand_color' => sanitize_text_field( $_POST['brand_color'] ) )
            )
        );

        if(Easyjobs_Helper::is_success_response($response['status'])){
            self::update_company_cache();
        }

        echo wp_json_encode($response);
        wp_die();
    }

    public static function update_show_life() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['show_life'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Status not provided' ) );
		}

        $response = Easyjobs_Helper::get_generic_response(
            Easyjobs_Api::post(
                'show_life',
                null,
                array( 'show_life' => sanitize_text_field( $_POST['show_life'] ) == 'true' ? 1 : 0 )
            )
        );
        
        if(Easyjobs_Helper::is_success_response($response['status'])){
            self::update_company_cache();
        }

        echo wp_json_encode($response);
        wp_die();
    }
    public static function update_ai_setup() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $fields = array( 'is_ai_enabled', 'run_batch_score' );
        $data    = array();
        foreach ( $_POST as $name => $value ) {
            if ( in_array( $name, $fields ) ) {
                $data[ $name ] = sanitize_text_field( $value ) === 'true';
            }
        }
        $response = Easyjobs_Api::post(
            'ai_setup',
            null,
            $data
        );
        if ( Easyjobs_Helper::is_success_response( $response->status ) ) {
            EasyJobs_DB::update_settings( (int) $response->data->ai_setup_enabled === 1 ? 'yes' : 'no', 'easyjobs_ai_setup' );
        }
        echo wp_json_encode( Easyjobs_Helper::get_generic_response( $response ) );
        wp_die();
    }

    public static function create_pipeline() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $steps = json_decode( wp_unslash( $_POST['steps'] ) );
        array_walk( $steps, 'sanitize_text_field' );
        echo wp_json_encode(
            Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post(
                    'create_pipeline',
                    null,
                    array(
						'name'  => sanitize_text_field( $_POST['name'] ),
						'steps' => $steps,
                    )
                )
            )
		);
        wp_die();
    }

    public static function update_pipeline() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['id'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Pipeline id not provided' ) );
            wp_die();
		}
        $steps = json_decode( wp_unslash( $_POST['steps'] ) );
        array_walk( $steps, 'sanitize_text_field' );
        echo wp_json_encode(
            Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post(
                    'update_pipeline',
                    abs( sanitize_text_field( $_POST['id'] ) ),
                    array(
						'name'  => sanitize_text_field( $_POST['name'] ),
						'steps' => $steps,
                    )
                )
            )
        );
        wp_die();
    }

    public static function delete_pipeline() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
		if ( ! isset( $_POST['pipeline'] ) ) {
            echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Pipeline id not provided' ) );
            wp_die();
		}
        echo wp_json_encode(
            Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post(
                    'delete_pipeline',
                    abs( sanitize_text_field( $_POST['index'] ) ),
                    json_decode(  $_POST['pipeline'] )
                )
            )
        );
        wp_die();
    }

    /**
     * Ajax callback for easyjobs_save_apply_settings action
     * Save candidate apply settings data
     *
     * @since 1.3.0
     */
    public static function save_apply_settings() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        $custom_fields_data = json_decode( wp_unslash( $_POST['custom_fields'] ) );
        $custom_fields = array();
        foreach ( $custom_fields_data as $custom_field ) {
            $custom_fields[] = (object) array(
                'id'            => ! is_null( $custom_field->id ) ? abs( sanitize_key( $custom_field->id ) ) : null,
                'type'          => sanitize_text_field( $custom_field->type ),
                'field_name'    => sanitize_text_field( $custom_field->field_name ),
                'allowed_types' => empty($custom_field->allowed_types) ? [] : $custom_field->allowed_types,
                'active'        => $custom_field->active,
            );
        }
        echo wp_json_encode(
            Easyjobs_Helper::get_generic_response(
                Easyjobs_Api::post(
                    'save_apply_settings',
                    null,
                    array(
						'custom_fields' => $custom_fields,
                    )
                )
            )
		);
        wp_die();
    }

    public static function get_company_info() {
        if ( ! Easyjobs_Helper::can_update_options() ) {
			echo wp_json_encode(
				array(
					'status'     => 'error',
					'message'    => 'Invalid request !!',
				)
			);
			wp_die();
        }
        if( ! Easyjobs_Helper::verified_request($_POST) ){
			echo wp_json_encode(Easyjobs_Helper::get_error_response('Invalid request'));
            wp_die();
		}
		if ( $info = Easyjobs_Helper::get_company_info(true) ) {
            echo wp_json_encode( Easyjobs_Helper::get_success_response( 'Company info success', $info ) );
		} else {
			echo wp_json_encode( Easyjobs_Helper::get_error_response( 'Failed to get company info' ) );
		}
        wp_die();
    }

    public static function save_template_settings() {
        if ( ! Easyjobs_Helper::verified_request($_POST)  || ! Easyjobs_Helper::can_update_options()) {
            echo json_encode(
                array(
					'status'  => 'error',
					'message' => 'Invaild request',
                )
            );
            wp_die();
        }
        if(!isset($_POST['template'])){
            echo wp_json_encode(Easyjobs_Helper::get_error_response('Template not provided'));
            wp_die();
        }
        $response = Easyjobs_Api::post(
            'template_settings',
            null,
            ['template' => sanitize_text_field($_POST['template'])]
        );

        if(Easyjobs_Helper::is_success_response($response->status)){
            $settings = EasyJobs_DB::get_settings();
            $transient = get_transient('elej_company_' . md5( $settings['easyjobs_api_key'] ));
            if(!empty($transient)){
                delete_transient('elej_company_' . md5( $settings['easyjobs_api_key'] ));
            }
            if($saved_data = unserialize(get_option('easyjobs_company_info'))){
                $saved_data->selected_template = sanitize_text_field($_POST['template']);
                update_option('easyjobs_company_info', serialize($saved_data));
            }
        }

        if(Easyjobs_Helper::is_success_response($response->status)){
            self::update_company_cache();
        }
        echo wp_json_encode(Easyjobs_Helper::get_generic_response($response));
        wp_die();
    }
}
