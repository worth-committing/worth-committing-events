<?php
// Grab info from the Dates Metabox
$the_event_dates = get_post_meta( $post->ID, 'ccw_event_day_date', TRUE );
$the_event_opens = get_post_meta( $post->ID, 'ccw_event_day_open', TRUE );
$the_event_closes = get_post_meta( $post->ID, 'ccw_event_day_close', TRUE );
$the_event_cal_link = get_post_meta( $post->ID, 'ccw_event_cal_link', TRUE );

// Grab info from the venue taxonomy
$the_venue_info = wp_get_post_terms( $post->ID, 'venue', array( 'fields' => 'all' ) );
$the_venue_info_address = get_term_meta( $the_venue_info[0]->term_id, 'ccw_venue_address', TRUE );

// Grab info from the booth location taxonomy
$the_booth_location = wp_get_post_terms( $post->ID, 'booth_location', array( 'fields' => 'all' ) );

// Grab info from the admission taxonomy
$the_admission_options = wp_get_post_terms( $post->ID, 'admission_options', array( 'fields' => 'all' ) );

// Grab info from the admission Metabox
$the_event_admission = get_post_meta( $post->ID, 'ccw_event_admission', true );

// Grab info from the event links Metabox
$the_event_links = get_post_meta( $post->ID, 'ccw_event_links', TRUE );