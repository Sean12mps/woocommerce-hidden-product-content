jQuery( document ).ready( function ( $ ) {

	$( '.wc_hpc_email_login .wc_hpc_submit' ).on( 'click', function( e ) {

		e.preventDefault();

		var
			el = $( this ),
			email = $( this ).parents( '.wc_hpc_email_login' ).find( '[name="wc_hpc-customer_email"]' ).val()
			product_id = $( this ).parents( '.wc_hpc_email_login' ).find( '[name="wc_hpc-product_id"]' ).val()
		;

		$( el ).attr( 'disabled', 'disabled' );
		$( el ).find( '.wc_hpc_loader' ).toggleClass( 'hide' );

		$.ajax( {
			type: "POST",
			url: wc_hpc_vars.ajaxurl,
			data: {
				action: 'wc_hpc_login',
				email: email,
				product_id: product_id,
				wc_hpc_nonce: wc_hpc_vars.nonce,
			},
			dataType: "JSON",
			success: function ( response ) {

				$( el ).removeAttr( 'disabled' );
				$( el ).find( '.wc_hpc_loader' ).toggleClass( 'hide' );

				$( el ).parents( '.wc_hpc_email_login' ).find( '.wc_hpc_login_response' ).html( response.message );

				if ( response.valid ) {

					$( el ).parents( '.wc_hpc_email_login' ).find( '.wc_hpc_login_form' ).remove();

					$( el ).remove();
				}
			}
		} );
	} )

	$( 'body' ).on( 'click', '.wchpc-scroll', function( e ) {

		e.preventDefault();

		const elTarget = $( this ).attr( 'href' );

		$( [ document.documentElement, document.body]  ).animate( {
			scrollTop: $( elTarget ).offset().top
		}, 2000);
	} );
} );
