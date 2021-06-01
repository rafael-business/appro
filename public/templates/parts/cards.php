<?php

$custom_meta 	= $meta['_appro_options_cards_custom_meta'] ? $meta['_appro_options_cards_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

?>
<div class="columns is-multiline is-mobile">
<?php

while ( have_posts() ) :
	the_post();

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
		    <div class="content"><?php echo get_the_excerpt(); ?></div>
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
					<dd>
					<?php
					switch ( $cm_item[2] ) : 
						case 'post':
							echo $meta_value['post_title'];
							break;

						case 'user':
							echo $meta_value['display_name'];
							break;

						case 'date':
							echo date( 'd/m/Y à\s H:i', strtotime( $meta_value ) );
							break;
						
						default:
							echo $meta_value;
							break;
					endswitch;
					?>
					</dd>
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
