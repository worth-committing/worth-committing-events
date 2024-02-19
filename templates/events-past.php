<?php

use WorthCommitting\Events\Utilities\ManageDates;

do_action( 'comfortcloth_before_past_events' );

// WP_Query arguments
$args = array(
	'post_type'              => array( 'event' ),
	'post_status'            => array( 'attended' ),
	'posts_per_page'         => '-1',
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
	echo "<div class='past-events-container'>";
	while ( $query->have_posts() ) {
		$query->the_post();

		// Grab info from the Dates Metabox -->
		$the_event_dates = get_post_meta( get_the_ID(), 'ccw_event_day_date', TRUE );

		$post_ID = get_the_ID();
		$post_class = get_post_class();
		$post_title = get_the_title();
		$post_permalink = esc_url( get_permalink() );
		$print_readable_dates = (new ManageDates() )->print_readable_date( current( $the_event_dates ), end( $the_event_dates ) );
		$the_event_dates = get_post_meta( get_the_ID(), 'ccw_event_day_date', TRUE );

		echo "<article id='post-$post_ID' " . esc_attr( implode( ' ', $post_class ) ) . ">";

		echo "<header class='entry-header'>";
		echo "<h2 class='entry-title'><a href='$post_permalink' rel='bookmark'>$post_title<span style='float:right;'>$print_readable_dates</span></a></h2>";
		echo "</header>";

		// echo "<div class='entry-content'></div>"; # .entry-content

		// echo "<footer class='entry-footer'></footer>";
		echo "</article>";
	}
	echo "</div>"; # past-events-container
} else {
	# TODO: ALLOW USER TO SELECT IMAGE FROM MEDIA SELECTOR -> https://jeroensormani.com/how-to-include-the-wordpress-media-selector-in-your-plugin/
	# TODO: ADD SHORTCODE BEFORE AND AFTER TO ALLOW USER TO ADD THINGS LIKE A NEWSLETTER SIGN UP ETC.
}

// Restore original Post Data
wp_reset_postdata();

do_action( 'comfortcloth_after_past_events' );