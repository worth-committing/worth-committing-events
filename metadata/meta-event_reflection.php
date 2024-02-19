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

add_action( 'cmb2_admin_init', 'ccw_events_event_reflection' );
/**
 * Define the metabox and field configurations.
 */
function ccw_events_event_reflection() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ccw_events_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'the_event_reflection',
		'title'         => __( 'Event Reflection', 'wc_events' ),
		'object_types'  => array( 'event', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$cmb->add_field( array(
		'name'    => 'Review',
		'desc'    => 'This will be published publicly',
		'id'      => 'event_review',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_field( array(
		'name'    => 'Reflection',
		'desc'    => 'This will just be for us',
		'id'      => 'event_reflection',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_field( array(
		'name' => 'Return Next Year?',
		'desc' => '',
		'id'   => 'event_return_again',
		'type' => 'checkbox',
	) );

}