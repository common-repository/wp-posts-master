<?php
/**
 * Class: wpp_Model_Layout
 * @author Flipper Code <hello@flippercode.com>
 * @package Maps
 * @version 3.0.0
 */

if ( ! class_exists( 'wpp_Model_Layout' ) ) {

	/**
	 * Layout model for CRUD operation.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class wpp_Model_Layout extends FlipperCode_Model_Base
	{
		/**
		 * Validations on location properies.
		 * @var array
		 */
		public $validations = array(
		'layout_title' => array( 'req' => 'Please enter template title.' ),
		);
		/**
		 * Intialize rule object.
		 */
		public function __construct() {
			$this->table = WPP_TBL_LAYOUT;
			$this->unique = 'layout_id';
		}
		/**
		 * Admin menu for CRUD Operation
		 * @return array Admin meny navigation(s).
		 */
		public function navigation() {

			return array(
			'wpp_form_layout' => __( 'New Template', WPP_TEXT_DOMAIN ),
			'wpp_manage_layout' => __( 'Manage Templates', WPP_TEXT_DOMAIN ),
			);
		}
		/**
		 * Install table associated with Rule entity.
		 * @return string SQL query to install post_widget_rules table.
		 */
		public function install() {

			global $wpdb;
			$post_widget_rules = 'CREATE TABLE '.$wpdb->prefix.'post_widget_layouts (
										`layout_id` int(11) NOT NULL AUTO_INCREMENT,
										`layout_title` varchar(255) DEFAULT NULL,
										`layout_rule_id` text DEFAULT NULL,
										`layout_type` varchar(255) DEFAULT NULL,
										`layout_post_setting` text DEFAULT NULL,
										 PRIMARY KEY (`layout_id`)
										) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;';

			return $post_widget_rules;
		}
		/**
		 * Get Rule(s)
		 * @param  array $where  Conditional statement.
		 * @return array         Array of Rule object(s).
		 */
		public function fetch($where = array()) {

			$objects = $this->get( $this->table, $where );

			if ( isset( $objects ) ) {
				foreach ( $objects  as $object ) {
					$object->layout_rule_id = unserialize( $object->layout_rule_id );
					$object->layout_post_setting = unserialize( $object->layout_post_setting );
				}
				return $objects;
			}
		}

		/**
		 * Add or Edit Operation.
		 */
		public function save() {
			$data = array();
			$entityID = '';
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

				die( 'Cheating...' );

			}

			$this->verify( $_POST );

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}

			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
			}

			if ( $entityID > 0 ) {
				$where[ $this->unique ] = $entityID;
			} else {
				$where = '';
			}
			$data['layout_title'] = sanitize_text_field( wp_unslash( $_POST['layout_title'] ) );
			$data['layout_rule_id'] = serialize( wp_unslash( $_POST['layout_rule_id'] ) );
			$data['layout_type'] = sanitize_text_field( wp_unslash( $_POST['layout_type'] ) );
			$data['layout_post_setting'] = serialize( wp_unslash( $_POST['layout_post_setting'] ) );

			$result = FlipperCode_Database::insert_or_update( $this->table, $data, $where );

			if ( false === $result ) {
				$response['error'] = __( 'Something went wrong. Please try again.',WPP_TEXT_DOMAIN );
			} elseif ( $entityID > 0 ) {
				$response['success'] = __( 'Template updated successfully',WPP_TEXT_DOMAIN );
			} else {
				$response['success'] = __( 'Template added successfully.',WPP_TEXT_DOMAIN );
			}
			return $response;
		}

		/**
		 * Delete rule object by id.
		 */
		public function delete() {
			if ( isset( $_GET['rule_id'] ) ) {
				$id = intval( wp_unslash( $_GET['rule_id'] ) );
				$connection = FlipperCode_Database::connect();
				$this->query = $connection->prepare( "DELETE FROM $this->table WHERE $this->unique='%d'", $id );
				return FlipperCode_Database::non_query( $this->query, $connection );
			}
		}
		/**
		 * Copy Template using Copy Link.
		 * @param  int $layout_id Template ID.
		 */
		function copy($layout_id) {
			if ( isset( $layout_id ) ) {
				$id = intval( wp_unslash( $layout_id ) );
				$layout = $this->get( $this->table,array( array( 'layout_id', '=', $id ) ) );
				$data = array();
				foreach ( $layout[0] as $column => $value ) {

					if ( 'layout_id' == $column ) {
						continue; } else if ( 'layout_title' == $column ) {
						$data[ $column ] = $value.' '.__( 'Copy',WPGMP_TEXT_DOMAIN );
						} else { $data[ $column ] = $value; }
				}

				$result = FlipperCode_Database::insert_or_update( $this->table, $data );
			}
		}

	}
}
