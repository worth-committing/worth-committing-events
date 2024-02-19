<?php
/**
 * Manage and display dates of events in different formats.
 *
 * @author       Eric Frisino
 * @created      2021-08-05 11:38 AM
 * @version      1.0.0
 * @license      GPL 3
 * @package      comfortcloth_events
 * @subpackage   utilities
 *
 */

namespace WorthCommitting\Events\Utilities;

// If this file is called directly, bail.
if( !defined( 'WPINC' ) ) die;

class ManageDates {
	public function __construct() {

	}

	function get_formatted_date( $date, $format = NULL ) : string {
		// Check if $format is set.
		if( ! isset( $format ) ) {
			// if not set it to default.
			$format = 'l, F d, Y';
		}
		// Convert the date passed to function to a time string.
		$the_date = strtotime( $date );
		// Return the formatted date.
		return date( $format, $the_date );
	}

	/**
	 * @param $start_date
	 * @param $end_date
	 *
	 * @return false|string
	 */
	function print_readable_date( $start_date, $end_date ) : bool|string {

		// Get month of start date.
		$the_start_date = strtotime( $start_date ); // Convert to string format.
		$the_start_date_month = date( 'M', $the_start_date ); // Get the month name.

		// Get month of end date.
		$the_end_date = strtotime( $end_date ); // Convert to string format.
		$the_end_date_month = date( 'M', $the_end_date ); // Get the month name.

		// Compare dates to see if they are exactly the same
		if( $the_start_date == $the_end_date ) {
			$the_date = date( 'F d, Y', $the_start_date );
			return $the_date;
		} elseif( $the_start_date_month == $the_end_date_month ) { // If they are different, compare start and end months.
			// If they are the same:
			// Get start date.
			$the_start_date_date = date( 'd', $the_start_date ); // Get the start day number.
			// Get end date.
			$the_end_date_date = date( 'd', $the_end_date ); // Get the end day number.
			// Get year of end date.
			$the_end_date_year = date( 'Y', $the_end_date ); // Get the end date year.
			// Format and return output.
			return $the_start_date_month . ' ' . $the_start_date_date . '-' . $the_end_date_date . ', ' . $the_end_date_year;
		} else { // If they are different:
			// Get start date.
			$the_start_date_date = date( 'd', $the_start_date ); // Get the start day number.
			// Get end date.
			$the_end_date_date = date( 'd', $the_end_date ); // Get the end day number.
			// Get year of end date.
			$the_end_date_year = date( 'Y', $the_end_date ); // Get the end date year.
			// Format and return output.
			return $the_start_date_month . ' ' . $the_start_date_date . '-' . $the_end_date_month . ' ' . $the_end_date_date . ', ' . $the_end_date_year;
		}
	}

}