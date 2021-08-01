<?php

$post_type = get_post_type();
$get = isset( $_GET ) ? $_GET : null;
$pod_filter = pods( $post_type );
$fields_filter = array();

if ( $custom_meta ) : 

	foreach ( $custom_meta as $cm ) : 

		$cm_item = explode( ',', $cm );
		$field = $cm_item[0];

		$fields_filter[$field] = array( 'default' => isset( $get[$field] ) ? $get[$field] : null );
	endforeach;
endif;

$params_filter = array( 'fields_only' => true, 'fields' => $fields_filter );

?>
<div class="filter">
	<form class="form-in-popup">
		<?= $pod_filter->form( $params_filter ) ?>
		<button class="button is-success is-fullwidth py-5">
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
			  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
			  <circle cx="10" cy="10" r="7" />
			  <line x1="21" y1="21" x2="15" y2="15" />
			</svg>
		</button>
	</form>
</div>
<?php
