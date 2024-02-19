<?php
/**
 * Register all custom taxonomies for this plugin.
 *
 * @author       Eric Frisino
 * @created      2021-06-29 4:18 PM
 * @version      1.0.0
 * @license      GPL 3
 * @package      Comfortcloth Events
 * @subpackage   post-tax-meta
 *
 */


namespace WorthCommitting\Events\WordPress;

class RegisterTaxonomies {
  public function __construct() {
    add_action( 'init', [ $this, 'ccw_admission_options' ], 0 );
    # add_action( 'init', [ $this, 'ccw_booth_location' ], 0 );
    # add_action( 'init', [ $this, 'ccw_event_type' ], 0 );
    # add_action( 'init', [ $this, 'ccw_insurance_requirement' ], 0 );
    # add_action( 'init', [ $this, 'ccw_juried' ], 0 );
    # add_action( 'init', [ $this, 'ccw_jury_categories' ], 0 );
    # add_action( 'init', [ $this, 'ccw_product_types' ], 0 );
    # add_action( 'init', [ $this, 'ccw_vendor_categories' ], 0 );
    add_action( 'init', [ $this, 'ccw_venue' ], 0 );
    add_action( 'init', [ $this, 'featured_event' ], 0 );

    // POSSIBLE ADDITIONAL TAXONOMIES FOR THE FUTURE
    # -> rating           # 1-10, Shown in half increments of 5 stars
    # -> product_types    # Art, Fine Art, Craft, Fine Craft, Film, Flea, Commercial/Retail, Homegrown Products (Farmers Market), Corporate/Information, Antiques/Collectibles
    # -> contact_promoter # Meta: Company, Contact Name, Address, City, State, Zip, Email, Phone, Web
    # -> contact_director # Meta: Company, Contact Name, Address, City, State, Zip, Email, Phone, Web
    # -> contact_other    # Meta: Company, Contact Name, Contact Type, Address, City, State, Zip, Email, Phone, Web
  }



