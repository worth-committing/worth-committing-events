<?php
// Set Namespace
namespace WorthCommitting\Events\Metadata;

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

class EventAdmission {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'ccw_event_admission',
			__( 'Event Admission', 'wc_events' ),
			array( $this, 'ccw_event_admission_render_metabox' ),
			'event',
			'advanced',
			'core'
		);

	}

	public function ccw_event_admission_render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'ccw_nonce_action', 'ccw_nonce' );

		// Retrieve an existing value from the database.
		$ccw_event_admission = get_post_meta( $post->ID, 'ccw_event_admission', true );

		// Set default values.
		if( empty( $ccw_event_admission ) ) $ccw_event_admission = '';

		// Form fields.

		echo '<textarea id="ccw_event_admission" name="ccw_event_admission" class="ccw_event_admission_field" style="width: 100%; height: 150px;" placeholder="' . esc_attr__( '', 'wc_events' ) . '">' . $ccw_event_admission . '</textarea>';

		echo 'Allowed Tags: &lt;a href="" title="" target=""&gt;&nbsp;&lt;/a&gt;, &lt;br /&gt;, &lt;em&gt;&nbsp;&lt;/em&gt;, &amp; &lt;strong&gt;&nbsp;&lt;/strong&gt;. ';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['ccw_nonce'] ) ? $_POST['ccw_nonce'] : '';
		$nonce_action = 'ccw_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Sanitize user input.
		$allowed_kses = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'target' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		$ccw_new_event_admission = isset( $_POST[ 'ccw_event_admission' ] ) ? wp_kses( $_POST[ 'ccw_event_admission' ], $allowed_kses ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'ccw_event_admission', $ccw_new_event_admission );

	}

}