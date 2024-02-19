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

add_action( 'cmb2_admin_init', 'ccw_events_event_status' );
/**
 * Define the metabox and field configurations.
 */
function event_status() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ccw_events_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'the_event_status',
		'title'         => __( 'Event Status', 'wc_events' ),
		'object_types'  => array( 'event', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );




// STATUS : INTERESTED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_interested',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Interested', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Interested',
		'id'   => 'event_interested_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_interested_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Application PDF',
		'desc'    => '',
		'id'      => 'event_interested_application',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
		),
	) );



	// STATUS : APPLIED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_applied',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Applied', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Applied',
		'id'   => 'event_applied_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_applied_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Applied On',
		'id'   => 'event_applied_date',
		'type' => 'text_date',
		'date_format' => 'Y-m-d',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Completed Application',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_applied_document',
		'type'    => 'text',
	) );



	// STATUS : ACCEPTED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_accepted',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Accepted', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Accepted',
		'id'   => 'event_accepted_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_accepted_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => 'Accepted On',
		'id'   => 'event_accepted_date',
		'type' => 'text_date',
		'date_format' => 'Y-m-d',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Booth Number',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_accepted_booth_number',
		'type'    => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Booth Location',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_accepted_booth_location',
		'type'    => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Acceptance PDF',
		'desc'    => '',
		'id'      => 'event_accepted_file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Acceptance File',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_accepted_document',
		'type'    => 'text',
	) );



	// STATUS : CONFIRMED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_confirmed',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Confirmed', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Confirmed',
		'id'   => 'event_confirmed_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_confirmed_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Confirmed File',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_confirmed_document',
		'type'    => 'text',
	) );



	// STATUS : MONEY SENT
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_money_sent',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Money Sent', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Money Sent',
		'id'   => 'event_money_sent_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_money_sent_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );



	// STATUS : NEEDS FOLLOWUP
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_needs_followup',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Needs Followup', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Needs Follow Up',
		'id'   => 'event_needs_followup_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_needs_followup_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Follow Up Done',
		'id'   => 'event_needs_followup_done_check',
		'type' => 'checkbox',
	) );



	// STATUS : EVENT FULL
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_event_full',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Event Full', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Event Full',
		'id'   => 'event_event_full_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_event_full_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Reply PDF',
		'desc'    => '',
		'id'      => 'event_event_full_file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Reply File',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_event_full_document',
		'type'    => 'text',
	) );



	// STATUS : DENIED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_denied',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Denied', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Denied',
		'id'   => 'event_denied_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_denied_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Reply PDF',
		'desc'    => '',
		'id'      => 'event_denied_file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Denied File',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_denied_document',
		'type'    => 'text',
	) );


	// STATUS : ATTENDED
	$group_field_id = $cmb->add_field( array(
		'id'          => 'the_event_status_attended',
		'type'        => 'group',
		'description' => '',
		'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Attended', 'wc_events' ), // since version 1.1.4, {#} gets replaced by row number
			// 'add_button'    => __( 'Add Another Entry', 'wc_events' ),
			// 'remove_button' => __( 'Remove Entry', 'wc_events' ),
			// 'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => '',
		'desc' => 'Attended',
		'id'   => 'event_attended_check',
		'type' => 'checkbox',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => '',
		'desc'    => '',
		'id'      => 'event_attended_text',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Attended PDF',
		'desc'    => '',
		'id'      => 'event_attended_file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
		),
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => 'Attended File',
		'desc'    => '',
		'default' => '',
		'id'      => 'event_attended_document',
		'type'    => 'text',
	) );
}