  /**
   * Admission Option; paid, free, how much, etc.
   */
  function ccw_admission_options(): void {

    $labels       = [
      'name'                       => _x( 'Paid Admission?', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Admission Option', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Admission', 'wc_events' ),
      'all_items'                  => __( 'All Admissions', 'wc_events' ),
      'parent_item'                => __( 'Parent Admission Option', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Admission Option:', 'wc_events' ),
      'new_item_name'              => __( 'New Admission Option Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Admission Option', 'wc_events' ),
      'edit_item'                  => __( 'Edit Admission Option', 'wc_events' ),
      'update_item'                => __( 'Update Admission Option', 'wc_events' ),
      'view_item'                  => __( 'View Admission Option', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate admission options with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove admission option', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Admission Options', 'wc_events' ),
      'search_items'               => __( 'Search Admission Options', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No locations', 'wc_events' ),
      'items_list'                 => __( 'Admission Options list', 'wc_events' ),
      'items_list_navigation'      => __( 'Admission Options list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_event_admission',
      'edit_terms'   => 'edit_event_admission',
      'delete_terms' => 'delete_event_admission',
      'assign_terms' => 'edit_posts',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      'capabilities'      => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'admission_options', [ 'event' ], $args );
  }



  /**
   * Booth location; building, number, etc.
   */
  function ccw_booth_location(): void {

    $labels       = [
      'name'                       => _x( 'Booth Locations', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Booth Location', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Booth Location', 'wc_events' ),
      'all_items'                  => __( 'All Booth Locations', 'wc_events' ),
      'parent_item'                => __( 'Parent Booth Location', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Booth Location:', 'wc_events' ),
      'new_item_name'              => __( 'New Booth Location Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Booth Location', 'wc_events' ),
      'edit_item'                  => __( 'Edit Booth Location', 'wc_events' ),
      'update_item'                => __( 'Update Booth Location', 'wc_events' ),
      'view_item'                  => __( 'View Booth Location', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate booth locations with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove booth location', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Booth Locations', 'wc_events' ),
      'search_items'               => __( 'Search Booth Locations', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No booth locations', 'wc_events' ),
      'items_list'                 => __( 'Booth Locations list', 'wc_events' ),
      'items_list_navigation'      => __( 'Booth Locations list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'booth_location', [ 'event' ], $args );

  }



  /**
   * Type of event; art show, conference, craft show, lecture, etc.
   */
  function ccw_event_type(): void {

    $labels       = [
      'name'                       => _x( 'Event Type', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Event Type', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Event Type', 'wc_events' ),
      'all_items'                  => __( 'All Event Types', 'wc_events' ),
      'parent_item'                => __( 'Parent Event Type', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Event Type:', 'wc_events' ),
      'new_item_name'              => __( 'New Event Type Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Event Type', 'wc_events' ),
      'edit_item'                  => __( 'Edit Event Type', 'wc_events' ),
      'update_item'                => __( 'Update Event Type', 'wc_events' ),
      'view_item'                  => __( 'View Event Type', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate event types with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove event type', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Event Types', 'wc_events' ),
      'search_items'               => __( 'Search Event Types', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No event types', 'wc_events' ),
      'items_list'                 => __( 'Event Types list', 'wc_events' ),
      'items_list_navigation'      => __( 'Event Types list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'event_type', [ 'event' ], $args );
  }



  /**
   * Do they need proof of insurance? A simple yes or no.
   */
  function ccw_insurance_requirement(): void {

    $labels       = [
      'name'                       => _x( 'Insurance Required?', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Insurance Requirement', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Insurance', 'wc_events' ),
      'all_items'                  => __( 'All Requirements', 'wc_events' ),
      'parent_item'                => __( 'Parent Insurance Requirement', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Insurance Requirement:', 'wc_events' ),
      'new_item_name'              => __( 'New Insurance Requirement', 'wc_events' ),
      'add_new_item'               => __( 'Add New Insurance Requirement', 'wc_events' ),
      'edit_item'                  => __( 'Edit Insurance Requirement', 'wc_events' ),
      'update_item'                => __( 'Update Insurance Requirement', 'wc_events' ),
      'view_item'                  => __( 'View Insurance Requirement', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate insurance requirements with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove insurance requirement', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Insurance Requirements', 'wc_events' ),
      'search_items'               => __( 'Search Insurance Requirements', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No insurance requirements', 'wc_events' ),
      'items_list'                 => __( 'Insurance Requirement list', 'wc_events' ),
      'items_list_navigation'      => __( 'Insurance Requirement list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'insurance_requirement', [ 'event' ], $args );
  }



  /**
   * Is the show juried? A simple yes or no.
   */
  function ccw_juried(): void {

    $labels       = [
      'name'                       => _x( 'Juried', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Juried', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Juried', 'wc_events' ),
      'all_items'                  => __( 'All Juried Options', 'wc_events' ),
      'parent_item'                => __( 'Parent Jury', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Jury:', 'wc_events' ),
      'new_item_name'              => __( 'New Juried Option', 'wc_events' ),
      'add_new_item'               => __( 'Add New Juried Option', 'wc_events' ),
      'edit_item'                  => __( 'Edit Juried Option', 'wc_events' ),
      'update_item'                => __( 'Update Juried Option', 'wc_events' ),
      'view_item'                  => __( 'View Juried Option', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate juries with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove juried Option', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Juried Options', 'wc_events' ),
      'search_items'               => __( 'Search Juried Options', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No juried Options', 'wc_events' ),
      'items_list'                 => __( 'Juried Option :ist', 'wc_events' ),
      'items_list_navigation'      => __( 'Juried List Navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'juried', [ 'event' ], $args );
  }



  /**
   * What categories were we juried into? Weaving, Pottery, Jewelery, etc.
   */
  function ccw_jury_categories(): void {

    $labels       = [
      'name'                       => _x( 'Jury Category', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Jury Category', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Jury Category', 'wc_events' ),
      'all_items'                  => __( 'All Jury Categories', 'wc_events' ),
      'parent_item'                => __( 'Parent Jury Category', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Jury Category:', 'wc_events' ),
      'new_item_name'              => __( 'New Jury Category Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Jury Category', 'wc_events' ),
      'edit_item'                  => __( 'Edit Jury Category', 'wc_events' ),
      'update_item'                => __( 'Update Jury Category', 'wc_events' ),
      'view_item'                  => __( 'View Jury Category', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate jury categories with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove jury category', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Jury Categories', 'wc_events' ),
      'search_items'               => __( 'Search Jury Categories', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No jury categories', 'wc_events' ),
      'items_list'                 => __( 'Jury Categories list', 'wc_events' ),
      'items_list_navigation'      => __( 'Jury Categories list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'jury_categories', [ 'event' ], $args );

  }



  function ccw_product_types(): void {

    $labels       = [
      'name'                       => _x( 'Product Types', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Product type', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Product type', 'wc_events' ),
      'all_items'                  => __( 'All Product types', 'wc_events' ),
      'parent_item'                => __( 'Parent Product type', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Product type:', 'wc_events' ),
      'new_item_name'              => __( 'New Product type Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Product type', 'wc_events' ),
      'edit_item'                  => __( 'Edit Product type', 'wc_events' ),
      'update_item'                => __( 'Update Product type', 'wc_events' ),
      'view_item'                  => __( 'View Product type', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate product types with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove product type', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Product types', 'wc_events' ),
      'search_items'               => __( 'Search Product types', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No product types', 'wc_events' ),
      'items_list'                 => __( 'Product types list', 'wc_events' ),
      'items_list_navigation'      => __( 'Product types list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'product_types', [ 'event' ], $args );

  }



  /**
   * Are we selling resale, wholesale, or both?
   */
  function ccw_vendor_categories(): void {

    $labels       = [
      'name'                       => _x( 'Vendor Categories', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Vendor Category', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Vendor Category', 'wc_events' ),
      'all_items'                  => __( 'All Vendor Categories', 'wc_events' ),
      'parent_item'                => __( 'Parent Vendor Category', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Vendor Category:', 'wc_events' ),
      'new_item_name'              => __( 'New Vendor Category Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Vendor Category', 'wc_events' ),
      'edit_item'                  => __( 'Edit Vendor Category', 'wc_events' ),
      'update_item'                => __( 'Update Vendor Category', 'wc_events' ),
      'view_item'                  => __( 'View Vendor Category', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate vendor categories with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove vendor category', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Vendor Categories', 'wc_events' ),
      'search_items'               => __( 'Search Vendor Categories', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No vendor categories', 'wc_events' ),
      'items_list'                 => __( 'Vendor Categories list', 'wc_events' ),
      'items_list_navigation'      => __( 'Vendor Categories list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_ccw_event_categories',
      'edit_terms'   => 'manage_ccw_event_categories',
      'delete_terms' => 'manage_ccw_event_categories',
      'assign_terms' => 'edit_events',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      // 'capabilities'               => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'vendor_categories', [ 'event' ], $args );

  }



  /**
   * Venue Name with the following additional Term Meta:
   * Street Address, City, State, Zip
   */
  function ccw_venue(): void {

    $labels       = [
      'name'                       => _x( 'Venue', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Venue', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Venue', 'wc_events' ),
      'all_items'                  => __( 'All Venues', 'wc_events' ),
      'parent_item'                => __( 'Parent Venue', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Venue:', 'wc_events' ),
      'new_item_name'              => __( 'New Venue Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Venue', 'wc_events' ),
      'edit_item'                  => __( 'Edit Venue', 'wc_events' ),
      'update_item'                => __( 'Update Venue', 'wc_events' ),
      'view_item'                  => __( 'View Venue', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate venues with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove venue', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Venues', 'wc_events' ),
      'search_items'               => __( 'Search Venues', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No venues', 'wc_events' ),
      'items_list'                 => __( 'Venues list', 'wc_events' ),
      'items_list_navigation'      => __( 'Venues list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_event_venues',
      'edit_terms'   => 'edit_event_venues',
      'delete_terms' => 'delete_event_venues',
      'assign_terms' => 'edit_posts',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      'capabilities'      => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'venue', [ 'event' ], $args );

  }



  /**
   * Is this a featured event?
   */
  function featured_event(): void {

    $labels       = [
      'name'                       => _x( 'Featured Event', 'Taxonomy General Name', 'wc_events' ),
      'singular_name'              => _x( 'Featured Event', 'Taxonomy Singular Name', 'wc_events' ),
      'menu_name'                  => __( 'Featured Event', 'wc_events' ),
      'all_items'                  => __( 'All Featured Events', 'wc_events' ),
      'parent_item'                => __( 'Parent Featured Event', 'wc_events' ),
      'parent_item_colon'          => __( 'Parent Featured Event:', 'wc_events' ),
      'new_item_name'              => __( 'New Featured Event Name', 'wc_events' ),
      'add_new_item'               => __( 'Add New Featured Event', 'wc_events' ),
      'edit_item'                  => __( 'Edit Featured Event', 'wc_events' ),
      'update_item'                => __( 'Update Featured Event', 'wc_events' ),
      'view_item'                  => __( 'View Featured Event', 'wc_events' ),
      'separate_items_with_commas' => __( 'Separate venues with commas', 'wc_events' ),
      'add_or_remove_items'        => __( 'Add or remove venue', 'wc_events' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'wc_events' ),
      'popular_items'              => __( 'Popular Featured Events', 'wc_events' ),
      'search_items'               => __( 'Search Featured Events', 'wc_events' ),
      'not_found'                  => __( 'Not Found', 'wc_events' ),
      'no_terms'                   => __( 'No venues', 'wc_events' ),
      'items_list'                 => __( 'Featured Events list', 'wc_events' ),
      'items_list_navigation'      => __( 'Featured Events list navigation', 'wc_events' ),
    ];
    $capabilities = [
      'manage_terms' => 'manage_featured_events',
      'edit_terms'   => 'edit_featured_events',
      'delete_terms' => 'delete_featured_events',
      'assign_terms' => 'edit_posts',
    ];
    $args         = [
      'labels'            => $labels,
      'hierarchical'      => TRUE,
      'public'            => TRUE,
      'show_ui'           => TRUE,
      'show_admin_column' => TRUE,
      'show_in_nav_menus' => TRUE,
      'show_tagcloud'     => FALSE,
      'capabilities'      => $capabilities,
      'show_in_rest'      => TRUE,
    ];
    register_taxonomy( 'featured_event', [ 'event' ], $args );

  }

}
