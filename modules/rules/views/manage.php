<?php
/**
 * Class wpp_Rule_Table File
 * @author Flipper Code <hello@flippercode.com>
 * @package Posts
 */

$form  = new WPP_FORM();
echo $form->show_header();


if ( class_exists( 'WP_List_Table_Helper' ) and ! class_exists( 'wpp_Rule_Table' ) ) {

	/**
	 * Class wpp_Rule_Table to display rules for manage.
	 * @author Flipper Code <hello@flippercode.com>
	 * @package Posts
	 */
	class wpp_Rule_Table extends WP_List_Table_Helper {
		/**
		 * Intialize class constructor.
		 * @param array $tableinfo Rules Table Informaiton.
		 */
		public function __construct($tableinfo) {
			parent::__construct( $tableinfo );
		}
			/**
			 * Output for Shortcode column.
			 * @param array $item Map Row.
			 */
			public function column_rule_number($item) {
			if ( '' == $item->rule_number ) {
				echo __( 'All',WPP_TEXT_DOMAIN );
			} else { 			echo $item->rule_number; }
			}
	}


	global $wpdb;
	$columns   = array( 'rule_name' => 'Rule Name','rule_number' => '# of Posts','rule_order_by' => 'Order By','rule_order' => 'Order' );
	$sortable  = array( 'rule_name','location_address' );
	$tableinfo = array(
	'table' => WPP_TBL_RULES,
	'textdomain' => WPP_TEXT_DOMAIN,
	'singular_label' => 'rule',
	'plural_label' => 'rules',
	'admin_listing_page_name' => 'wpp_manage_rules',
	'admin_add_page_name' => 'wpp_form_rules',
	'primary_col' => 'rule_id',
	'columns' => $columns,
	'sortable' => $sortable,
	'per_page' => 200,
	'actions' => array( 'edit','delete' ),
	'col_showing_links' => 'rule_name',
	);
	return new wpp_Rule_Table( $tableinfo );

}
?>
