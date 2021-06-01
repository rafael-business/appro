<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php 
			if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					if ( is_day() ) : 
						
						printf( __( 'Daily Archives: %s', 'appro' ), get_the_date() );
					elseif ( is_month() ) : 
						
						printf( __( 'Monthly Archives: %s', 'appro' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'appro' ) ) );
					elseif ( is_year() ) : 
						
						printf( __( 'Yearly Archives: %s', 'appro' ), get_the_date( _x( 'Y', 'yearly archives date format', 'appro' ) ) );
					else : 

						_e( post_type_archive_title() );
					endif;
					?>
				</h1>
			</header><!-- .page-header -->

				<?php
				$templates 	= WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) );
				$id 		= get_appro();
				$meta 		= get_post_meta( $id );
				$layouts 	= maybe_unserialize( $meta['_appro_options_layouts'][0] );
				$layouts 	= array_keys( $layouts );

				?>
				<div class="columns">
					<div class="column">
						<form>
							<input 
								type="search" 
								name="s" 
								placeholder="<?php _e( 'Search...', 'appro' ) ?>" 
								value="<?php echo isset( $_GET['s'] ) ? $_GET['s'] : ''; ?>" 
								class="input is-small" 
							/>
						</form>
					</div>

					<div class="column is-1">
						<?php

						$filter_icon = plugin_dir_url( __DIR__ ) .'img/filter.svg';
						$order_icon = plugin_dir_url( __DIR__ ) .'img/order.svg';

						?>
						<a 
							id="open-filter" 
							class="tab-title has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php _e( 'Filter', 'appro' ); ?>" 
						><img src="<?php echo $filter_icon; ?>" /></a>
						<a 
							id="open-order" 
							class="tab-title has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php _e( 'Order', 'appro' ); ?>" 
						><img src="<?php echo $order_icon; ?>" /></a>
					</div>
					
					<?php
					if ( count( $layouts ) > 1 ) : 
					?>
					<div class="column is-narrow">
					<?php

					foreach ( $layouts as $layout ) : 

						if ( !get_permission( get_appro(), $layout ) ) continue;
						
						$icon = plugin_dir_url( __DIR__ ) .'img/'. $layout .'.svg';
						?>
						<a 
							data-open="<?php echo 'appro-'. $layout; ?>" 
							class="tab-title has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php _e( ucfirst( $layout ), 'appro' ); ?>" 
						>
							<img src="<?php echo $icon; ?>" />
						</a>
						<?php
					endforeach;
					?>
					</div>
					<?php
					endif;
					?>
				</div>
				<?php

				foreach ( $layouts as $layout ) : 

					if ( !get_permission( get_appro(), $layout ) ) continue;

					?>
					<div id="<?php echo 'appro-'. $layout; ?>" class="tab-content">
					<?php require_once $templates . '/parts/'. $layout .'.php'; ?>
					</div>
					<?php
				endforeach;

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );

			endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php

function get_appro(){

	$aps = get_posts( array( 
		'suppress_filters' 	=> false, 
		'post_type'			=> 'appro_archive_page', 
		'post_status'		=> 'publish', 
		'meta_query'		=> array( 
			array( 
	            'key'   => '_appro_options_post_type', 
	            'value' => get_post_type() 
	        )
		)
	));

	// Imediatamente retornando ao get_posts normal
	$posts = get_posts( array( 
		'suppress_filters' 	=> false, 
		'post_type'			=> get_post_type() 		
	));

	$ap 		= $aps[0];
	$id 		= $ap->ID;

	return $id;
}

function get_permission( $id, $layout ){

	$permission = false;
	$aolr   	= get_post_meta( $id, '_appro_options_'. $layout .'_role', true );
	$roles  	= $aolr ? maybe_unserialize( $aolr ) : null;
	$user 		= is_user_logged_in() ? wp_get_current_user() : null;

	if ( $roles ) : 

		if ( !$user ) return false;

    	foreach ( $roles as $role => $status ) : 
    		
    		if ( in_array( $role, (array) $user->roles ) ) : 
		    	
    			return true;
			endif;
    	endforeach;
    else : 

    	return true;
	endif;
}

get_footer();