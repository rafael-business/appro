<?php

$start_field 	= $meta['_appro_options_calendar_start_field'] ? $meta['_appro_options_calendar_start_field'][0] : null;
$start_field 	= $start_field && !empty( $start_field ) ? $start_field : null;

$data 			= $meta['_appro_options_calendar_data'] ? maybe_unserialize( $meta['_appro_options_calendar_data'][0] ) : null;
$data 			= $data && !empty( $data ) ? array_keys( $data ) : null;

$custom_meta 	= $meta['_appro_options_calendar_custom_meta'] ? $meta['_appro_options_calendar_custom_meta'][0] : null;
$custom_meta 	= $custom_meta && !empty( $custom_meta ) ? explode( ';', $custom_meta ) : null;

$events = '';

while ( have_posts() ) :
	the_post();

	$start = get_post_meta( get_the_ID(), $start_field, true );
	//print_r( $start );
	//$start = $start && !empty( $start ) ? date( 'c', strtotime( implode( '-', array_reverse( explode( '/', $start ) ) ) ) ) : get_the_date( 'c' );

	$event = array( 
      'id' 				=> get_the_ID(), 
      'start' 			=> $start, 
      //'end' 			=> date( 'c', strtotime( $start .' + 1 hour' ) ), 
      'title' 			=> addslashes( get_the_title() ), 
      'url'				=> get_the_permalink(), 
      'className'		=> 'has-tooltip-arrow event-'. get_the_ID() 
   	);

   	$events .= json_encode( $event ) .',';

endwhile;

?>

<div class="columns is-multiline is-mobile">
	<div class="column is-full">
		<div id="calendar"></div>
	</div>
</div>
<script>
	document.addEventListener( 'DOMContentLoaded', getCalendar );
    function getCalendar() {

    	var events = [<?php echo $events; ?>];
        
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'br', 
            buttonText: {
                today:    'Hoje', 
                month:    'Mensal', 
                week:     'Semanal', 
                day:  	  'Diário'
            },  
            headerToolbar: { 
                start: 'title', 
                center: '', 
                end: 'today dayGridMonth,timeGridWeek,timeGridDay prev,next' 
            }, 
            allDaySlot: false, 
            slotMinTime: '00:00:00', 
            slotMaxTime: '23:59:59', 
            navLinks: 	 true, 
            views: {
                dayGrid: {
                // options apply to dayGridMonth, dayGridWeek, and dayGridDay views
                },
                timeGrid: {
                // options apply to timeGridWeek and timeGridDay views
                },
                week: {
                // options apply to dayGridWeek and timeGridWeek views
                },
                day: {
                // options apply to dayGridDay and timeGridDay views
                }
            }, 
            height: 'auto', 
            events: events, 
            eventDidMount: function( events ) {

			    var a_event = document.querySelectorAll( '.event-' + events.event.id )[0];
			    a_event.setAttribute( 'data-tooltip', events.event.title );
			}
        });

        calendar.render();
    }
</script>
<?php
