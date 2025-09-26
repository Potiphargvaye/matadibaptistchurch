<?php
function easyjobs_settings_args() {
    $settings_tabs = array(
        'general' => array(
            'title'       => __( 'General', 'easyjobs' ),
            'priority'    => 10,
            'button_text' => __( 'Save Settings', 'easyjobs' ),
            'sections'    => apply_filters(
                'easyjobs_general_settings_sections',
                array(
					'general_settings' => apply_filters(
						'easyjobs_general_settings',
						array(
							'title'    => __( 'General Settings', 'easyjobs' ),
							'priority' => 10,
							'fields'   => array(
								'easyjobs_api_key' => array(
									'type'     => 'text',
									'label'    => __( 'Api Key', 'easyjobs' ),
									'priority' => 11,
									'help'     => 'Don\'t have a API Key? <a href="https://app.easy.jobs/login" target="_blank">Sign Up</a> to get started.',
								),

							),
                        )
                    ),
                )
            ),
        ),
    );
    return apply_filters( 'easyjobs_settings_tab', $settings_tabs );
}
