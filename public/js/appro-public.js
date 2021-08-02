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

			var order = $( '.order' ).eq(0).html();
			
			$.dialog({
			    title: 'Ordenação',
			    content: order,
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

		$( document ).on( 'change', '#filter_data [name=data]', function( e ) {

			e.preventDefault();
			$('#filter_data').submit();
		});

		$( document ).on( 'click', '.add', function( e ) {

			e.preventDefault();

			var title = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><line x1="9" y1="14" x2="15" y2="14" /></svg>';

			title += $( '#post-add-title' ).eq(0).val();
			var post_add = $( '.post-add' ).eq(0).html();
			
			$.dialog({
			    title: title,
			    content: post_add,
			    boxWidth: '320px',
    			useBootstrap: false
			});
		});
	});

})( jQuery );
