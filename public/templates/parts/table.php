<?php

$data_			= $meta['_appro_options_table_data'] ? maybe_unserialize( $meta['_appro_options_table_data'][0] ) : null;
$data_ 			= $data_ && !empty( $data_ ) ? array_keys( $data_ ) : null;

$custom_meta 	= $meta['_appro_options_table_custom_meta'] ? $meta['_appro_options_table_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

$table_body = array();

while ( /*$query->have_posts()*/ false ) :

	$id = get_the_ID();
	
	$query->the_post();

	if ( $data_ ) : 

		foreach ( $data_ as $dt ) : 

			switch ( $dt ) {
				case 'ID':
					$table_body[$id][$dt] = "<a href=". get_permalink() .">". $id ."</a>";
					break;

				case 'title':
					$table_body[$id][$dt] =  the_title();
					break;

				case 'thumbnail':
					$table_body[$id][$dt] = the_post_thumbnail( $id, array( 64, 64 ) );
					break;

				case 'excerpt':
					$table_body[$id][$dt] =  the_excerpt();
					break;
				
				default:
					$table_body[$id][$dt] =  $dt;
					break;
			}
		endforeach;
	endif;

	if ( $custom_meta ) : 

		foreach ( $custom_meta as $cm ) : 

			$cm_item = explode( ',', $cm );
			if ( 'date' == $cm_item[2] ) {

				$meta_value = get_post_timestamp( get_the_ID() );
			} elseif ( 'status' == $cm_item[2] ) {
				
				$meta_value = get_post_status( get_the_ID() );
			} elseif ( 'tax' == $cm_item[2] ) {
				
				$meta_value = get_the_terms( get_the_ID(), $cm_item[0] );
			} else {

				$meta_value = get_post_meta( get_the_ID(), $cm_item[0], true );
			}

			$table_body[$id][$cm_item[0]] = get_display_text( $cm_item[2], $meta_value );
		endforeach;
	endif;

endwhile;

require_once( 'old-system.php' );

foreach ( $entries as $entry ) : 

	$id = $entry['id'];

	//$table_body[$id]['ID'] = $id;	
	$table_body[$id]['type'] = $entry[21];	
	$table_body[$id]['nome_paciente'] = $entry[6];
	//$table_body[$id]['date'] = get_display_text( 'date', strtotime( $entry[7] ) );
	$table_body[$id]['actions'] = 'actions-exame';
	$table_body[$id]['status'] = $entry[23];
	$table_body[$id]['url_exame'] = $entry[22];
endforeach;

//echo '<code>';
//print_r( $table_body );
//echo '</code>';

?>
<table class="table is-striped is-hoverable is-fullwidth">
	<thead>
		<tr>
			<?php
			if ( $data_ ) :
			foreach ( $data_ as $dt ) : 
				
			?>
			<th><?php _e( ucfirst( $dt ) ); ?></th>
			<?php
			endforeach;
			endif;

			if ( $custom_meta ) : 

				foreach ( $custom_meta as $cm ) : 

					$cm_item = explode( ',', $cm );
					
				?>
				<th><?php _e( $cm_item[1], 'appro' ); ?></th>
				<?php
				endforeach;
			endif;
			?>
		</tr>
	</thead>
	<tbody>
<?php
if ( $table_body ) : 
foreach ( $table_body as $id => $row ) : 

	?>
		<tr>
		<?php
		foreach ( $row as $key => $col ) : 

			if ( 'url_exame' === $key ) continue;
			
			?>
			<td>
			<?php
			switch ( $key ) : 

				case 'actions':
					echo do_shortcode( '['.$col.' id="'.$id.'" url_exame="'.$row['url_exame'].'"]' );
					break;
				
				default:
					echo $col;
					break;
			endswitch;
			?>
			</td>
			<?php
		endforeach;
		?>
		</tr>
	<?php

endforeach;
endif;
	?>
	</tbody>
</table>
<?php
