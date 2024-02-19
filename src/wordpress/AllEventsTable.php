<?php

// Set Namespace
namespace WorthCommitting\Events\WordPress;

class AllEventsTable {

	public function __construct() {
		add_filter( 'manage_edit-events_columns', array( $this, 'add_event_dates_column' ) );                           // Add Event Dates column.
		add_filter( 'manage_edit-events_sortable_columns', array( $this, 'make_event_dates_column_sortable' ) );        // Make Event Dates column sortable.
		add_action( 'pre_get_posts', array( $this, 'set_event_dates_sort_order' ) );                                    // Let WordPress know how to sort it.
		add_action( 'manage_events_posts_custom_column', array( $this, 'output_events_dates_column_content' ), 10, 2 ); // Output Event Dates column content.
	}



	/**
	 * @param $columns
	 *
	 * @return mixed
	 *
	 * Add Event Dates column to admin table.
	 */
	public function add_event_dates_column( $columns ) {
		$columns["event-dates"] = "Event Dates";

		return $columns;
	}



	/**
	 * @param $columns
	 *
	 * @return mixed
	 *
	 * Make column Sortable.
	 */
	public function make_event_dates_column_sortable( $columns ) {
		$columns["event-dates"] = "Event Dates";

		return $columns;
	}



	/**
	 * @param $query
	 *
	 * Tell WordPress how to sort this column when it loads the admin table for this CPT
	 */
	function set_event_dates_sort_order( $query ) {
		// Test to see if were in the admin, on the events archive, and not in the draft screen.
		if( is_admin() && is_post_type_archive( 'event' ) && $query->get( 'post_status' ) != 'draft' ) {

			// Get the current orderby value.
			$orderby = $query->get( 'orderby' );

			// if orderby is not set, let it to Ascending.
			// This sorts it correctly in the case of a fresh load.
			if( !isset( $_GET['order'] ) ) {
				$query->set( 'order', 'ASC' );
			}

			// Check to see if the orderby is set to the Event Dates column.
			if( $orderby == 'Event Dates' ) {
				// If it is, let the query know how to sort the column.
				$query->set( 'meta_key', 'ccw_event_first_day' );
				$query->set( 'orderby', 'meta_value_num' );
			} else {
				// if it isn't tell the query anyway.
				$query->set( 'meta_key', 'ccw_event_first_day' );
				$query->set( 'orderby', 'meta_value_num' );
			}
		}
	}



	/**
	 * @param $colname  string  Name of the column that is being drawn on the front end.
	 * @param $cptid    int     The post type ID
	 *
	 * Get, format, and output the date in the admin table.
	 */
	public function output_events_dates_column_content( $colname, $cptid ) {
		if( $colname == "event-dates" ) {
			// Get raw first day data.
			$the_event_dates = get_post_meta( $cptid, 'ccw_event_first_day', TRUE );
			// Convert it from a number to a date format.
			$opening_day_date = substr( $the_event_dates, 0, 4 ) . '-' . substr( $the_event_dates, 4, 2 ) . '-' . substr( $the_event_dates, 6 );

			// Check to see that its not the first day.
			if( $opening_day_date == '0000-01-01' ) {
				// If it is, it wasn't set in the post settings so dont echo anything.
				echo '';
			} else {
				// If it isn't, convert the date string to a usable date format.
				$opening_day_date = date_create( $opening_day_date );
				// Then format the date to be nicely readable.
				$opening_day_date = date_format( $opening_day_date, 'F j, Y' );
				// Show the formatted date in it's cell.
				echo $opening_day_date;
			}
		}
	}


}