<?php
// Set Namespace
namespace WorthCommitting\Events\Shortcodes;
/**
 * @author        Eric Frisino
 * @copyright     2021 Comfortcloth Weaving LLC
 * @created       2021-06-23 1:10 PM
 * @license       GPL 3
 * @package       ccw_events
 * @subpackage    shortcodes
 */

use WP_Query;

if( !defined( 'WPINC' ) ) {
  die;
}


class attended_shows_resume {
  public function __construct() {
    add_shortcode( 'src\shortcodes\attended_shows_resume', [ $this, 'attended_shows_resume' ] );
  }



  function attended_shows_resume() {
    // WP_Query arguments
    $args = [
      'post_type'      => 'events',
      'post_status'    => 'attended',
      'posts_per_page' => -1,
    ];
    // The Query
    $query = new WP_Query( $args );

    $display_me = "<div class='resume-row'>
			<div class='span-1-of-10'>2020</div>
			<div class='span-9-of-10'>
				<strong>Memorial Art Gallery Show & Sale</strong>, Online<br />
				<strong>HMWG Show & Sale</strong>, Online<br />
				<strong>Peters Valley School of Craft Show</strong>, Online<br />
				<strong>2020 Farm to Fiber Tour</strong>, Our Studio<br />
				<strong>American Craft Show Baltimore</strong><br />
			</div>
		</div>";
    $display_me .= "<div class='resume-row'>
			<div class='span-1-of-10'>2019</div>
			<div class='span-9-of-10'>
				<strong>Southern VT Arts Center Holiday Market</strong>, Manchester VT*<br />
				<strong>HMWG Show & Sale</strong>, Colonie NY<br />
				<strong>Memorial Art Gallery Show & Sale</strong>, Rochester NY*<br />
				<strong>Adirondack Wool & Arts Festival</strong>, Greenwich NY*<br />
				<strong>Fall Crafts at Lyndhurst</strong>, Tarrytown NY*<br />
				<strong>Saratoga Fiber Forum</strong>, Saratoga Springs NY<br />
				<strong>The Rhinebeck Crafts Festival</strong>, Rhinebeck NY*<br />
				<strong>Beekman Street Art Fair</strong>, Saratoga Springs NY*<br />
				<strong>100 American Craftsmen</strong>, Lockport NY*, <em>Honorable Mention in Non-Wearable Fiber</em><br />
				<strong>Bruce Museum Crafts Festival</strong>, Greenwich CT* <em>Second Place in Fiber</em><br />
				<strong>Spring Crafts at Lyndhurst</strong>, Tarrytown NY*<br />
				<strong>Collar City Craft Fest</strong>, Troy NY*<br />
				<strong>CraftBoston Spring</strong>, Boston MA*<br />
				<strong>American Craft Show Baltimore</strong>, Baltimore MD*<br />
			</div>
		</div>";
    $display_me .= "<div class='resume-row'>
			<div class='span-1-of-10'>2018</div>
			<div class='span-9-of-10'>
				<strong>HMWG Show & Sale</strong>, Colonie NY<br />
				<strong>Memorial Art Gallery Show & Sale</strong>, Rochester NY*<br />
				<strong>Holiday Arts Fair</strong>, Saratoga NY*<br />
				<strong>Adirondack Wool & Arts Festival</strong>, Greenwich NY*<br />
				<strong>Hammondsport Festival of Crafts</strong>, Hammondsport NY*<br />
				<strong>Beekman Street Art Fair</strong>, Saratoga Springs NY*<br />
				<strong>LARAC June Arts Festival</strong>, Glens Falls NY*<br />

				<strong>Collar City Craft Fest</strong>, Troy NY*<br />
				<strong></strong><br />
			</div>
		</div>";
    $display_me .= "* Jurried Shows.";
    // The Loop
    if( $query->have_posts() ) {
      while( $query->have_posts() ) {
        $query->the_post();
      }
    } else {
      // no posts found
    }


    // Restore original Post Data
    wp_reset_postdata();

    return $display_me;
  }
}

new attended_shows_resume;