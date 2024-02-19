<?php

use WorthCommitting\Events\Utilities\ManageDates;

do_action( 'comfortcloth_before_upcoming_events' );

// WP_Query arguments
$args = array(
	'post_type'              => array( 'event' ),
	'post_status'            => array( 'publish' ),
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();

		// Grab info from the Dates Metabox -->
		$the_event_dates = get_post_meta( $post->ID, 'ccw_event_day_date', TRUE );

		// Grab the current post ID
		$post_ID = get_the_ID();
		// Grab the post's classes.
		$post_class = esc_attr( implode( ' ', get_post_class( 'list-event-container' ) ) );
		// Grab the title fo the post.
		$post_title = get_the_title();
		// Grab the post's URL
		$post_permalink = esc_url( get_permalink() );
		// Format the dates for output.
		$print_readable_dates = (new ManageDates() )->print_readable_date( current( $the_event_dates ), end( $the_event_dates ) );

		// Output the events archive content.
		echo "<article id='post-{$post_ID}' class='{$post_class}'>";

			echo "<header class='entry-header'>";
				echo "<h2 class='entry-title ccw-event-name'><a href='{$post_permalink}' rel='bookmark'>{$post_title}<span class='ccw-event-name-date'>{$print_readable_dates}</span></a></h2>";
			echo "</header>";

			echo "<div class='entry-content'>";
				echo "<div class='event-image'>";
					the_post_thumbnail();
				echo "</div>";

				echo "<div class='event-info-container'>";
					the_content();
					echo "<div><a href='{$post_permalink}' class='ccw_event_archive_more_info_link'>See Full Event Info  <i class='fa fa-hand-o-right'> </i></a></div>";
				echo "</div>";
			echo "</div>"; # .entry-content

			echo "<footer class='entry-footer'></footer>";

		echo "</article>";
	}
} else {
	# TODO: ALLOW USER TO SELECT IMAGE FROM MEDIA SELECTOR -> https://jeroensormani.com/how-to-include-the-wordpress-media-selector-in-your-plugin/
	# TODO: ADD SHORTCODE BEFORE AND AFTER TO ALLOW USER TO ADD THINGS LIKE A NEWSLETTER SIGN UP ETC.
	echo "<img src='" . get_template_directory_uri() . "/images/no-current-shows.jpg' title='No current events' alt='No current events' />";
}

// Restore original Post Data
wp_reset_postdata();

do_action( 'comfortcloth_after_upcoming_events' );