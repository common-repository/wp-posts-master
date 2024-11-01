<?php
/**
 * Class wpp_Layout_Table File
 * @author Flipper Code <hello@flippercode.com>
 * @package Posts
 */
 
$form  = new WPP_Form();
echo $form->show_header();
  
if ( class_exists( 'WP_List_Table_Helper' ) and ! class_exists( 'wpp_Layout_Table' ) ) {

	/**
	 * Class wpp_Rule_Table to display rules for manage.
	 * @author Flipper Code <hello@flippercode.com>
	 * @package Posts
	 */
	class wpp_Layout_Table extends WP_List_Table_Helper {
		/**
		 * Intialize class constructor.
		 * @param array $tableinfo Rules Table Informaiton.
		 */
		public function __construct($tableinfo) {
			parent::__construct( $tableinfo );
		}
			/**
			 * Output for Shortcode column.
			 * @param array $item Layout Row.
			 */
			public function column_shortcode($item) {

				 echo '[wprpw_display_layout id='.$item->layout_id.']';

			}

			/**
			 * Display Rules.
			 * @param array $item Layout Row.
			 */
			public function column_layout_rule_id($item) {
				$modelFactory = new WPP_Model();
				$rule_obj = $modelFactory->create_object( 'rules' );
				$all_rules = $rule_obj->fetch();
				$selected_rules = unserialize( $item->layout_rule_id );
				if ( ! is_array( $selected_rules ) ) {
					$selected_rules = array();
				}
				$display_rules = array();
				if ( is_array( $all_rules ) ) {
					foreach ( $all_rules as $rule ) {
						if ( in_array( $rule->rule_id,$selected_rules ) ) {
							$display_rules[] = $rule->rule_name;
						}
					}
				}
				echo implode( ',', $display_rules );
			}
			/**
			 * Clone of the map.
			 */
			public function copy() {
				$layout_id = intval( wp_unslash( $_GET['layout_id'] ) );
				$modelFactory = new WPP_Model();
				$layout_obj = $modelFactory->create_object( 'layout' );
				$layout_obj->copy( $layout_id );
				$this->prepare_items();
				$this->listing();
			}
	}


	global $wpdb;
	$columns   = array( 'layout_title' => __( 'Template Title',WPP_TEXT_DOMAIN ),'layout_rule_id' => __( 'Selected Rules',WPP_TEXT_DOMAIN ),'shortcode' => __( 'Shortcode',WPP_TEXT_DOMAIN ) );
	$sortable  = array( 'layout_title' );
	$tableinfo = array(
	'table' => WPP_TBL_LAYOUT,
	'textdomain' => WPP_TEXT_DOMAIN,
	'singular_label' => 'template',
	'plural_label' => 'templates',
	'admin_listing_page_name' => 'wpp_manage_layout',
	'admin_add_page_name' => 'wpp_form_layout',
	'primary_col' => 'layout_id',
	'columns' => $columns,
	'sortable' => $sortable,
	'per_page' => 200,
	'actions' => array( 'edit','delete','copy' ),
	'col_showing_links' => 'layout_title',
	);
	return new wpp_Layout_Table( $tableinfo );

}
?>
