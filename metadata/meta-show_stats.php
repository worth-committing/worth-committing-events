<?php
// Set Namespace
namespace comfortcloth_events;

 /**
  * @package       worth-committing-events
  * @subpackage    Metadata
  * @author        Eric Frisino <efrisino@gmail.com>
  * @license       GPL-2.0+
  * @link          http://www.ericfrisino.com
  * @copyright     2017 Eric Frisino
  * @created       3/24/17 10:05 AM
	*/

// If this file is called directly, bail.
if ( ! defined( 'WPINC' ) ) { die; }

add_action( 'cmb2_admin_init', 'ccw_events_event_stats' );
/**
 * Define the metabox and field configurations.
 */
function ccw_events_event_stats() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ccw_events_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'the_event_stats',
		'title'         => __( 'Show Stats', 'wc_events' ),
		'object_types'  => array( 'event', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$cmb->add_field( array(
		'name'    => 'Show Age',
		'desc'    => 'Age of Show in Years',
		'default' => '',
		'id'      => 'show_age',
		'type'    => 'text',
	) );

	$cmb->add_field( array(
		'name'    => 'Expected Attendance',
		'desc'    => '',
		'default' => '',
		'id'      => 'show_attendance',
		'type'    => 'text',
	) );

	$cmb->add_field( array(
		'name'    => 'Total Exhibitors',
		'desc'    => '',
		'default' => '',
		'id'      => 'show_exhibitors_all',
		'type'    => 'text',
	) );

	$cmb->add_field( array(
		'name'    => 'Food Exhibitors',
		'desc'    => '',
		'default' => '',
		'id'      => 'show_exhibitors_food',
		'type'    => 'text',
	) );

	$cmb->add_field( array(
		'name'    => 'Craft Exhibitors',
		'desc'    => '',
		'default' => '',
		'id'      => 'show_exhibitors_craft',
		'type'    => 'text',
	) );

	$cmb->add_field( array(
		'name'    => 'Weaving Exhibitors',
		'desc'    => '',
		'default' => '',
		'id'      => 'show_exhibitors_weaving',
		'type'    => 'text',
	) );
}