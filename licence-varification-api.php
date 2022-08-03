<?php
/**
 * Plugin Name: A Licence verification API
 * Description: This custom plugin is developed for Licence verification data tables controls where we can control, entry new, delete or add any licences on our site and can see hou much API called intotal.
 * Plugin URI: https://futurewordpress.com/licences/chrome-fiverr/
 * Author Name: Future Wordpress
 * Version: 3.6.6
 * Author URI: https://futurewordpress.com/author/
 *
 * Text Domain: licence-verification-api
 *
 * @package licence-verification-api
 * @category Core
 *
 * licence-verification-api is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * licence-verification-api is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'LICENCE_VERIFICATION_API_VERSION', '3.6.6' );


if ( ! defined( 'LICENCE_VERIFICATION_API_DIR_PATH' ) ) {
	define( 'LICENCE_VERIFICATION_API_DIR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_DIR_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_DIR_URI', untrailingslashit( plugins_url( __FILE__ ) ) );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_URI', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_PATH' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_PATH', untrailingslashit( plugins_url( __FILE__ ) ) . '/assets/build' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_JS_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_JS_URI', untrailingslashit( plugins_url( __FILE__ ) ) . '/assets/build/js' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_JS_DIR_PATH' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_JS_DIR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build/js' );
}
if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_IMG_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_IMG_URI', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build/src/img' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_CSS_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_CSS_URI', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build/css' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_CSS_DIR_PATH' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_CSS_DIR_PATH', untrailingslashit( plugins_url( __FILE__ ) ) . '/assets/build/css' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_BUILD_LIB_URI' ) ) {
	define( 'LICENCE_VERIFICATION_API_BUILD_LIB_URI', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build/library' );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_ARCHIVE_POST_PER_PAGE' ) ) {
	define( 'LICENCE_VERIFICATION_API_ARCHIVE_POST_PER_PAGE', 9 );
}

if ( ! defined( 'LICENCE_VERIFICATION_API_SEARCH_RESULTS_POST_PER_PAGE' ) ) {
	define( 'LICENCE_VERIFICATION_API_SEARCH_RESULTS_POST_PER_PAGE', 9 );
}
if( is_admin() ) {
  require_once LICENCE_VERIFICATION_API_DIR_PATH . '/admin.php';
}
require_once LICENCE_VERIFICATION_API_DIR_PATH . '/public.php';