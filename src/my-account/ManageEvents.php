<?php
/**
 * @author        Eric Frisino
 * @copyright     2023 Comfortcloth Weaving LLC
 * @created       7/13/23 12:47 PM
 * @license       MIT
 */

namespace WorthCommitting\Events\MyAccount;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use WorthCommitting\Events\Metadata\EventLinks;
use WorthCommitting\Events\Utilities\FormBuilder;
use WorthCommitting\Events\Utilities\ManageDates;


//use WorthCommitting\WCSSADIMembers\Traits\IsActiveMember;
//use WorthCommitting\WCSSADIMembers\Utilities\Sanitize;

class ManageEvents {
  //  use IsActiveMember;

  private Logger $logger;



  public function __construct() {
    // 1. Add member profile endpoint to WooCommerce My Account page.
    add_action( 'init', [ $this, 'add_endpoint' ], 1 );

    // 2. Add new variable to the vars array.
    add_filter( 'query_vars', [ $this, 'query_vars' ], 0 );

    // 3. Add link to my account navigation.
    add_filter( 'woocommerce_account_menu_items', [ $this, 'add_link_to_my_account_navigation' ], 1000 );

    // 4. Add content to the new endpoint.
    // Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
    add_action( 'woocommerce_account_manage-events_endpoint', [ $this, 'manage_events_content' ] );

    // 5. Add slug generator to the footer.
    add_action( 'wp_footer', [ $this, 'make_slug' ] );

    add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_the_scripts' ] );

    // 6. When event is saved, update the featured image.

    // Set up monolog.
    $this->logger = new Logger( 'manage-events' );
    $this->logger->pushHandler( new StreamHandler( __DIR__ . '../../../logs/manage-events.log', Level::Debug ) );
  }



  /**
   * Add member profile endpoint to WooCommerce My Account page.
   *
   * @return void
   */
  function add_endpoint(): void {
    add_rewrite_endpoint( 'manage-events', EP_ROOT | EP_PAGES );
  }



  /**
   * Add query variable to the vars array.
   *
   * @param $vars
   *
   * @return mixed
   */
  function query_vars( $vars ): mixed {
    $vars[] = 'manage-events';

    return $vars;
  }



  /**
   * Add link to Edit member profile page.
   *
   * @param  array  $items  All My Account navigation menu items.
   *
   * @return array My Account navigation menu items with new item included.
   */
  function add_link_to_my_account_navigation( array $items ): array {
    if( current_user_can( 'ssadi_member' ) ) {
      // Save Logout link information.
      $logout = $items['customer-logout'];

      // Remove the logout link from the list.
      unset( $items['customer-logout'] );

      $items['manage-events'] = 'Manage Events';

      // Add the logout link back to the end of the list.
      $items['customer-logout'] = $logout;
    }

    // Return the updated list.
    return $items;
  }



  /**
   * Add content to the edit member profile page.
   *
   * @return void
   */
  function manage_events_content(): void {
    // Get current user, and see if they are a member.
    $user_is_a_member = TRUE; # $this->CheckMembershipStatus( get_current_user_id() ); # in_array( 'member', (array) $current_user->roles );

    if( is_user_logged_in() && current_user_can( 'edit_worth_committing_event' ) ) {
      $this->user_is_a_member( get_current_user_id() );
    } else {
      $this->user_is_not_a_member();
    }
  }



  function user_is_a_member( $userID ): void {
    //    if( isset( $_GET['action'] ) ) {
    //      switch( $_GET['action'] ) {
    //        # Empty form to create a new event.
    //        case 'create':
    //          $this->edit_event( action: 'create' );
    //          break;
    //        # Filled in form to update an existing event.
    //        case 'edit':
    //          $this->edit_event( action: 'edit',
    //                             event : json_decode( json_encode( get_post( $_GET['id'] ) ), TRUE ) );
    //          break;
    //        # Insert a new event from the empty create form and then populate it with record from database.
    //        case 'insert':
    //          $inserted_event_id = $this->create_event( action: 'insert',
    //                                                    data  : $_POST );
    //          $this->edit_event( action: 'edit',
    //                             event : json_decode( json_encode( get_post( $inserted_event_id ) ), TRUE ) );
    //          break;
    //        # Update an existing event from the updated form and then populate it with updated record from database.
    //        case 'update':
    //          $updated_event_id = $this->update_event( action: 'update',
    //                                                   data  : $_POST );
    //          $this->edit_event( action: 'edit',
    //                             event : json_decode( json_encode( get_post( $updated_event_id ) ), TRUE ) );
    //          break;
    //        default:
    //          break;
    //      }
    //    } else {
    //      $this->show_users_events();
    //    }

    $this->show_users_events();
  }



