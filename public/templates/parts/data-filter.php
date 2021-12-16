<?php

$post_type = get_post_type();
$post_type_obj = get_post_type_object( $post_type );
$pt = strtolower( $post_type_obj->labels->name );

?>
<input type="hidden" id="data_filter-title" value="<?= __( 'Period', 'appro' ); ?>">
<div class="data_filter-content">
	<form id="filter_data" class="form-in-popup" method="GET">
		<label for="data"><?php printf( __( 'Show %s ', 'appro' ), $pt ) ?></label>
		<select id="data" name="data">
			<option value="todos" <?= choose( 'todos' ) ?>><?php _e( 'all', 'appro' ); ?></option>
			<option value="esse_mes" <?= choose( 'esse_mes' ) ?>><?php _e( 'current month', 'appro' ); ?></option>
			<option value="mes_passado" <?= choose( 'mes_passado' ) ?>><?php _e( 'previus month', 'appro' ); ?></option>
			<option value="proximo_mes" <?= choose( 'proximo_mes' ) ?>><?php _e( 'next month', 'appro' ); ?></option>
			<option value="essa_semana" <?= choose( 'essa_semana' ) ?>><?php _e( 'this week', 'appro' ); ?></option>
			<option value="semana_passada" <?= choose( 'semana_passada' ) ?>><?php _e( 'previous week', 'appro' ); ?></option>
			<option value="semana_que_vem" <?= choose( 'semana_que_vem' ) ?>><?php _e( 'next week', 'appro' ); ?></option>
			<option value="hoje" <?= choose( 'hoje' ) ?>><?php _e( 'today', 'appro' ); ?></option>
			<option value="ontem" <?= choose( 'ontem' ) ?>><?php _e( 'yesterday', 'appro' ); ?></option>
			<option value="amanha" <?= choose( 'amanha' ) ?>><?php _e( 'tomorrow', 'appro' ); ?></option>
		</select>
		<button class="button is-link is-fullwidth py-5">
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
			  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
			  <path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5" />
			</svg>
		</button>
	</form>
</div>
<?php
