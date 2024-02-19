<?php
/**
 * @author        Eric Frisino
 * @copyright     2021 Comfortcloth Weaving LLC
 * @created       2021-08-08 3:35 PM
 * @license       GPL 3
 * @package       comfortcloth_events
 * @subpackage    utilites
 */
namespace WorthCommitting\Events\Utilities;

// If this file is called directly, bail.
if( !defined( 'WPINC' ) ) {
	die;
}



class manage_calendar_files {

	private float $version; # VERSION:2.0 -- currently not needed, as version is hardcoded.
	private string $current_time; # Current Date/Time stamp in UTC
	public string $producer_email; # UID:uid1@example.com
	public array $organizer_info; # ORGANIZER;CN=John Doe:MAILTO:john.doe@example.com
	public string $date_time_start; # DTSTART:19970714T170000Z && DTSTAMP:19970714T170000Z
	public string $date_time_stop; # DTEND:19970715T035959Z
	public string $calendar_name; # SUMMARY:Bastille Day Party
	public string $event_name;
	public string $event_url;
	public string $event_description;
	public string $event_location; # LOCATION: Name of the venue.
	public string $lat_long; # GEO:48.85299;2.36885
	public int $event_ID; # Unique 10-digit number generated for each event.

	public function __construct() {

	}



	/**
	 * Build the content of the event calendar to be exported in the international calendar format.
	 *
	 * If you want to add an alarm use the following syntax inside VEVENT:
	 * . "BEGIN:VALARM\n"
	 *  . "ACTION:DISPLAY\n"
	 *  . "DESCRIPTION:New York Sheep & Wool Festival\n"
	 *  . "TRIGGER:-P1D\n"
	 * . "END:VALARM\n"
	 *
	 * @return string Content of the file.
	 */
	public function build_ical_output() : string {
		$file_output = "BEGIN:VCALENDAR\n"
			. "VERSION:2.0\n"
			. "PRODID:-//com.comfortclothweaving//Event Plugin//EN\n" # Who created the event?
			. "X-WR-CALNAME:Comfortcloth\n" # Calendar name.
			. "NAME:Comfortcloth\n" # Calendar name.
			. "CALSCALE:GREGORIAN\n" # What calendar are we using?

			// TODO: DEFAULT TO THIS, ALLOW USER TO SET DIFFERENT TIMEZONE.
			. "BEGIN:VTIMEZONE\n"
				. "TZID:America/New_York\n"
				. "LAST-MODIFIED:20201011T015911Z\n" # Last time this file was updated.
				. "TZURL:http://tzurl.org/zoneinfo-outlook/America/New_York\n"
				. "X-LIC-LOCATION:America/New_York\n"

				. "BEGIN:DAYLIGHT\n"
					. "TZNAME:EDT\n"
					. "TZOFFSETFROM:-0500\n"
					. "TZOFFSETTO:-0400\n"
					. "DTSTART:19700308T020000\n"
					. "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU\n"
				. "END:DAYLIGHT\n"

				. "BEGIN:STANDARD\n"
					. "TZNAME:EST\n"
					. "TZOFFSETFROM:-0400\n"
					. "TZOFFSETTO:-0500\n"
					. "DTSTART:19701101T020000\n"
					. "RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU\n"
				. "END:STANDARD\n"
			. "END:VTIMEZONE\n";


		foreach( $this->event_date_times as $date_time ) {

			$file_output .= "BEGIN:VEVENT\n"
				. "DTSTAMP:{$this->current_time}Z\n"  # Date/Time stamp at time of creation in UTC.
				. "UID:{$this->current_time}Z-{$this->event_ID}@comfortclothweaving.com\n"  # DSTAMP + '-' + Random 9 digit number + '@comfortcloth.com'.
				. "DTSTART;TZID=America/New_York:{$this->date_time_start}\n"  # YYYYMMDD + 'T' + HHMMSS in 24 hour format... 20211016T100000
				. "DTEND;TZID=America/New_York:{$this->date_time_stop}\n"  # YYYYMMDD + 'T' + HHMMSS in 24 hour format... 20211016T170000
				. "SUMMARY:{$this->event_name}\n"  # Name of the event.
				. "URL:{$this->event_name}\n"  # URL of the event.
				. "DESCRIPTION:{$this->event_description}\n"  # Description of the event.
				. "LOCATION:{$this->event_location}\n"  # Name of the location.
				. "GEO:{$this->lat_long}\n"  # Latitude and Longitude of the location... 48.85299;2.36885
			. "END:VEVENT\n";
		}

		$file_output .= "END:VCALENDAR";

		return $file_output;
	}



	/**
	 * @return string
	 */
	public function get_current_time() : string {
		return $this->current_time;
	}



	/**
	 * Set the value of the current time.
	 */
	public function set_current_time( ) : void {
		$this->current_time = gmdate( "Ymd\TH:i:s\Z", now() );
	}



	/**
	 * @return float
	 */
	public function get_version() : float {
		return $this->version;
	}



	/**
	 * @param  float  $version
	 */
	public function set_version( float $version ) : void {
		$this->version = $version;
	}



	/**
	 * TODO SOMEDAY CREATE THIS CLASS. FOR NOW, EVERYTHING IN EST.
	 *
	 * @param $city
	 * @param $state
	 */
	private function set_timezone( $city, $state ) {}



	/**
	 * Create random unique 10-digit number for each event.
	 * @return int
	 */
	private function create_unique_ID() : int {
		return mt_rand( 10000000, 99999999);
	}
}



new manage_calendar_files;