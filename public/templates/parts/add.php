<?php

$post_type = get_post_type();
$post_type_obj = get_post_type_object( $post_type );
$get = isset( $_GET ) ? $_GET : null;

?>
<input type="hidden" id="post-add-title" value="<?= $post_type_obj->labels->singular_name; ?>">
<div class="post-add">
	<div class="form-in-popup">
		<?php echo do_shortcode( '[add-medical-reports]' ); ?>
	</div>
</div>
<?php
