jQuery( document ).ready( function ( $ ) {
	$( '.blockchain-lite-onboarding-notice' ).parents( '.is-dismissible' ).on( 'click', 'button', function ( e ) {
		$.ajax( {
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'blockchain_lite_dismiss_onboarding',
				nonce: blockchain_lite_Onboarding.dismiss_nonce,
				dismissed: true
			},
			dataType: 'text',
			success: function ( response ) {
			}
		} );
	} );
} );
