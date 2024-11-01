<?php
/**
 * Class: wpp_Model_Rules
 * @author Flipper Code <hello@flippercode.com>
 * @package Maps
 * @version 3.0.0
 */

if ( ! class_exists( 'wpp_Model_Rules' ) ) {

	/**
	 * Location model for CRUD operation.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class wpp_Model_Rules extends FlipperCode_Model_Base
	{
		/**
		 * Validations on location properies.
		 * @var array
		 */
		public $validations = array(
		'rule_name' => array( 'req' => 'Please enter rule title.' ),
		);
		/**
		 * Intialize rule object.
		 */
		public function __construct() {
			$this->table = WPP_TBL_RULES;
			$this->unique = 'rule_id';
		}
		/**
		 * Admin menu for CRUD Operation
		 * @return array Admin meny navigation(s).
		 */
		public function navigation() {

			return array(
			'wpp_how_overview' => __( 'How to Use', WPP_TEXT_DOMAIN ),
			'wpp_form_rules' => __( 'Add Rule', WPP_TEXT_DOMAIN ),
			'wpp_manage_rules' => __( 'Manage Rules', WPP_TEXT_DOMAIN ),
			);
		}
		/**
		 * Install table associated with Rule entity.
		 * @return string SQL query to install post_widget_rules table.
		 */
		public function install() {
				
			global $wpdb;
			$post_widget_rules = 'CREATE TABLE '.$wpdb->prefix.'post_widget_rules (
							   rule_id int(11) NOT NULL AUTO_INCREMENT,
							   rule_name varchar(255) DEFAULT NULL,
							   rule_type varchar(255) DEFAULT NULL,
							   rule_match text DEFAULT NULL,
							   rule_value varchar(255) DEFAULT NULL,
							   rule_number varchar(255) DEFAULT NULL,
							   rule_offset varchar(255) DEFAULT NULL,
							   rule_order_by varchar(255) DEFAULT NULL,
							   rule_order varchar(255) DEFAULT NULL,
							   rule_customfield text DEFAULT NULL,
								PRIMARY KEY (rule_id)
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
					$object->rule_match = unserialize( $object->rule_match );
					$object->rule_customfield = unserialize( $object->rule_customfield );
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
			if ( isset( $_POST['custom_fields']['key'] ) ) {
				foreach ( wp_unslash( $_POST['custom_fields']['key'] ) as $key => $val ) {
					if ( '' == $val ) {
						unset( $_POST['custom_fields']['key'][ $key ] );
					}
				}
			}
			$data['rule_name'] = sanitize_text_field( wp_unslash( $_POST['rule_name'] ) );
			$data['rule_number'] = sanitize_text_field( wp_unslash( $_POST['rule_number'] ) );
			$data['rule_offset'] = sanitize_text_field( wp_unslash( $_POST['rule_offset'] ) );
			$data['rule_order_by'] = sanitize_text_field( wp_unslash( $_POST['rule_order_by'] ) );
			$data['rule_order'] = sanitize_text_field( wp_unslash( $_POST['rule_order'] ) );
			$data['rule_match'] = serialize( wp_unslash( $_POST['rule_match'] ) );
			$data['rule_customfield'] = serialize( wp_unslash( $_POST['custom_fields'] ) );

			$result = FlipperCode_Database::insert_or_update( $this->table, $data, $where );

			if ( false === $result ) {
				$response['error'] = __( 'Something went wrong. Please try again.',WPP_TEXT_DOMAIN );
			} elseif ( $entityID > 0 ) {
				$response['success'] = __( 'Rule updated successfully',WPP_TEXT_DOMAIN );
			} else {
				$response['success'] = __( 'Rule added successfully.',WPP_TEXT_DOMAIN );
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

	}
}