  function user_is_not_a_member(): void {
    echo "<div class='w-3/4 m-auto bg-white shadow rounded-lg p-10 text-center mb-6'>";
    echo "  <i class='fa-solid fa-user-xmark text-4xl'></i>";
    echo "  <div class='font-heading text-5xl text-center mt-2 mb-0'>Your membership has expired</div>";
    echo "  <div class='text-base mt-1'>or you are not eligible for membership at this time.</div>";
    echo "</div>";

    echo "<div class='w-3/4 m-auto'>";
    echo "  <p class='text-center'>If you feel you have reached this page in error, please reach out via the form below.</p>";
    echo "</div>";

    echo "<div class='w-3/4 m-auto p-4'>";
    echo do_shortcode( '[gravityform id="6" title="false"]' );
    echo "</div>";
  }



  private function sanitize_data( array $content ): array {
    $this->logger->debug( 'POST CONTENT', $content );


    $this->logger->info( 'Data Submitted by: ', );

    return [];
  }



  private function create_event( string $action, array $data, ): array {
    // Initialize results array.
    $update_results = [];

    // --- --- --- --- --- --- --> The data submitted
    echo "<pre class='text-sm'>THE DATA</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $data );
    echo "</pre>";

    // --- --- --- --- --- --- --> Save the event.
    $the_post = [
      'post_author'    => get_current_user_id(),
      'post_content'   => wp_kses_post( $data['content'] ),
      'post_title'     => sanitize_text_field( $data['title'] ),
      'post_name'      => $this->create_slug( $data['title'] . " " . $data['ccw_event_day_date'][0] ),
      'post_excerpt'   => wp_kses_post( $data['excerpt'] ),
      'post_status'    => $data['post_status'],
      'post_type'      => 'event',
      'comment_status' => 'closed',
      'ping_status'    => 'closed',
    ];

    echo "<pre class='text-sm'>THE POST</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $the_post );
    echo "</pre>";


    # $insert_result = wp_insert_post( $the_post );

    # echo "<pre class='text-sm mb-10'>"; var_dump( $insert_result );  echo "</pre>";

    // --- --- --- --- --- --- --> Save featured image.
    if( array_key_exists( 'featured_image', $_POST ) ) {

      // These files need to be included as dependencies when on the front end.
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      require_once(ABSPATH . 'wp-admin/includes/media.php');

      // Let WordPress handle the upload.
      $img_id = media_handle_upload( 'featured_image', 0 );

      echo "<pre class='text-sm'>UPLOADED IMAGE ID</pre>";
      echo "<pre class='text-sm mb-10'>";
      var_dump( $img_id );
      echo "</pre>";

      # if ( is_wp_error( $img_id ) ) {
      #   echo "Error";
      # } else {
      #   set_post_thumbnail( $insert_result, $img_id );
      # }
    }

    // Check if a specific value was updated.
    # if( isset( $_POST['acf']['field_64d10a7d2db3c'] ) ) {
    #   set_post_thumbnail( $insert_result, $_POST['acf']['field_64d10a7d2db3c'] );
    # }


    // --- --- --- --- --- --- --> Prepare and save event links meta.
    $the_links = isset( $_POST['ccw_event_link_text'] ) ? (new EventLinks())->prep_event_links_for_saving( $data ) : NULL;

    echo "<pre class='text-sm'>Event Links</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $the_links );
    echo "</pre>";

    # if( !is_null( $the_links ) ) {
    #   update_post_meta( $insert_result, 'ccw_event_links', $the_links );
    # }


    // --- --- --- --- --- --- --> Prepare and save event date/times meta.
    $the_dates = isset( $_POST['ccw_event_day_date'] ) ? $this->prep_dates_for_saving( $data ) : NULL;

    echo "<pre class='text-sm'>Event Date/Times</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $the_dates );
    echo "</pre>";

    $the_event_first_day = count( $the_dates ) == 0
      ? ''
      : str_replace( '-', '', $the_dates[0]['date'] );

    echo "<pre class='text-sm'>Event First Day</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $the_event_first_day );
    echo "</pre>";

    # update_post_meta( $insert_result, 'ccw_event_first_day', $the_event_first_day );

    $the_event_last_day = count( $the_dates ) == 0
      ? ''
      : str_replace( '-', '', $the_dates[count( $the_dates ) - 1]['date'] );

    echo "<pre class='text-sm'>Event Last Day</pre>";
    echo "<pre class='text-sm mb-10'>";
    var_dump( $the_event_last_day );
    echo "</pre>";

    # update_post_meta( $insert_result, 'ccw_event_last_day', $the_event_last_day );

    // Update the event dates meta.
    # if( isset( $the_dates ) ) {
    #   update_post_meta( $insert_result, 'ccw_event_dates', $the_dates );
    # }

    # if( !is_null( $the_links ) ) {
    #   update_post_meta( $insert_result, 'ccw_event_links', $the_links );
    # }

    // Prep and save event dates to database.


    // Return the results.
    return $update_results;
  }



