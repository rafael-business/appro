( function( $ ) {
	'use strict';

	$( document ).ready( function() {
		
		$( '.tab-content' ).hide();
		$( '.tab-content' ).eq(0).show();

		$( document ).on( 'click', '.tab-title', function( e ) {

			e.preventDefault();
			var open = $( this ).data( 'open' );
			
			$( '.tab-content' ).hide();
			$( '#'+ open ).show();
		});
	});

})( jQuery );
