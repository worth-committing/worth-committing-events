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

add_action( 'cmb2_admin_init', 'ccw_events_event_attended' );
/**
 * Define the metabox and field configurations.
 */
function ccw_events_event_attended() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ccw_events_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'the_event_attended',
		'title'         => __( 'Event Profit/Loss', 'wc_events' ),
		'object_types'  => array( 'event', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


	$group_field_id = $cmb->add_field( array(
		'id'          => 'event_cost',
		'type'        => 'group',
		'description' => '',
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Cost {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Cost', 'cmb2' ),
			'remove_button' => __( 'Remove Cost', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Cost Name',
		'id'   => 'the_cost_name',
		'type' => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Cost Amount',
		'id'   => 'the_cost_amt',
		'type' => 'text_money',
	) );

	$cmb->add_field( array(
		'name' => 'Gross Sales',
		'desc' => 'How much Money Came in?',
		'id' => 'event_gross_sales',
		'type' => 'text_money',
	) );

	$cmb->add_field( array(
		'name' => 'Net Sales',
		'desc' => 'How much Money Did we Make?',
		'id' => 'event_net_sales',
		'type' => 'text_money',
	) );

	$cmb->add_field( array(
		'name' => 'Total profit',
		'desc' => 'How much Money Did we Take Home?',
		'id' => 'event_total_profit',
		'type' => 'text_money',
	) );
}