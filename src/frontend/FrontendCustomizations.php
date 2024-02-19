<?php
// Set Namespace
namespace WorthCommitting\Events\Frontend;

// If this file is called directly, bail.
if ( ! defined( 'WPINC' ) ) { die; }

class FrontendCustomizations {
	public function __construct() {
		add_action( 'pre_get_posts', [ $this, 'sort_the_events' ] );
	}

	function sort_the_events( $query ) {
		// Check to make sure were running the main query for the `event` post type not in the admin
		if( ! is_admin() && is_post_type_archive( 'event' ) && $query->is_main_query() ) {
			$query->set( 'meta_key', 'ccw_event_first_day' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'ASC' );
		}
	}
}