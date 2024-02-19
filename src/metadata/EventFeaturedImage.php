<?php
// Set Namespace
namespace WorthCommitting\Events\Metadata;


class EventFeaturedImage {
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
			'featured_event_image',
			__( 'Featured Event Image', 'comfortcloth_portal' ),
			array( $this, 'render_metabox' ),
			'event',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'nonce_action', 'nonce' );

		// Retrieve an existing value from the database.
		$event_featured_image = get_post_meta( $post->ID, 'event_featured_image', true );

		// Set default values.
		if( empty( $event_featured_image ) ) $event_featured_image = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="event_featured_image" class="event_featured_image_label">' . __( 'Featured Image', 'comfortcloth_portal' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="event_featured_image" name="event_featured_image" class="event_featured_image_field" placeholder="' . esc_attr__( '', 'comfortcloth_portal' ) . '" value="' . esc_attr( $event_featured_image ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$nonce_action = 'nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Sanitize user input.
		$new_event_featured_image = isset( $_POST[ 'event_featured_image' ] ) ? sanitize_text_field( $_POST[ 'event_featured_image' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'event_featured_image', $new_event_featured_image );

	}

}