<?php
// Set Namespace
namespace WorthCommitting\Events\Shortcodes;

// If this file is called directly, bail.
use WorthCommitting\Events\Utilities\TemplateLoader;

if ( ! defined( 'WPINC' ) ) {
	die;
}
error_log('template_shortcodes loaded' );
class template_shortcodes {

	public function __construct() {
		error_log('constructing template_shortcodes' );
		// Shortcode for all contacts.
		add_shortcode( 'UPCOMING_EVENTS', [ $this, 'show_upcoming_events' ] );

		// Shortcode to display new contact form.
		add_shortcode( 'PAST_EVENTS', [ $this, 'show_past_events' ] );
	}

	public function show_upcoming_events() {
		error_log( 'UPCOMING_EVENTS');
		( new TemplateLoader() )->get_template_part( 'events', 'upcoming', true );
	}

	public function show_past_events() {
		( new TemplateLoader() )->get_template_part( 'events', 'past', true );
	}
}
new template_shortcodes;