  public function prep_dates_for_saving( $data ): array {
    $date  = $data['ccw_event_day_date'] ?? '';
    $open  = $data['ccw_event_day_open'] ?? '';
    $close = $data['ccw_event_day_close'] ?? '';

    // Initialize array.
    $the_event_dates = [];

    // Get, Sanitize, & Save all the data.
    if( is_array( $date ) && count( $date ) > 0 ) {
      $total_dates = count( $date );

      for( $i = 0; $i < $total_dates; $i++ ) {
        $the_event_dates[$i]['date']  = sanitize_text_field( $date[$i] );
        $the_event_dates[$i]['open']  = sanitize_text_field( $open[$i] );
        $the_event_dates[$i]['close'] = sanitize_text_field( $close[$i] );
      }

      $this->logger->info( 'the dates', $the_event_dates );
    }

    return $the_event_dates;
  }



  public function make_slug() { ?>
    <script>
      jQuery(document).ready(function ($) {
        $("#business_name").on("blur", function () {
          let business_name = $(this).val();
          let business_name_slug = business_name.trim().replace(/[^A-Za-z0-9-]+/g, '-').toLowerCase();
          $('.the_slug').text(business_name_slug);
        });
      });
    </script>

    <script>
      jQuery(document).ready(function ($) {
        $('#event_venue').select2({
          placeholder: 'Select Venue'
        });
      });
    </script>

    <script>
      function add_venue() {

      }
    </script>
  <?php }



