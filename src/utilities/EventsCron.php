<?php
// Set Namespace
namespace WorthCommitting\Events\Utilities;

// Use WordPress Query Class
use WP_Query;

/**
 * Run a daily cronjob to check current events and update any that ended before today's status to 'attended'
 *
 * Class ccw_events_cron
 *
 * @package comfortcloth_events
 */
class EventsCron {
  /**
   * Build the class.
   *
   * events_cron constructor.
   */
  public function __construct() {
    add_action( 'wp', [ $this, 'ccw_events_check_attended_events_cron' ] );
  }



  /**
   * Check currently published events to make sure that they end in the future,
   * if they ended before today, update their status from `publish` to `attended`.
   */
  public function ccw_events_set_events_to_attended(): void {

    // Set WP_Query arguments to grab currently published events.
    $args = [
      'post_type'   => 'event',
      'post_status' => 'publish',
    ];

    // Run the query
    $query = new WP_Query( $args );

    // If we have any events, cycle through them.
    if( $query->have_posts() ) {
      while( $query->have_posts() ) {
        $query->the_post();

        // Get the last day of each event.
        $the_show_close_date = get_post_meta( get_the_ID(), 'ccw_event_last_day', TRUE );
        // Get today's date.
        $todays_date = date( 'Ymd' );

        // Check to see if today's date is greater than the closing date.
        if( $todays_date > $the_show_close_date ) {
          // if it is, set the post status menu...
          $change_post_status_to_attended = [
            'ID'          => get_the_ID(),
            'post_status' => 'attended'
          ];
          // and update the post status to attended.
          wp_update_post( $change_post_status_to_attended );

          // Grab event title, site name and url for the email.
          $event_title = get_the_title( get_the_ID() );
          $site_name   = get_bloginfo( 'name' );
          $site_url    = get_bloginfo( 'url' );

          // Set email recipient.
          $to = "eric@comfortclothweaving.com";
          // Set email subject.
          $subject = "[CRON] {$event_title} Marked Attended";
          // Write the email.
          $body = "<span style='font-family: \"JetBrains Mono\", monospace;'>Hi Eric!<br /><br />
							I took the liberty of marking {$event_title} attended for you.<br /><br />
							Hope all is well.<br /><br />
							With love,<br />
							-Your favorite server<br />
							-- -- -- -- --<br />
							{$site_name}<br />
							{$site_url} - <a href='{$site_url}/wp-admin'>Admin</a></span>";
          // Set email headers
          $headers = [ "Content-Type: text/html; charset=UTF-8" ];

          // Send email.
          wp_mail( $to, $subject, $body, $headers );
        }

      }
    }

    // Restore original Post Data
    wp_reset_postdata();
  }



  /**
   * Schedule Cron Job Event
   */
  function ccw_events_check_attended_events_cron() {
    wp_schedule_event( time(), 'daily', $this->ccw_events_set_events_to_attended() );
  }

}
