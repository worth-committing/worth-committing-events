<?php
// Set Namespace
namespace WorthCommitting\Events\Metadata;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use WorthCommitting\Events\Utilities\FormBuilder;

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
if( !defined( 'WPINC' ) ) {
  die;
}

class EventLinks {

  private Logger $logger;



  public function __construct() {

    if( is_admin() ) {
      add_action( 'load-post.php', [ $this, 'init_metabox' ] );
      add_action( 'load-post-new.php', [ $this, 'init_metabox' ] );

      if( wp_get_environment_type() == 'local' ) {
        $this->logger = new Logger( 'events' );
        $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Debug ) );
      } else {
        $this->logger = new Logger( 'events' );
        $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Critical ) );
      }
    }

  }



  public function init_metabox() {

    add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
    add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );

  }



  public function add_metabox() {

    add_meta_box(
      'ccw_event_links',
      __( 'Event Links', 'wc_events' ),
      [ $this, 'ccw_event_links_render_metabox' ],
      'event',
      'advanced',
      'core'
    );

  }



  /**
   * Render the metabox on the back end to allow inputting links.
   *
   * ccw_event_links array format:
   *   'text'   => 'text to display for link',
   *   'link'   => 'http://tld.com/whatever',
   *   'target' => '_self' || '_blank
   *   [ [link_record_1], [link_record_2], ... ]
   *
   * @param $post
   *
   * @return void
   */
  public function ccw_event_links_render_metabox( $post ): void {

    // Add nonce for security and authentication.
    wp_nonce_field( 'ccw_nonce_action', 'ccw_nonce' );

    // Retrieve an existing value from the database.
    $ccw_event_links = get_post_meta( $post->ID, 'ccw_event_links', TRUE );

    // Set default values.
    if( empty( $ccw_event_links ) ) {
      $ccw_event_links = [];
    }

    // Form fields.
    echo "<table class='link-input form-table'>";
    echo "  <tr>";
    echo "    <th>Link</th>";
    echo "    <th>Text</th>";
    echo "    <th>URL</th>";
    echo "    <th>Target</th>";
    echo "  </tr>";

    // Count existing links.
    $total_links = count( $ccw_event_links );

    // If links exist, step through and display their data...
    // otherwise, create a single empty row, ready for link input.
    if( $total_links > 0 ) {
      $counter = 0;
      while( $total_links > $counter ) {

        echo (new FormBuilder())->build_event_link_row( $ccw_event_links[$counter], $counter, 'admin' );

        $counter++;
      }
    } else {
      $counter = 1;
      echo (new FormBuilder())->build_event_link_row( NULL, 0, 'admin' );
    }
    echo "</table>";

    echo "<div style='text-align: right;'>";
    echo "  <div id='remove_link' class='components-button button' data-delete_row=' " . $counter - 1 . "'>Delete Last Row</div>";
    echo "  <div id='add_link' class='components-button button-primary' data-current_row='$counter'>Add Link</div>";
    echo "</div>";
  }



  public function save_metabox( $post_id, $post ): void {

    // Add nonce for security and authentication.
    $nonce_name   = $_POST['ccw_nonce'] ?? '';
    $nonce_action = 'ccw_nonce_action';

    // Check if a nonce is set.
    if( !isset( $nonce_name ) ) {
      return;
    }

    // Check if a nonce is valid.
    if( !wp_verify_nonce( $nonce_name, $nonce_action ) ) {
      return;
    }

    # $this->logger->info('event links post content: ', $_POST );
    # $this->logger->info('just the link text: ', $_POST['ccw_event_link_text'] );
    # $this->logger->info('just the link url: ', $_POST['ccw_event_link_url'] );
    # $this->logger->info('just the link target: ', $_POST['ccw_event_link_target'] );

    // check to see if any links have been submitted and sanitize if they have.
    $the_links = isset( $_POST['ccw_event_link_text'] ) ? $this->prep_event_links_for_saving( $_POST ) : NULL;


    // if the sanitized content has been set, save it!
    if( !is_null( $the_links ) ) {
      // Update the meta field in the database.
      update_post_meta( $post_id, 'ccw_event_links', $the_links );
    }
  }



  public function prep_event_links_for_saving( $data ): array {
    $logger = new Logger( 'events' );

    $logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Debug ) );

    $the_link_text = $data['ccw_event_link_text'];

    $the_link_url = $data['ccw_event_link_url'];

    $the_link_target = $data['ccw_event_link_target'];

    $total_links = count( $the_link_text );

    $the_links = [];

    for( $i = 0; $i < $total_links; $i++ ) {
      # $logger->info( "this link: $the_link_text[$i] // $the_link_url[$i] // $the_link_target[$i]" );

      $the_links[$i]['text'] = sanitize_text_field( $the_link_text[$i] );

      $the_links[$i]['url'] = sanitize_url( $the_link_url[$i] );

      $the_links[$i]['target'] = $the_link_target[$i] == '_blank' || $the_link_target[$i] == '_self'
        ? $the_link_target[$i]
        : '_blank';
    }

    return $the_links;
  }

}