  private function edit_event( string $action, array $event = [] ): void {
    # echo "<pre class='text-sm'>"; var_dump( $event ); echo "</pre>";

    // Set the base URL
    $base_url = get_site_url();

    // Get the venue taxonomy.
    $the_venues = get_terms( [ 'taxonomy' => 'venue', 'hide_empty' => FALSE ] );
    $the_venue  = array_key_exists( 'ID', $event )
      ? wp_get_post_terms( $event['ID'], 'venue' )
      : [ '' ];
    $the_venue  = array_key_exists( 0, $the_venue ) ? $the_venue[0] : '';

    // Get the admission flag taxonomy.
    $the_admission_options = get_terms( [ 'taxonomy' => 'admission_options', 'hide_empty' => FALSE ] );
    $the_admission         = array_key_exists( 'ID', $event )
      ? wp_get_post_terms( $event['ID'], 'admission_options' )
      : [ '' ];
    $the_admission         = is_null( $the_admission ) ?? $the_admission[0];

    // Get all event post meta.
    $the_meta = array_key_exists( 'ID', $event ) ? get_post_meta( $event['ID'] ) : [ '' ];

    // Get the event links.
    $the_links = array_key_exists( 'ccw_event_links', $the_meta ) ? $the_meta['ccw_event_links'] : [];
    $the_links = count( $the_links ) > 0 ? unserialize( $the_links[0] ) : [];

    // Set heading.
    echo match ($action) {
      'create' => "<h3 class='manage-events_title'>Create Event</h3>",
      'edit'   => "<h3 class='manage-events_title'>Edit Event</h3>",
    };

    echo "<pre class='text-xs'>";
    // var_dump( $event );
    // var_dump( $the_venue );
    // var_dump( $the_admission );
    var_dump( $the_meta );
    // var_dump( $the_links );
    echo "</pre>";


    $form_action = match ($action) {
      'edit'   => 'update&id=' . $_GET['id'],
      'create' => 'insert',
      'insert' => 'edit',
    };

    echo "<form class='my-account_form-container edit_member_profile' action='?action=$form_action' method='post' enctype='multipart/form-data'>";

    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>General Event Information</h4>";

    //--- --- --- --- --- --- --- --- --- --> Event Name: title
    $post_title = array_key_exists( 'post_title', $event ) ? $event['post_title'] : '';
    $post_name  = array_key_exists( 'post_name', $event ) ? $event['post_name'] : '';
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='title'>Event Title</label></div>";
    echo "      <div class='field-input'>";
    echo "        <input type='text' name='title' id='title' value='$post_title' />";
    echo "        <p class='field-description'>Once you update your event date/time your url will show below:</p>";
    echo "        <p class='field-description'>";
    echo "          <a href='$base_url/events/$post_name' class='text-slate-500 hover:text-slate-700 underline'>";
    echo "            $base_url/events/<span class='the_slug'>$post_name</span>";
    echo "          </a>";
    echo "        </p>";
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    //--- --- --- --- --- --- --- --- --- --> Event Description: the_content
    $post_content = array_key_exists( 'post_content', $event ) ? $event['post_content'] : '';
    $post_excerpt = array_key_exists( 'post_excerpt', $event ) ? $event['post_excerpt'] : '';
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='content'>Full Event Description</label></div>";
    echo "      <div class='field-input'>";
    wp_editor( $post_content,
               'content',
               [ 'media_buttons' => FALSE, 'teeny' => TRUE, 'textarea_rows' => 15 ] );
    echo "        <p class='field-description'>This will appear on the event's page.</p>";
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    //--- --- --- --- --- --- --- --- --- --> Elevator Pitch: the_excerpt
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='excerpt'>Short Event Description</label></div>";
    echo "      <div class='field-input'>";
    wp_editor( $post_excerpt,
               'excerpt',
               [ 'media_buttons' => FALSE, 'teeny' => TRUE, 'textarea_rows' => 5 ] );
    echo "        <p class='field-description'>2-3 sentences about the event. This will appear on pages linking to the event's page.</p>";
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    echo "  </div>"; # .edit-member-profile_container

    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>Event Venue</h4>";

    //--- --- --- --- --- --- --- --- --- --> Event Venue: venue
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='event_venue'>Event Venue</label></div>";
    echo "      <div class='field-input'>";
    echo "        <div class='two-columns'>";
    echo "          <div><select id='event_venue' name='event_venue' class='w-full'>";
    foreach( $the_venues as $venue ) {
      $selected = $venue->term_id == $the_venue->term_id ? "selected='selected'" : '';
      echo "<option value='{$venue->term_id}' $selected>{$venue->name}</option>";
    }
    echo "          </select></div>";
    echo "        <p class='field-description'>Don't see the location of your event? <a href='' class='text-slate-500 hover:text-slate-700 underline'>Request it be added here</a>.</p>";
    echo "      </div>"; # .two-columns
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    echo "  </div>"; # .edit-member-profile_container

    //--- --- --- --- --- --- --- --- --- --> Event Dates & Times: ccw_event_day_date, ccw_event_day_open, ccw_event_day_close
    $dates = array_key_exists( 'ccw_event_dates', $the_meta ) ? unserialize( $the_meta['ccw_event_dates'][0] ) : [];

    echo "<pre class='text-xs'>";
    var_dump( $dates );
    //    var_dump( $opens );
    //    var_dump( $closes );
    echo "</pre>";

    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>Event Date Time</h4>";
    echo "    <div class='field-container day-time-input'>";

    echo "      <div class='field-label border-b-2 border-b-neutral-500 w-full'>Event Day</div>";
    echo "      <div class='field-input'>";
    echo "        <div class='three-columns'>";
    echo "          <div class='border-b-2 border-b-neutral-500 w-full'><strong>DATE</strong></div>";
    echo "          <div class='border-b-2 border-b-neutral-500 w-full'><strong>OPEN TIME</strong></div>";
    echo "          <div class='border-b-2 border-b-neutral-500 w-full'><strong>CLOSE TIME</strong></div>";
    echo "        </div>"; # .three-columns
    echo "      </div>"; # .field-input

    $ccw_event_dates = is_countable( $dates ) ? $dates : [];
    $total_dates     = count( $ccw_event_dates );

    if( $total_dates > 0 ) {
      $counter = 0;
      while( $total_dates > $counter ) {

        echo (new FormBuilder())->build_event_date_time_row( $ccw_event_dates[$counter], $counter, 'frontend' );

        $counter++;
      }
    } else {
      $counter = 1;
      echo (new FormBuilder())->build_event_date_time_row( NULL, 0, 'frontend' );
    }

    echo "    </div>"; # .field-container
    echo "    <div class='flex justify-end gap-3 w-full'>";
    echo "      <div id='remove_date' class='sm-button destructive inline-block cursor-pointer' data-delete_row='" . $counter - 1 . "'>Delete Last Row</div>";
    echo "      <div id='add_date' class='sm-button inline-block cursor-pointer' data-current_row='" . $counter . "'>Add Date</div>";
    echo "    </div>";
    echo "  </div>"; # .edit-member-profile_container */


    //--- --- --- --- --- --- --- --- --- --> Event Admission: event_admission, event_admission_notes
    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>Admission Information</h4>";

    //--- --- --- --- --- --- --- --- --- --> Paid Admission: event_admission
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='event_admission'>Paid Admission</label></div>";
    echo "      <div class='field-input'>";
    echo "        <select id='event_admission' name='event_admission'>";
    foreach( $the_admission_options as $admission_option ) {
      $selected = !is_null( $the_admission ) && $admission_option->term_id == $the_admission->term_id ? "selected='selected'" : '';
      echo "<option value='{$admission_option->term_id}' $selected>{$admission_option->name}</option>";
    }
    echo "        </select>";
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    //--- --- --- --- --- --- --- --- --- --> Admission Description: event_admission_notes
    $admission_notes = array_key_exists( 'ccw_event_admission', $event ) ? $event['ccw_event_admission'] : '';
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='ccw_event_admission'>Admission Notes</label></div>";
    echo "      <div class='field-input'>";
    wp_editor( $admission_notes,
               'ccw_event_admission',
               [ 'media_buttons' => FALSE, 'teeny' => TRUE, 'textarea_rows' => 15 ] );
    echo "        <p class='field-description'>This will appear on the event's page under the admission title.</p>";
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container

    echo "  </div>"; # .edit-member-profile_container


    //--- --- --- --- --- --- --- --- --- --> Event Links: ccw_event_links
    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>Event Links</h4>";

    echo "    <p class='text-sm'>This is where you will enter relevant links, such as 'Facebook Event Page', 'Event Website', 'Parking', etc.</p>";


    echo "    <div id='link-input' class='field-container'>";

    echo "      <div class='field-label border-b-2 border-b-neutral-500 w-full' style='margin-top: 0 !important;'>Links</div>";
    echo "      <div class='field-input'>";
    echo "        <div class='three-columns'>";
    echo "          <div class='border-b-2 border-b-neutral-500 w-full col-span-3'><strong>URL, Text, & Target</strong></div>";
    echo "        </div>"; # .three-columns
    echo "      </div>"; # .field-input

    if( is_countable( $the_links ) ) {
      $total_links = count( $the_links );
    }

    if( $total_links > 0 ) {
      $counter = 0;

      while( $total_links > $counter ) {
        echo (new FormBuilder())->build_event_link_row( $the_links[$counter], $counter, 'frontend' );

        $counter++;
      }
    } else {
      echo (new FormBuilder())->build_event_link_row( NULL, 0, 'frontend' );
    }

    echo "    </div>"; # .field-container

    echo "    <div class='flex justify-end gap-3 w-full'>";
    echo "      <div id='remove_link' class='sm-button destructive inline-block cursor-pointer' data-delete_row='" . $counter - 1 . "'>Delete Last Link</div>";
    echo "      <div id='add_new_event_link' class='sm-button inline-block cursor-pointer' data-current_row='" . $counter . "'>Add Link</div>";
    echo "    </div>";

    echo "  </div>"; # .edit-member-profile_container */

    //    $form_settings = [
    //      'field_groups' => [
    //        'group_64d10a7d283f8'
    //      ],
    //      'form'         => FALSE,
    //      'return'       => '',
    //      'uploader'     => 'wp',
    //      'new_post' => $action == 'create',
    //      'post_id' => $_GET['id'] ?? 'new_post',
    //    ];
    //
    //    acf_form( $form_settings );

    echo "  <div class='edit-member-profile_container'>";
    echo "    <h4>Featured Image</h4>";
    echo "    <input class='text-input' name='featured_image' type='file' id='featured_image'/>";
    echo "  </div>"; # .edit-member-profile_container


    //--- --- --- --- --- --- --- --- --- --> Event Status: post_status
    echo "  <div class='edit-member-profile_container'>";
    $the_statuses = [ 'draft', 'publish', 'trash' ];
    echo "    <div class='field-container'>";
    echo "      <div class='field-label'><label for='post_status'>Event Status</label></div>";
    echo "      <div class='field-input'>";
    echo "        <div class='one-column'>";
    echo "          <div><select id='post_status' name='post_status' class='w-full'>";
    foreach( $the_statuses as $status ) {
      if( isset( $event['post_status'] ) ) {
        $selected = $event['post_status'] == $status ? "selected='selected'" : '';
      } else {
        $selected = '';
      }
      echo "<option value='$status' $selected>" . ucfirst( $status ) . "</option>";
    }
    echo "          </select></div>";
    echo "      </div>"; # .two-columns
    echo "      </div>"; # .field-input
    echo "    </div>"; # .field-container
    echo "  </div>"; # .edit-member-profile_container

    if( isset( $_GET['id'] ) ) {
      echo "<input type='hidden' name='id' value='{$_GET['id']}'/>";
    }

    echo "  <button class='lg-button uppercase mt-10'><i class='fa-solid fa-up mr-2'></i> Save all information above <i class='fa-solid fa-up ml-2'></i></button>";


    echo "</form>";
  }



