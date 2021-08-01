<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php 

			$templates 	= WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) );
			$id 		= get_appro();
			$meta 		= get_post_meta( $id );
			$layouts 	= maybe_unserialize( $meta['_appro_options_layouts'][0] );
			$layouts 	= array_keys( $layouts );

			// Query
			$has_busca = isset( $_GET['s'] ) ? true : false;
			$busca = $has_busca ? $_GET['s'] : '';
			$gets = isset( $_GET ) ? $_GET : null;
			unset( $gets['busca'] );
			$meta_query = array();
			$i = 0;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			foreach ( $gets as $key => $value ) : 
				
				if ( !$value ) continue;
				
				$meta_query[$i]['key'] = $key;
				$meta_query[$i]['value'] = $value;
				$meta_query[$i]['compare'] = 'LIKE';
				$i++;
			endforeach;

			$args = array(
				'post_type' 		=> get_post_type(),
				'post_status'		=> 'publish',
				'posts_per_page' 	=> 2,
				'paged'     		=> $paged,
				's'					=> $busca,
				'meta_query'		=> $meta_query
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) : ?>

				<div class="columns">
					<header class="column page-header">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive icon-archive-title" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
						  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
						  <rect x="3" y="4" width="18" height="4" rx="2" />
						  <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
						  <line x1="10" y1="12" x2="14" y2="12" />
						</svg>
						<div class="mb-0 archive-title">
							<span class="has-text-black title is-5"><?php _e( 'Archive', 'appro' ); ?></span>
							<span class="has-text-grey-lighter title is-5">&nbsp;/&nbsp;</span>
							<span class="title is-5"><?php _e( post_type_archive_title() ); ?></span>
						</div>
						<?php
						if ( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) : 
						?>
						<div class="tags has-addons mt-1">
							<span class="tag is-link is-light"><?php echo isset( $_GET['s'] ) ? $_GET['s'] : ''; ?></span>
							<a href="<?php echo get_post_type_archive_link( get_post_type() ); ?>" class="tag is-delete is-danger is-light"></a>
						</div>
						<?php
						endif;
						?>
					</header><!-- .page-header -->

					<div class="column">
						<form class="form-search">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-search icon-input-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
							  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
							  <circle cx="15" cy="15" r="4" />
							  <path d="M18.5 18.5l2.5 2.5" />
							  <path d="M4 6h16" />
							  <path d="M4 12h4" />
							  <path d="M4 18h4" />
							</svg>
							<input 
								type="search" 
								name="s" 
								placeholder="<?php _e( 'Search...', 'appro' ) ?>" 
								value="<?php echo isset( $_GET['s'] ) ? $_GET['s'] : ''; ?>" 
								class="input pl-6" 
							/>
						</form>
					</div>

					<div class="column is-narrow">
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
						<!--
						<a 
							id="open-order" 
							class="tab-title has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php _e( 'Order', 'appro' ); ?>" 
						><img src="<?php echo $order_icon; ?>" /></a>
						-->
					</div>
					
					<?php

					$str['table'] 		= __( 'Table', 'appro' );
					$str['cards'] 		= __( 'Cards', 'appro' );
					$str['calendar'] 	= __( 'Calendar', 'appro' );

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
							class="tab-title tab-title-layout has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php echo $str[$layout]; ?>" 
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

					<!--
					<div class="column is-narrow">
						<?php

						$export_icon = plugin_dir_url( __DIR__ ) .'img/table-export.svg';

						?>
						<a 
							id="open-export" 
							class="tab-title has-tooltip-arrow" 
							href="#" 
							data-tooltip="<?php _e( 'Export', 'appro' ); ?>" 
						><img src="<?php echo $export_icon; ?>" /></a>
					</div>
					-->
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

				get_bulma_pagination( $query );

				require_once $templates . '/parts/filter.php';

			else :
				
				require_once $templates . '/parts/not-found.php';

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

function get_display_text( $key, $value ){

	switch ( $key ) : 

		case 'post' : 
			return $value['post_title'];
			break;

		case 'user' : 
			return $value['display_name'];
			break;

		case 'user_email' : 
			return get_user_by_email( $value )->display_name;
			break;

		case 'date' : 
			return date( 'd/m/Y à\s H:i', strtotime( $value ) );
			break;

		case 'tax' : 
			return $value['name'];
			break;
		
		default : 
			return $value;
			break;
	endswitch;
}


function get_bulma_pagination( $query ) {

	$big = 999999999;
	$total_pages = $query->max_num_pages;

	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $total_pages,
		'prev_next' => false,
		'type'  => 'array',
		'prev_next'   => true,
		'prev_text'    => __( 'Previous' ),
		'next_text'    => __( 'Next')
	));

	if ( is_array( $pages ) ) : 

		$paged = get_query_var('paged') == 0 ? 1 : get_query_var( 'paged' );

  	?>
  	<nav class="pagination is-centered mt-5" role="navigation" aria-label="pagination">
  		<a class="pagination-previous" href="<?php echo get_previous_posts_page_link(); ?>" <?php echo $paged == 1 ? 'disabled' : ''; ?>><?php _e( 'Previous' ); ?></a>
  		<a class="pagination-next" href="<?php echo get_next_posts_page_link(); ?>" <?php echo $paged < $total_pages ? '' : 'disabled'; ?>><?php _e( 'Next'); ?></a>
  		<ul class="pagination-list">
  	<?php

    for ( $i = 1; $i <= $total_pages; $i++ ) : 

		?>
			<li><a class="pagination-link <?php echo $i == $paged ? 'is-current' : ''; ?>" href="<?php echo get_pagenum_link($i); ?>"><?php echo $i; ?></a></li>
		<?php
    endfor;

    ?>
		</ul>
    </nav>
    <?php
	endif;
}

get_footer();