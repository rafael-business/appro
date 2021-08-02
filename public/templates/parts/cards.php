<?php

$custom_meta 	= $meta['_appro_options_cards_custom_meta'] ? $meta['_appro_options_cards_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

?>
<div class="columns is-multiline is-mobile">
<?php

while ( $query->have_posts() ) :
	$query->the_post();

	$post_categories = wp_get_post_categories( get_the_ID() );
	$cats = array();
	$categorias = '';
	     
	foreach( $post_categories as $c ) : 

	    $cat = get_category( $c );
	    $link = get_term_link( $cat->term_id ) .'?post_type='. get_post_type();
	    $categorias .= '<a href="'. $link .'" class="tag mr-1 is-warning">'. $cat->name .'</a>';
	endforeach;

	?>
	<div class="column is-4">
		<div class="card">
		  <div class="card-image">
		  	<a href="<?php echo get_the_permalink(); ?>">
			    <figure class="image is-4by3">
			    	<?php the_post_thumbnail( '400by300' ); ?>
			    </figure>
		    </a>
		  </div>
		  <div class="card-content">
		  	<div class="mb-2"><?php echo '#'. get_the_ID(); ?></div>
		  	<a href="<?php echo get_the_permalink(); ?>">
		  		<h3 class="title is-5 mb-4"><?php echo get_the_title(); ?></h3>
		  	</a>
		    <?php
		    if ( $custom_meta ) : 

			    foreach ( $custom_meta as $cm ) : 

					$cm_item = explode( ',', $cm );
					$meta_value = get_post_meta( get_the_ID(), $cm_item[0], true );
					
				?>
				<dl class="data-with-tags">
					<dt>
						<?php _e( $cm_item[1], 'appro' ); ?>
					</dt>
					<dd><?= get_display_text( $cm_item[2], $meta_value ); ?></dd>
				</dl>
				<?php
				endforeach;
			endif;
			?>
		  </div>
		</div>
	</div>
	<?php

endwhile;

?>
</div>
<?php
