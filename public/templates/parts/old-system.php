<?php

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
$offset = isset( $_GET['pagina'] ) ? ( intval($_GET['pagina']) - 1 ) * $page_size : 0;

$date = $date_query[0];
$start = date( 'Y-m-d', strtotime( $date['after'] ) );
$end = date( 'Y-m-d', strtotime( $date['before'] ) );

$field_filters = array(
    'mode' => 'all'
);

global $current_user; 
if ( $current_user ) {

    $empregador = get_user_meta( $current_user->ID, 'empregador' , true );  
    if ( !empty( $empregador ) ) {
        
        $empreg = array(
            'key'       => '27',
            'operator'  => '=',
            'value'     => $empregador
        );
        array_push( $field_filters, $empreg );
    }
}

if ( isset( $_GET['busca'] ) ) {

    $busca = array(
        'key'       => '6',
        'operator'  => 'contains',
        'value'     => $_GET['busca']
    );
    array_push( $field_filters, $busca );
}

$search = array(
    'field_filters' => $field_filters,
    'start_date'    => $start,
    'end_date'      => $end
);
$search_json = urlencode( json_encode( $search ) );
 
$url  = $base_url;
$url .= $route;
$url .= '?api_key=' . $api_key;
$url .= '&signature=' . $sig;
$url .= '&expires=' . $expires;
$url .= '&paging[page_size]=' . $page_size;
$url .= '&paging[offset]=' . $offset;
$url .= '&search=' . $search_json;
//echo '<a href="'.$url.'">URL</a>'; 

$response = wp_remote_request( $url, array('method' => 'GET' ) );
 
if ( wp_remote_retrieve_response_code( $response ) != 200 || ( empty( wp_remote_retrieve_body( $response ) ) ) ){
    
    echo 'There was an error attempting to access the API.';
    die();
}
 
$body_json = wp_remote_retrieve_body( $response );
$body = json_decode( $body_json, true );
 
$data            = $body['response'];
$status_code     = $body['status'];
$total           = 0;
$total_retrieved = 0;
 
if ( $status_code <= 202 ) {
    
    $entries = $data['entries'];
    $status  = $status_code;
    $total              = $data['total_count'];
    $total_retrieved    = count( $entries );

    $total_pages = ceil($total / $page_size);

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
