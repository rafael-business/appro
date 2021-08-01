( function( $ ) {
	'use strict';

	$( document ).ready( function() {
		
		$( '.tab-content' ).hide();
		$( '.tab-content' ).eq(0).show();
		$( '.tab-title-layout' ).eq(0).addClass( 'active' );

		$( document ).on( 'click', '.tab-title-layout', function( e ) {

			e.preventDefault();
			$( '.tab-title-layout' ).removeClass( 'active' );
			$( this ).addClass( 'active' );
			$( this ).blur();
			var open = $( this ).data( 'open' );
			
			$( '.tab-content' ).hide();
			$( '#'+ open ).show();
		});

		$( document ).on( 'click', '#open-filter', function( e ) {

			e.preventDefault();

			$( '.filter' ).eq(0).find( 'select' ).each( function( i ) {
				
				var index = $( this )[0].selectedIndex;
				var value = index ?? $( this )[0].options[index].value;
				if ( value ) {
					
					$( this ).find( 'option[value='+ value +']' ).eq(0).attr( 'selected','selected' );
				}
			});

			var filter = $( '.filter' ).eq(0).html();
			
			$.dialog({
			    title: 'Filtro',
			    content: filter,
			    boxWidth: '320px',
    			useBootstrap: false
			});
		});

		$( document ).on( 'click', '#open-order', function( e ) {

			e.preventDefault();
			
			$.dialog({
			    title: 'Ordem',
			    content: 'inputs',
			    boxWidth: '320px',
    			useBootstrap: false
			});
		});

		$( document ).on( 'click', '#open-export', function( e ) {

			e.preventDefault();

			TableExport.prototype.charset = "charset=iso-8859-1";
			TableExport.prototype.defaultButton = "button";
			TableExport.prototype.formatConfig.xlsx.buttonContent = '.xlsx';

			var table = TableExport( document.querySelectorAll( '#appro-table .table' )[0] );
			table.update({
				filename: 'nome_doc',
				formats: ['xlsx'],
				position: 'top'
			});

		});
	});

})( jQuery );
