<?php

$data 			= $meta['_appro_options_table_data'] ? maybe_unserialize( $meta['_appro_options_table_data'][0] ) : null;
$data 			= $data && !empty( $data ) ? array_keys( $data ) : null;

$custom_meta 	= $meta['_appro_options_table_custom_meta'] ? $meta['_appro_options_table_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

?>
<table class="table is-striped is-hoverable is-fullwidth">
	<thead>
		<tr>
			<?php
			foreach ( $data as $dt ) : 
				
			?>
			<th><?php _e( ucfirst( $dt ) ); ?></th>
			<?php
			endforeach;

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

while ( have_posts() ) :
	the_post();

	?>
		<tr>
		<?php
		if ( $data ) : 

			foreach ( $data as $dt ) : 

				switch ( $dt ) {
					case 'ID':
						?>
						<td><?php echo the_ID(); ?></td>
						<?php
						break;

					case 'title':
						?>
						<td><?php echo the_title(); ?></td>
						<?php
						break;

					case 'thumbnail':
						?>
						<td class="image-64"><?php echo the_post_thumbnail( get_the_ID(), array( 64, 64 ) ); ?></td>
						<?php
						break;

					case 'excerpt':
						?>
						<td><?php echo the_excerpt(); ?></td>
						<?php
						break;
					
					default:
						?>
						<td><?php echo $dt; ?></td>
						<?php
						break;
				}
			endforeach;
		endif;

		if ( $custom_meta ) : 

			foreach ( $custom_meta as $cm ) : 

				$cm_item = explode( ',', $cm );
				$meta_value = get_post_meta( get_the_ID(), $cm_item[0], true );
				
			?>
			<td>
				<?php
				switch ( $cm_item[2] ) {
					case 'post':
						echo $meta_value['post_title'];
						break;

					case 'user':
						echo $meta_value['display_name'];
						break;
					
					default:
						echo $meta_value;
						break;
				}
				?>
			</td>
			<?php
			endforeach;
		endif;
		?>
		</tr>
	<?php

endwhile;

?>
	</tbody>
</table>
<?php
