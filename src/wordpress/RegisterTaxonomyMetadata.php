<?php
/**
 * Register Custom Meta that applies to Taxonomies registered in this plugin.
 *
 * @author       Eric Frisino
 * @created      2021-06-29 4:50 PM
 * @version      1.0.0
 * @license      GPL 3
 * @package      Comfortcloth Events
 * @subpackage   post-tax-meta
 *
 */


namespace WorthCommitting\Events\WordPress;


class RegisterTaxonomyMetadata {
	public function __construct() {

		if ( is_admin() ) {

			add_action( 'venue_add_form_fields',  array( $this, 'create_screen_fields'), 10, 1 );
			add_action( 'venue_edit_form_fields', array( $this, 'edit_screen_fields' ),  10, 2 );

			add_action( 'created_venue', array( $this, 'save_data' ), 10, 1 );
			add_action( 'edited_venue',  array( $this, 'save_data' ), 10, 1 );

		}

	}

	public function create_screen_fields( $taxonomy ) {

		// Set default values.
		$ccw_venue_address = '';

		// Form fields.
		echo '<div class="form-field term-ccw_venue_address-wrap">';
		echo '	<label for="ccw_venue_address">' . __( 'Venue Address', 'wc_events' ) . '</label>';
		echo '	<input type="text" id="ccw_venue_address" name="ccw_venue_address" placeholder="' . esc_attr__( '', 'wc_events' ) . '" value="' . esc_attr( $ccw_venue_address ) . '">';
		echo '	<p class="description">' . __( 'the address of the venue in a way that it can be used in a google maps url', 'wc_events' ) . '</p>';
		echo '</div>';

	}

	public function edit_screen_fields( $term, $taxonomy ) {

		// Retrieve an existing value from the database.
		$ccw_venue_address = get_term_meta( $term->term_id, 'ccw_venue_address', true );

		// Set default values.
		if( empty( $ccw_venue_address ) ) $ccw_venue_address = '';

		// Form fields.
		echo '<tr class="form-field term-ccw_venue_address-wrap">';
		echo '<th scope="row">';
		echo '	<label for="ccw_venue_address">' . __( 'Venue Address', 'wc_events' ) . '</label>';
		echo '</th>';
		echo '<td>';
		echo '	<input type="text" id="ccw_venue_address" name="ccw_venue_address" placeholder="' . esc_attr__( '', 'wc_events' ) . '" value="' . esc_attr( $ccw_venue_address ) . '">';
		echo '	<p class="description">' . __( 'the address of the venue in a way that it can be used in a google maps url', 'wc_events' ) . '</p>';
		echo '</td>';
		echo '</tr>';

	}

	public function save_data( $term_id ) {

		// Sanitize user input.
		$ccw_new_venue_address = isset( $_POST[ 'ccw_venue_address' ] ) ? sanitize_text_field( $_POST[ 'ccw_venue_address' ] ) : '';

		// Update the meta field in the database.
		update_term_meta( $term_id, 'ccw_venue_address', $ccw_new_venue_address );

	}

}