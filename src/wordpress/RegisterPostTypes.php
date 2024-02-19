<?php
// Set Namespace
namespace WorthCommitting\Events\WordPress;

/**
 * @package       Comfortcloth Weaving Events
 * @subpackage    Custom Post Types
 * @author        Eric Frisino <efrisino@gmail.com>
 * @license       GPL-2.0+
 * @link          http://www.ericfrisino.com
 * @copyright     2017 Eric Frisino
 * @created       3/21/17 12:30 PM
 *
 */

// If this file is called directly, bail.
if( !defined( 'WPINC' ) ) {
  die;
}

class RegisterPostTypes {
  public function __construct() {
    add_action( 'init', [ $this, 'register_event' ], 0 );
  }



  function register_event(): void {

    $labels       = [
      'name'                  => _x( 'Events', 'Post Type General Name', 'wc_events' ),
      'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'wc_events' ),
      'menu_name'             => __( 'Events', 'wc_events' ),
      'name_admin_bar'        => __( 'Events', 'wc_events' ),
      'archives'              => __( 'Event Archives', 'wc_events' ),
      'attributes'            => __( 'Event Attributes', 'wc_events' ),
      'parent_item_colon'     => __( 'Parent Event:', 'wc_events' ),
      'all_items'             => __( 'All Events', 'wc_events' ),
      'add_new_item'          => __( 'Add New Event', 'wc_events' ),
      'add_new'               => __( 'Add New', 'wc_events' ),
      'new_item'              => __( 'New Event', 'wc_events' ),
      'edit_item'             => __( 'Edit Event', 'wc_events' ),
      'update_item'           => __( 'Update Event', 'wc_events' ),
      'view_item'             => __( 'View Event', 'wc_events' ),
      'view_items'            => __( 'View Events', 'wc_events' ),
      'search_items'          => __( 'Search Event', 'wc_events' ),
      'not_found'             => __( 'Not found', 'wc_events' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'wc_events' ),
      'featured_image'        => __( 'Featured Image', 'wc_events' ),
      'set_featured_image'    => __( 'Set featured image', 'wc_events' ),
      'remove_featured_image' => __( 'Remove featured image', 'wc_events' ),
      'use_featured_image'    => __( 'Use as featured image', 'wc_events' ),
      'insert_into_item'      => __( 'Insert into event', 'wc_events' ),
      'uploaded_to_this_item' => __( 'Uploaded to this event', 'wc_events' ),
      'items_list'            => __( 'Events list', 'wc_events' ),
      'items_list_navigation' => __( 'Events list navigation', 'wc_events' ),
      'filter_items_list'     => __( 'Filter events list', 'wc_events' ),
    ];
    $capabilities = [
      'edit_post'          => 'edit_event',
      'read_post'          => 'read_event',
      'delete_post'        => 'delete_event',
      'edit_posts'         => 'edit_posts',
      'edit_others_posts'  => 'edit_others_events',
      'publish_posts'      => 'publish_events',
      'read_private_posts' => 'read_private_events',
    ];
    $args         = [
      'label'               => __( 'Events', 'wc_events' ),
      'description'         => __( 'Events that we attend, or want to attend', 'wc_events' ),
      'labels'              => $labels,
      'supports'            => [ 'title', 'editor', 'author', 'thumbnail', ],
      'hierarchical'        => TRUE,
      'public'              => TRUE,
      'event_ui'            => TRUE,
      'event_in_menu'       => TRUE,
      'menu_position'       => 19,
      'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="calendar-star" class="svg-inline--fa fa-calendar-star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M256.9 290.7L309.7 298.4C318.2 299.6 321.6 310.1 315.4 316.1L277.3 353.3L286.3 405.8C287.7 414.3 278.8 420.8 271.2 416.8L224 392L176.8 416.8C169.2 420.8 160.3 414.3 161.7 405.8L170.7 353.3L132.6 316.1C126.4 310.1 129.8 299.6 138.3 298.4L191.1 290.7L214.7 242.9C218.5 235.2 229.5 235.2 233.3 242.9L256.9 290.7zM128 0C141.3 0 152 10.75 152 24V64H296V24C296 10.75 306.7 0 320 0C333.3 0 344 10.75 344 24V64H384C419.3 64 448 92.65 448 128V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V128C0 92.65 28.65 64 64 64H104V24C104 10.75 114.7 0 128 0zM400 192H48V448C48 456.8 55.16 464 64 464H384C392.8 464 400 456.8 400 448V192z"></path></svg>' ),
      'event_in_admin_bar'  => TRUE,
      'event_in_nav_menus'  => TRUE,
      'can_export'          => TRUE,
      'has_archive'         => TRUE,
      'exclude_from_search' => TRUE,
      'publicly_queryable'  => TRUE,
      # 'capabilities'        => $capabilities,
      'capability_type'     => ['worth_committing_event','worth_committing_events'],
      'show_in_rest'        => FALSE,
    ];
    register_post_type( 'event', $args );

  }
}