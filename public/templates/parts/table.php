<?php

$data_			= $meta['_appro_options_table_data'] ? maybe_unserialize( $meta['_appro_options_table_data'][0] ) : null;
$data_ 			= $data && !empty( $data_ ) ? array_keys( $data_ ) : null;

$custom_meta 	= $meta['_appro_options_table_custom_meta'] ? $meta['_appro_options_table_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

add_filter( 'https_ssl_verify', '__return_false' );

function calculate_signature( $string, $private_key ) {
    $hash = hash_hmac( 'sha1', $string, $private_key, true );
    $sig = rawurlencode( base64_encode( $hash ) );
    return $sig;
}
 
$base_url = 'https://telemedic.top/gravityformsapi/';
$api_key = '977c815ee8';
$private_key = '2b98e9a066b4813';
$method  = 'GET';
$route = 'forms/6/entries';
$expires = strtotime( '+60 mins' );
$string_to_sign = sprintf( '%s:%s:%s:%s', $api_key, $method, $route, $expires );
$sig = calculate_signature( $string_to_sign, $private_key );
$page_size = 25;
$offset = 0;
 
$url  = $base_url;
$url .= $route;
$url .= '?api_key=' . $api_key;
$url .= '&signature=' . $sig;
$url .= '&expires=' . $expires;
$url .= '&paging[page_size]=' . $page_size;
$url .= '&paging[offset]=' . $offset;
//echo $url; 

$response = wp_remote_request( $url, array('method' => 'GET' ) );
 
if ( wp_remote_retrieve_response_code( $response ) != 200 || ( empty( wp_remote_retrieve_body( $response ) ) ) ){
    //http request failed
    echo 'There was an error attempting to access the API.';
    die();
}
 
$body_json = wp_remote_retrieve_body( $response );
//results are in the "body" and are json encoded, decode them and put into an array
$body = json_decode( $body_json, true );
 
$data            = $body['response'];
$status_code     = $body['status'];
$total           = 0;
$total_retrieved = 0;
 
if ( $status_code <= 202 ){
    //entries retrieved successfully
    $entries = $data['entries'];
    $status  = $status_code;
    $total              = $data['total_count'];
    $total_retrieved    = count( $entries );

	//echo '<code>';
	//print_r( $total );
	//echo '</code>';
}
else {
    //entry retrieval failed, get error information
    $error_code         = $data['code'];
    $error_message      = $data['message'];
    $error_data         = isset( $data['data'] ) ? $data['data'] : '';
    $status             = $status_code . ' - ' . $error_code . ' ' . $error_message . ' ' . $error_data;
}

?>
<table class="table is-striped is-hoverable is-fullwidth">
	<thead>
		<tr>
			<?php
			foreach ( $data_ as $dt ) : 
				
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

while ( $query->have_posts() ) :
	$query->the_post();

	?>
		<tr>
		<?php
		if ( $data_ ) : 

			foreach ( $data_ as $dt ) : 

				switch ( $dt ) {
					case 'ID':
						?>
						<td><a href="<?php echo get_permalink(); ?>"><?php echo the_ID(); ?></a></td>
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
				if ( 'date' == $cm_item[2] ) {

					$meta_value = get_post_timestamp( get_the_ID() );
				} elseif ( 'status' == $cm_item[2] ) {
					
					$meta_value = get_post_status( get_the_ID() );
				} elseif ( 'tax' == $cm_item[2] ) {
					
					$meta_value = get_the_terms( get_the_ID(), $cm_item[0] );
				} elseif ( 'shortcode' == $cm_item[2] ) {
					
					$meta_value = '['. $cm_item[0] .']';
				} else {

					$meta_value = get_post_meta( get_the_ID(), $cm_item[0], true );
				}
				
			?>
			<td><?= get_display_text( $cm_item[2], $meta_value ); ?></td>
			<?php
			endforeach;
		endif;
		?>
		</tr>
	<?php

endwhile;

	foreach ( $entries as $entry ) : ?>
		<tr>
			<td><?= $entry[21] ?></td>	
			<td><?= $entry[6] ?></td>
			<td><?= get_display_text( 'date', strtotime( $entry[7] ) ); ?></td>
			<td><?= $entry[23] ?></td>
			<td><?= get_display_text( 'shortcode', '[actions-exame]' ); ?></td>
		</tr>
		<?php 
	endforeach; ?>
	</tbody>
</table>
<?php
