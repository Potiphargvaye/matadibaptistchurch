(function ($) {
	'use strict';
	/**
	 * Run function when customizer is ready.
	 */
	function customizer_controls_show(setting,controler_name,controler_val){
		wp.customize.control( controler_name, function( control ) {
			var controler_array = controler_val.split(',');
			var visibility = function() {
				if ( $.inArray(setting.get(), controler_array) > -1 ) {
					control.container.slideDown( 180 );
				} else {
					control.container.slideUp( 180 );
				}
			};
			visibility();
			setting.bind( visibility );
		});
	}

	function customizer_controls_hide(setting,controler_name,controler_val){
		wp.customize.control( controler_name, function( control ) {
			var controler_array = controler_val.split(',');
			var visibility = function() {
				if ( $.inArray(setting.get(), controler_array) > -1 ) {
					control.container.slideUp( 180 );
				} else {
					control.container.slideDown( 180 );
				}
			};
			visibility();
			setting.bind( visibility );
		});
	}

	function customizer_conditional_setting_return_toggle(setting,controler_name,controler_val){
		wp.customize.control( controler_name, function( control ) {
			var visibility = function() {
				if ( setting.get() == true ) {
					control.container.slideDown( 180 );
				} else {
					control.container.slideUp( 180 );
				}
			};
			visibility();
			setting.bind( visibility );
		});
	}

	wp.customize.bind( 'ready', function() {
		wp.customize( 'easyjobs_landing_custom_max_width', function( setting ) {
			customizer_conditional_setting_return_toggle(setting,'easyjobs_landing_container_max_width',true);
		});
		wp.customize( 'easyjobs_landing_custom_content_max_width', function( setting ) {
			customizer_conditional_setting_return_toggle(setting,'easyjobs_landing_content_max_width',true);
		});
	});

})(jQuery);