  public function enqueue_the_scripts(): void {
    wp_enqueue_script( 'add_event_date', plugin_dir_url( __FILE__ ) . 'inc/js/add-date-time.js', [ 'jQuery' ], TRUE );
  }



  /**
   * This content is displayed if no `action` param is set in the url.
   * A place to start, if you will.
   *
   * @return void
   */
  private function show_users_events(): void {
    // Get the current user.
    $the_user = wp_get_current_user();

    // Initialize user's events array
    $events = new \WP_Query( [
                               'author'         => get_current_user_id(),
                               'post_type'      => 'event',
                               'posts_per_page' => -1,
                               'post_status'    => 'any' ] );

    //    echo "<pre class='text-sm'>";
    //    var_dump( $events );
    //    echo "</pre>";

    echo "  <h3 class='manage-events_title'>Manage Events</h3>";

    if( $events->have_posts() ) {
      echo "<div class='my-account_events-table'>";
      echo "  <div class='event-title header'>Title</div>";
      echo "  <div class='event-dates header'>Dates</div>";
      echo "  <div class='event-status header'>Status</div>";
      echo "  <div class='event-actions header'>Actions</div>";

      while( $events->have_posts() ) {
        $events->the_post();

        $post_status = match (get_post_status()) {
          'attended'  => 'Attended',
          'draft'     => 'Draft',
          'published' => "Published",
          'publish'   => "Published",
          "default"   => "&mdash;",
        };

        $event_meta = get_post_meta( get_the_ID() );

        // var_dump( $event_meta );

        $the_dates = array_key_exists( 'ccw_event_first_day', $event_meta ) && array_key_exists( 'ccw_event_last_day', $event_meta )
          ? (new ManageDates())->print_readable_date( $event_meta['ccw_event_first_day'][0], $event_meta['ccw_event_last_day'][0] )
          : 'Not Set';

        echo "  <div class='event-title'>" . esc_html( get_the_title() ) . "</div>";
        echo "  <div class='event-dates'>$the_dates</div>";
        echo "  <div class='event-status'>$post_status</div>";
        echo "  <div class='event-actions'>";
        echo "    <a href='" . get_the_permalink() . "'><i class='fa-solid fa-eye px-1'></i></a>";
        echo "    <a href='" . get_edit_post_link( get_the_ID() ) . "'><i class='fa-solid fa-pen-nib px-1'></i></a>";
        echo "  </div>";
      }
      echo "</div>";
    } else {
      echo "You have not created any events.";
    }

    echo "<hr class='mt-6 border-b-2 border-b-neutral-500' />";

    $the_URL = get_site_url() . "/wp-admin/post-new.php?post_type=event";
    echo "<div class='w-auto text-center mt-8'><a href='$the_URL' class='md-button m-auto'>Create New Event</a></div>";
  }



  public function create_slug( $str, $delimiter = '-' ): string {

    return strtolower( trim( preg_replace( '/[\s-]+/', $delimiter, preg_replace( '/[^A-Za-z0-9-]+/', $delimiter, preg_replace( '/[&]/', 'and', preg_replace( '/[\']/', '', iconv( 'UTF-8', 'ASCII//TRANSLIT', $str ) ) ) ) ), $delimiter ) );

  }
}