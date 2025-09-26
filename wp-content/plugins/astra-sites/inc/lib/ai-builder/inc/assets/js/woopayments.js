jQuery( document ).ready( function ( $ ) {
	// Banner dismiss functionality for WooPayments notices.
	$( document ).on(
		'click',
		'.woop-dismissible-notice .notice-dismiss, .woop-notice .woop-notice-close-btn',
		function ( event ) {
			event.preventDefault();
			const $notice = $( this ).closest( '.woop-notice' );
			const noticeId = $notice.attr( 'id' );

			// Using fetch instead of jQuery.ajax
			const formData = new FormData();
			formData.append( 'action', 'dismiss_woopayments_notice' );
			formData.append( '_security', aiBuilderWooPayments.ajaxNonce );
			formData.append( 'notice_id', noticeId );

			fetch( aiBuilderWooPayments.ajaxUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
			} )
				.then( ( response ) => {
					if ( ! response.ok ) {
						throw new Error( 'Network response was not ok' );
					}
					return response.json();
				} )
				.then( () => {
					$notice.fadeOut( 500, function () {
						$( this ).remove();
					} );
				} )
				.catch( ( error ) => {
					console.error( 'Error dismissing notice:', error );
				} );
		}
	);

	// WooPayments Analytics button clicks.
	$( '.woop-notice-btn, .connect-account-page__buttons button' ).on(
		'click',
		function ( e ) {
			// Set the flag in user meta via AJAX.
			const clickSource = e.target.dataset?.source || 'onboarding';

			const formData = new FormData();
			formData.append(
				'action',
				'astra_sites_set_woopayments_analytics'
			);
			formData.append( 'source', clickSource );
			formData.append( 'nonce', aiBuilderWooPayments.ajaxNonce );

			fetch( aiBuilderWooPayments.ajaxUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin',
			} )
				.then( ( response ) => {
					if ( ! response.ok ) {
						throw new Error( 'Network response was not ok' );
					}
					// return response.json();
				} )
				.catch( ( error ) => {
					console.error( 'Error in WooPayments Analytics:', error );
				} );
		}
	);
} );
