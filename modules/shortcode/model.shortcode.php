<?php
/**
 * Class: wpp_Model_Shortcode
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Posts
 */

if ( ! class_exists( 'wpp_Model_Shortcode' ) ) {

	/**
	 * Shortcode model to display output on frontend.
	 * @package Posts
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class wpp_Model_Shortcode extends FlipperCode_Model_Base {
		/**
		 * Intialize Shortcode object.
		 */
		function __construct() {
		}
		/**
		 * Admin menu for Settings Operation
		 * @return array Admin menu navigation(s).
		 */
		function navigation() {
			return array();
		}
	}
}
