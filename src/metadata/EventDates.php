<?php
// Set Namespace
namespace WorthCommitting\Events\Metadata;

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


class EventDates {

  public function __construct() {

    if( is_admin() ) {
      add_action( 'load-post.php', [ $this, 'init_metabox' ] );
      add_action( 'load-post-new.php', [ $this, 'init_metabox' ] );
    }

  }



  public function init_metabox(): void {
    add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
    add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );

  }



  public function add_metabox(): void {

    add_meta_box(
      'ccw_event_dates',
      __( 'Event Dates & Times', 'wc_events' ),
      [ $this, 'ccw_event_dates_render_metabox' ],
      'event',
      'advanced',
      'default'
    );

  }



  public function ccw_event_dates_render_metabox( $post ): void {

    // Add nonce for security and authentication.
    wp_nonce_field( 'ccw_nonce_action', 'ccw_nonce' );

    // Retrieve an existing value from the database.
    $ccw_event_dates = get_post_meta( $post->ID, 'ccw_event_dates', TRUE );

//    if( empty( $ccw_event_day_close ) ) {
//      $ccw_event_cal_link = '';
//    }
    ?>


    <table class="day-time-input form-table">
      <tr>
        <th>Day</th>
        <th>Date</th>
        <th>Time Open</th>
        <th>Time Close</th>
      </tr>

      <?php
      $ccw_event_dates = is_countable( $ccw_event_dates ) ? $ccw_event_dates : [];
      $total_dates = count( $ccw_event_dates );

      if( $total_dates > 0 ) {
        $counter = 0;
        while( $total_dates > $counter ) {

          echo (new FormBuilder())->build_event_date_time_row( $ccw_event_dates[$counter], $counter, 'admin' );

          $counter++;
        }
      } else {
        $counter = 1;
        echo (new FormBuilder())->build_event_date_time_row( NULL, 0, 'admin' );
      }
      ?>
    </table>

    <div style="text-align: right;">
      <div id="remove_date" class="components-button button" data-delete_row="<?php echo $counter - 1; ?>">Delete Last Row</div>
      <div id="add_date" class="components-button button-primary" data-current_row="<?php echo $counter; ?>">Add Date</div>
    </div>

    <!--    <h2>Calendar event link</h2>-->
    <!--    <table style="width: 100%;">-->
    <!--      <tr>-->
    <!--        <th><label for="ccw_event_cal_link">Calendar Link</label></th>-->
    <!--        <td>-->
    <!--          <input type="url" id="ccw_event_cal_link" name="ccw_event_cal_link" class="widefat ccw_event_cal_link" style="width: 100%;" placeholder="--><?php //echo esc_attr__( 'https://comfortclothweaving.com/event-links/[event-name].ics', 'wc_events' ); ?><!--"-->
    <!--                 value="--><?php //echo esc_url( $ccw_event_cal_link ); ?><!--">-->
    <!--        </td>-->
    <!--      </tr>-->
    <!--    </table>-->

  <?php }



  public function save_metabox( $post_id, $post ): void {

    // Add nonce for security and authentication.
    $nonce_name          = $_POST['ccw_nonce'] ?? '';
    $nonce_action        = 'ccw_nonce_action';
    $ccw_event_day_date  = $_POST['ccw_event_day_date'] ?? '';
    $ccw_event_day_open  = $_POST['ccw_event_day_open'] ?? '';
    $ccw_event_day_close = $_POST['ccw_event_day_close'] ?? '';

    // Check if a nonce is set, don't go any further if it is not.
    if( !isset( $nonce_name ) ) {
      return;
    }

    // Check if the nonce is valid, don't fo any further if it is not.
    if( !wp_verify_nonce( $nonce_name, $nonce_action ) ) {
      return;
    }

    // Get, Sanitize, & Save all the data.
    if( is_array( $ccw_event_day_date ) && count( $ccw_event_day_date ) > 0 ) {
      // Initialize array.
      $the_event_dates = [];

      $total_dates = count( $ccw_event_day_date );

      for( $i = 0; $i < $total_dates; $i++ ) {
        $the_event_dates[$i]['date']  = sanitize_text_field( $ccw_event_day_date[$i] );
        $the_event_dates[$i]['open']  = sanitize_text_field( $ccw_event_day_open[$i] );
        $the_event_dates[$i]['close'] = sanitize_text_field( $ccw_event_day_close[$i] );
      }


      if( count( $the_event_dates ) == 0 ) {
        // Set or Update the post meta with this info.
        update_post_meta( $post_id, 'ccw_event_first_day', '' );

        // Set or Update the post meta with this info.
        update_post_meta( $post_id, 'ccw_event_last_day', '' );
      } else {

        // Update the meta field in the database.
        update_post_meta( $post_id, 'ccw_event_day_date', $the_event_dates );

        // Set the first day to a variable without dashes.
        $ccw_event_first_day = str_replace( '-', '', $the_event_dates[0]['date'] );
        // Set or Update the post meta with this info.
        update_post_meta( $post_id, 'ccw_event_first_day', $ccw_event_first_day );

        // Set the last day to a variable without dashes using end()
        $ccw_event_last_day = str_replace( '-', '', $the_event_dates[count( $the_event_dates ) - 1]['date'] );
        // Set or Update the post meta with this info.
        update_post_meta( $post_id, 'ccw_event_last_day', $ccw_event_last_day );
      }
    }

    // Update the event dates meta.
    if( isset( $the_event_dates ) ) {
      update_post_meta( $post_id, 'ccw_event_dates', $the_event_dates );
    }


    // Sanitize and update the events calendar link.
//    $ccw_new_ccw_event_cal_link = isset( $_POST['ccw_event_cal_link'] ) ? esc_url( $_POST['ccw_event_cal_link'] ) : '';
//    update_post_meta( $post_id, 'ccw_event_cal_link', $ccw_new_ccw_event_cal_link );

  }

  public function prep_dates_for_saving( $the_dates ) {

  }

}