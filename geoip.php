<?php
/*
Plugin Name: WP Engine GeoIP
Version: 1.0.0
Description: Create a personalized user experienced based on location.
Author: Steven Word
Author URI: http://wpengine.com
Plugin URI: http://www.wpengine.com/wp-geo-ip/
Text Domain: wp-geo-ip
Domain Path: /languages
*/

/* Examples use of how to add geoip information to post content:

function geoip_append_content( $content ) {
	$geo = WPEngine\GeoIp::instance();
	$content .= "How's the weather in {$geo->city()}, {$geo->region()} {$geo->country()}?<br /><br />";
	return $content;
}
add_filter( 'the_content', 'geoip_append_content' );

*/

namespace WPEngine;

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class GeoIp {

	// The single instance of this object.  No need to have more than one.
	private static $instance = null;

	// The geographical data loaded from the environment
	public $geos;

	public static function init() {
		add_action( 'init', array( self::instance(), 'setup' ) );
	}

	public static function instance() {
		// create a new object if it doesn't exist.
		is_null( self::$instance ) && self::$instance = new self;
		return self::$instance;
	}

	public function setup() {
		$this->geos = $this->get_actuals();
	}

	/**
 	 * Here we extract the data from headers set by nginx -- lets only send them if they are part of the cache key
 	 **/
	public function get_actuals() {
		return array(
			'countrycode'  => getenv( 'HTTP_GEOIP_COUNTRY_CODE' ),
			'countrycode3' => getenv( 'HTTP_GEOIP_COUNTRY_CODE3' ),
			'countryname'  => getenv( 'HTTP_GEOIP_COUNTRY_NAME' ),
			'latitude'     => getenv( 'HTTP_GEOIP_LATITUDE' ),
			'longitude'    => getenv( 'HTTP_GEOIP_LONGITUDE' ),
			'areacode'     => getenv( 'HTTP_GEOIP_AREA_CODE' ),
			'region'       => getenv( 'HTTP_GEOIP_REGION' ),
			'city'         => getenv( 'HTTP_GEOIP_CITY' ),
			'postalcode'   => getenv( 'HTTP_GEOIP_POSTAL_CODE' ),
		);
	}

	/**
	 * Examples of easy to use utility functions that we should have for each geo that is part of the cache key
	 *
	 * @return mixed
	 */
	public function country() {
		return $this->geos[ 'countrycode' ];
	}

	/**
	 * Region
	 * @return mixed
	 */
	public function region() {
		return $this->geos[ 'region' ];
	}

	/**
	 * @return mixed
	 */
	public function city() {
		return $this->geos[ 'city' ];
	}

}

/**
 * Register to do the stuff
 */
GeoIp::init();
