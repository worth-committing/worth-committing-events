<?php
/**
 * @author        Eric Frisino
 * @copyright     2021 Comfortcloth Weaving LLC
 * @created       2021-08-05 4:51 PM
 * @license       GPL 3
 * @package       comfortcloth_events
 * @subpackage    wordpress
 */

namespace WorthCommitting\Events\WordPress;

// If this file is called directly, bail.
use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use WorthCommitting\Events\Utilities\FormBuilder;

if( !defined( 'WPINC' ) ) {
  die;
}


class AdminEventAjax {
  private Logger $logger;



  public function __construct() {
    if( is_admin() ) {
      add_action( 'admin_footer', [ $this, 'add_event_day_javascript' ] );
      add_action( 'wp_ajax_add_event_day', [ $this, 'add_event_day' ] );
    } else {
      add_action( 'init', [ $this, 'enqueue_js' ] );
      add_action( 'wp_ajax_nopriv_add_event_day', [ $this, 'add_event_day' ] );
    }

    if( wp_get_environment_type() == 'local' ) {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Debug ) );
    } else {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Critical ) );
    }
  }



  public function enqueue_js(): void {
    wp_enqueue_script( 'add-remove-event-day-row', plugin_dir_url( __FILE__ ) . '../scripts/add-event-day.js', [ 'jquery' ], '', TRUE );
  }



  function add_event_day_javascript() { ?>
    <script type="text/javascript" id="add-remove-event-day">
      jQuery(document).ready(function ($) {
        /** WHEN THE ADD ROW BUTTON IS CLICKED... **/
        $("#add_date").click(function () {
          /** set the ajax action we are calling, and the row we are currently on in the form. **/
          const data = {
            'action': 'add_event_day',
            'current_row': $(this).data('current_row'),
            'is_admin': 'admin'
          };

          /** Set Row Numbers. **/
          const add_row_num = $(this).data('current_row') + 1;
          const del_row_num = $(this).data('current_row');

          /** Log the information going to the ajax call & the row numbers. **/
          // console.log(data);
          // console.log("ADD ROW NUMBER : " + add_row_num);
          // console.log("DEL ROW NUMBER : " + del_row_num);

          jQuery.post(ajaxurl, data, function (response) {
            /** Log the html we received back. **/
            // console.log('Got this from the server: ' + response);

            /** Update the button data. **/
            $("#remove_date").data("delete_row", del_row_num);
            $("#add_date").data("current_row", add_row_num);

            /** Check to see if there is more than one row... **/
            if (add_row_num > 1) {
              // if there is, enable the remove row button.
              $("#remove_date").prop("disabled", false)
            }

            /** Add the row to the end of the table. **/
            $(".day-time-input").append(response);
          }); // END AJAX CALL.
        }); // END CLICK #add_date.

        /** WHEN THE REMOVE ROW BUTTON IS CLICKED... **/
        $("#remove_date").click(function () {

          /** Get the row number we are deleting. **/
          const row_num = $("#remove_date").data('delete_row');

          /** Log the row we are removing **/
          // console.log("delete_row : " + row_num );

          /** Build the ID selector for the row we are deleting. **/
          const row_to_del = "#date_row_" + row_num;

          /** Log the ID selector **/
          // console.log("id selector : " + row_to_del );

          /** Remove the row. **/
          $(row_to_del).remove();

          /** Set the row numbers. **/
          const add_row_num = row_num;
          const del_row_num = row_num - 1;

          /** Log the row numbers. **/
          console.log("ADD ROW NUMBER : " + add_row_num);
          console.log("DEL ROW NUMBER : " + del_row_num);

          /** Update the row numbers. **/
          $("#remove_date").data("delete_row", del_row_num);
          $("#add_date").data("current_row", add_row_num);

          /** Check to see if there is more than one row... **/
          if (row_num <= 1) {
            // If there is not, disable the remove button.
            $("#remove_date").prop("disabled", true)
          } // END IF less than 2 rows.
        }); // END CLICK #remove_date.
      }); // END DOCUMENT READY.
    </script> <?php
  }



  #[NoReturn] function add_event_day(): void {
    // global $wpdb; # this is how you get access to the database

    $this->logger->info( "data sent:", $_POST );

    // $this->logger->info( "<- --------------------------------------- ->" );
    // $this->logger->info( "ADD EVENT DAY" );
    // $this->logger->info( "<- --------------------------------------- ->" );
    $new_row_html = (new FormBuilder())->build_event_date_time_row( NULL, $_POST['current_row'], $_POST['is_admin'] );
    // $this->logger->info( "NEW ROW HTML :" );
    // $this->logger->info( $new_row_html );
    // $this->logger->info( "<- --------------------------------------- ->" );

    echo $new_row_html;

    wp_die(); # this is required to terminate immediately and return a proper response
  }
}