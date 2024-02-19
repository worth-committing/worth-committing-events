<?php
/**
 * @author        Eric Frisino
 * @copyright     2021 Comfortcloth Weaving LLC
 * @created       2021-08-05 5:07 PM
 * @license       GPL 3
 * @package       comfortcloth_events
 * @subpackage    utilities
 */

namespace WorthCommitting\Events\Utilities;

// If this file is called directly, bail.
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

if( !defined( 'WPINC' ) ) {
  die;
}


class FormBuilder {

  private Logger $logger;



  public function __construct() {
    add_action( 'comfortcloth_after_upcoming_events', [ $this, 'build_email_signup_form' ], 10, 'Upcoming+Events' );
    add_action( 'comfortcloth_after_past_events', [ $this, 'build_email_signup_form' ], 10, 'Attended+Events' );

    if( wp_get_environment_type() == 'local' ) {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Debug ) );
    } else {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Critical ) );
    }
  }



  /**
   * @param        $event
   * @param  int   $row_number
   * @param  bool  $admin
   *
   * @return string
   */
  public function build_event_date_time_row( $event, int $row_number = 0, string $location = 'admin' ): string {
    if( !is_null( $event ) ) {
      $data['date']  = $event['date'] ?? "";
      $data['open']  = $event['open'] ?? "";
      $data['close'] = $event['close'] ?? "";
    } else {
      $data['date']  = "";
      $data['open']  = "";
      $data['close'] = "";
    }

//    $this->logger->info("Event: ", $event );
//    $this->logger->info("Row Number: $row_number" );
//    $this->logger->info("is Admin: $admin" );

    $day_number = $row_number + 1;

    if( $location == 'admin' ) {
      $date_time_html = "<tr id='date_row_{$row_number}'>";
      $date_time_html .= "  <th>Day {$day_number}</th>";
      $date_time_html .= "  <td>";
      $date_time_html .= "    <label for='ccw_event_day_date_{$row_number}' class='screen-reader-text'>Day {$day_number} date</label>";
      $date_time_html .= "    <input type='date' id='ccw_event_day_date_{$row_number}' name='ccw_event_day_date[{$row_number}]' class='widefat ccw_event_day_date_field' placeholder='YYYY-MM-DD' value='{$data['date']}'>";
      $date_time_html .= "  </td>";
      $date_time_html .= "  <td>";
      $date_time_html .= "    <label for='ccw_event_day_open_{$row_number}' class='screen-reader-text'>Day {$day_number} open time</label>";
      $date_time_html .= "    <input type='time' id='ccw_event_day_open_{$row_number}' name='ccw_event_day_open[{$row_number}]' class='widefat ccw_event_day_open_field' placeholder='HH:MM:SS' value='{$data['open']}'>";
      $date_time_html .= "  </td>";
      $date_time_html .= "  <td>";
      $date_time_html .= "    <label for='ccw_event_day_close_{$row_number}' class='screen-reader-text'>Day {$day_number} close time</label>";
      $date_time_html .= "    <input type='time' id='ccw_event_day_close_{$row_number}' name='ccw_event_day_close[{$row_number}]' class='widefat ccw_event_day_close_field' placeholder='HH:MM:SS' value='{$data['close']}'>";
      $date_time_html .= "  </td>";
      $date_time_html .= "</tr>";
    } else {
      $date_time_html = "<div id='date_row_{$row_number}' class='w-full col-span-10 field-container' style='margin-bottom:0 !important;'>";
      $date_time_html .= "  <div class='field-label'><label for='day-{$row_number}_date'>Day {$day_number}</label></div>";
      $date_time_html .= "  <div class='field-input'>";
      $date_time_html .= "    <div class='three-columns'>";
      $date_time_html .= "      <input type='date' name='ccw_event_day_date[$row_number]' id='day-{$row_number}_date' class='' style='display: flex;' value='{$data['date']}' />";
      $date_time_html .= "      <input type='time' name='ccw_event_day_open[$row_number]' id='day-{$row_number}_open' class='' style='display: flex;' value='{$data['open']}' />";
      $date_time_html .= "      <input type='time' name='ccw_event_day_close[$row_number]' id='day-{$row_number}_close' class='' style='display: flex;' value='{$data['close']}' />";
      $date_time_html .= "    </div>"; # .three-columns
      $date_time_html .= "  </div>"; # .field-input
      $date_time_html .= "</div>"; # .field-input
    }

    return $date_time_html;
  }



  public function build_email_signup_form( $page ): void {
    echo "<div class='email-signup_events'>
			<h3>Be the first to know about our upcoming events by subscribing to our newsletter!</h3>
			<div id='mc_embed_signup_container_footer' style='margin-top: 25px;'>
				<form id='mc-embedded-subscribe-form'
							class='validate' 
							action='https://comfortclothweaving.us6.list-manage.com/subscribe/post?u=d30134c1d60bfa35bc5af8a75&amp;id=8a01c3884f&amp;SIGNUP={$page}'
							method='post'
							name='mc-embedded-subscribe-form'
							novalidate=''
							target='_blank'>
					<div id='mc_embed_container'>
					
						<div class='mc-field-group fname'>
							<label class='screen-reader-text' for='mce-FNAME'>First Name <span class='asterisk'>*</span></label>
							<input id='mce-FNAME' class='required' name='FNAME' type='text' value='' placeholder='First Name' />
						</div>
						
						<div class='mc-field-group lname'>
							<label class='screen-reader-text' for='mce-LNAME'>Last Name <span class='asterisk'>*</span></label>
							<input id='mce-LNAME' class='required' name='LNAME' type='text' value='' placeholder='Last Name' />
						</div>
						
						<div class='clear'></div>
						
						<div class='mc-field-group email-address'>
							<label class='screen-reader-text' for='mce-EMAIL'>Email Address <span class='asterisk'>*</span></label>
							<input id='mce-EMAIL' class='required email' name='EMAIL' type='email' value='' placeholder='Email Address' />
						</div>
						
						<div class='mc-field-group subscribe'>
							<input id='mc-embedded-subscribe' class='button' name='subscribe' type='submit' value='Subscribe' style='margin: 20px 0 0 25%; width: 50%; ' />
							<p class='small'>We promise not to sell or give your information away to anyone.</p>
						</div>
	
						<div id='mce-responses' class='clear'>
							<input name='EMAILTYPE' type='hidden' value='html' />
							<div id='mce-error-response' class='response' style='display: none;'></div>
							<div id='mce-success-response' class='response' style='display: none;'></div>
						</div>
	
						<div style='position: absolute; left: -5000px;' aria-hidden='true'><input tabindex='-1' name='b_d30134c1d60bfa35bc5af8a75_8a01c3884f' type='text' value='' /></div>
	
						<div class='clear'></div>
	
					</div>
	
					<div class='clear'></div>
				</form>
			</div>
		</div>";
  }



  public function build_event_link_row( $event, int $row_number = 0, string $location = 'admin' ): string {
    $data['text']   = $event['text'] ?? "";
    $data['url']    = $event['url'] ?? "";
    $data['target'] = $event['target'] ?? "";

    $blank_selected = $data['target'] == "_blank" ? "selected='selected'" : "";
    $self_selected  = $data['target'] == "_self" ? "selected='selected'" : "";

    $link_number = $row_number + 1;

    if( $location == 'admin' ) {
      $link_html = "<tr id='link_row_{$row_number}'>";
      $link_html .= "  <th>Link {$link_number}</th>";
      $link_html .= "  <td>";
      $link_html .= "    <label for='ccw_event_link_{$row_number}' class='screen-reader-text'>Link {$link_number} text</label>";
      $link_html .= "    <input type='text' id='ccw_event_link_{$row_number}' name='ccw_event_link_text[{$row_number}]' class='widefat ccw_event_link_field' value='{$data['text']}'>";
      $link_html .= "  </td>";
      $link_html .= "  <td>";
      $link_html .= "    <label for='ccw_event_url_{$row_number}' class='screen-reader-text'>Link {$link_number} url</label>";
      $link_html .= "    <input type='url' id='ccw_event_url_{$row_number}' name='ccw_event_link_url[{$row_number}]' class='widefat ccw_event_url_field' value='{$data['url']}'>";
      $link_html .= "  </td>";
      $link_html .= "  <td>";
      $link_html .= "    <label for='ccw_event_target_{$row_number}' class='screen-reader-text'>Link {$link_number} url</label>";
      $link_html .= "    <select id='ccw_event_target_{$row_number}' name='ccw_event_link_target[{$row_number}]' class='widefat ccw_event_target_field'>";
      $link_html .= "      <option value='_blank' $blank_selected >New Tab/Window</option>";
      $link_html .= "      <option value='_self' $self_selected >This Tab/Window</option>";
      $link_html .= "    </select>";
      $link_html .= "  </td>";
      $link_html .= "</tr>";
    } else {
      $link_html = "<div id='link_row_{$row_number}' class='w-full col-span-10 field-container' style='margin-bottom:0 !important;'>";
      $link_html .= "  <div class='field-label'><label for='ccw_event_link_{$row_number}'>Link {$link_number}</label></div>";
      $link_html .= "  <div class='field-input'>";
      $link_html .= "    <div class='three-columns'>";
      $link_html .= "      <input type='url' name='ccw_event_link_url[{$row_number}]' id='ccw_event_url_{$row_number}' style='display: flex;' class='col-span-3' placeholder='https://' value='{$data['url']}' />";
      $link_html .= "      <input type='text' name='ccw_event_link_text[{$row_number}]' id='ccw_event_link_{$row_number}' style='display: flex;' class='col-span-2' placeholder='Link Text' value='{$data['text']}' />";
      $link_html .= "      <select id='ccw_event_target_{$row_number}' name='ccw_event_link_target[{$row_number}]' class='widefat ccw_event_linnk_target'>";
      $link_html .= "        <option value='_blank' $blank_selected >New Tab/Window</option>";
      $link_html .= "        <option value='_self' $self_selected >This Tab/Window</option>";
      $link_html .= "      </select>";
      $link_html .= "    </div>"; # .three-columns
      $link_html .= "  </div>"; # .field-input
      $link_html .= "</div>"; # .link_row_{row number}
    }

    return $link_html;
  }
}