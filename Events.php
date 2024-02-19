<?php

/**
 * Plugin Name:   Worth Committing : Events
 * Plugin URI:    http://
 * Description:   Organize and display events on your website.
 * Version:       1.1.0
 * Author:        Eric Frisino
 * Author URI:    https://worthcommitting.dev/docs/wc-events
 * Text Domain:   wc_events
 * Domain Path:   /languages/
 * License:       GPL-2.0+
 * Copyright:     2017 Eric Frisino
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package     worth-committing-events
 * @created     3/21/17 12:25 PM
 * @author      Eric Frisino
 * @license     GPL-2.0+
 * @copyright   2017 Eric Frisino
 */

// Set Namespace
namespace WorthCommitting\Events;

// If this file is called directly, bail.
if( !defined( 'WPINC' ) ) {
  die;
}

// Define the absolute path to the plugin.
define( 'CCEVENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCEVENTS_URI', plugin_dir_url( __FILE__ ) );

// Autoload all libraries included with composer.
require CCEVENTS_PATH . 'vendor/autoload.php';

// Set up Monologger for this file.
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use WorthCommitting\Events\Frontend\FrontendCustomizations;
use WorthCommitting\Events\Metadata\EventAdmission;
use WorthCommitting\Events\Metadata\EventDates;
use WorthCommitting\Events\Metadata\EventFeaturedImage;
use WorthCommitting\Events\Metadata\EventLinks;
use WorthCommitting\Events\MyAccount\ManageEvents;
use WorthCommitting\Events\Utilities\EventsCron;
use WorthCommitting\Events\WordPress\AdminEventAjax;
use WorthCommitting\Events\WordPress\AdminLinkAjax;
use WorthCommitting\Events\WordPress\AllEventsTable;
use WorthCommitting\Events\WordPress\RegisterTaxonomies;
use WorthCommitting\Events\WordPress\RegisterPostTypes;
use WorthCommitting\Events\WordPress\RegisterTaxonomyMetadata;

class Events {

  public Logger $logger;



  public function __construct() {
    // Set the format of the log line.
    if( wp_get_environment_type() == 'local' ) {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Debug ) );
    } else {
      $this->logger = new Logger( 'events' );
      $this->logger->pushHandler( new StreamHandler( CCEVENTS_PATH . 'logs/main.log', Level::Critical ) );
    }

    $this->logger->debug( "Begin Comfortcloth Events Execution\n Plugin Directory: " . CCEVENTS_PATH . "\n Plugin URI: " . CCEVENTS_URI );

    /** Flush Rewrite Rules on Plugin Activation & Deactivation **/
    register_activation_hook( __FILE__, [ $this, 'comfortcloth_events_flush_rewrite_rules' ] );
    register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );
    add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ] );
  }



  function comfortcloth_events_flush_rewrite_rules(): void {
    (new RegisterPostTypes)->register_event(); // call function that registers a custom post type
    flush_rewrite_rules();
  }



  function enqueue_admin(): void {
    # $this->logger->info("Loading ccw_edit_event Script" );
    # wp_enqueue_script( 'wc_add_event_day', CCEVENTS_URI . '/src/scripts/add-event-day.js', ['jquery'], '', 1 );
  }



  function enqueue_frontend(): void {
    //ENQUEUE PLUGIN STYLES
    wp_enqueue_style( 'ccw_events_styles', plugins_url( '/src/styles/events.css', __FILE__ ) );
  }
}

new Events;


//--- --- --- --- --- --- --- --- --- --- --- Load WordPress Admin.
new AllEventsTable;
new AdminEventAjax;
new AdminLinkAjax;


//--- --- --- --- --- --- --- --- --- --- --- Load Event Post Type, metadata, and custom taxonomies.
new RegisterPostTypes;
# Metadata
new EventAdmission;
new EventDates;
# new EventFeaturedImage;
new EventLinks;
# Taxonomies
new RegisterTaxonomies;
new RegisterTaxonomyMetadata;

//--- --- --- --- --- --- --- --- --- --- --- Front End Customizations.
new FrontendCustomizations;
new ManageEvents;


//--- --- --- --- --- --- --- --- --- --- --- Load Cron Job to set events to attended.
new EventsCron;