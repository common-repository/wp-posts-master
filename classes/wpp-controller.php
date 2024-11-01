<?php
/**
 * Controller class
 * @author Flipper Code<hello@flippercode.com>
 * @version 3.0.0
 * @package Posts
 */

if ( ! class_exists( 'WPP_Controller' ) ) {

	/**
	 * Controller class to display views.
	 * @author: Flipper Code<hello@flippercode.com>
	 * @version: 3.0.0
	 * @package: Maps
	 */

	class WPP_Controller extends Flippercode_Factory_Controller{


		function __construct() {

			parent::__construct(WPP_MODEL,'WPP_Model_');

		}

	}
	
}
