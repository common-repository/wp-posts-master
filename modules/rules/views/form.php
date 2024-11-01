<?php
/**
 * Template for Add & Edit Rules
 * @author  Flipper Code <hello@flippercode.com>
 * @package Posts
 */

if ( isset( $_REQUEST['_wpnonce'] ) ) {

	$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
	die( 'Cheating...' );
	} else {
		$data = $_POST;
	}
}

global $wpdb;
$modelFactory = new WPP_Model();
$rule_obj = $modelFactory->create_object( 'rules' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['rule_id'] ) ) {
	$rule_obj = $rule_obj->fetch( array( array( 'rule_id', '=', intval( wp_unslash( $_GET['rule_id'] ) ) ) ) );
	$data = (array) $rule_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}

$form  = new WPP_FORM();
$form->set_header( __( 'Add / Edit Post Listing Rule', WPP_TEXT_DOMAIN ), $response, __( 'Add / Edit Post Listing Rule', WPP_TEXT_DOMAIN ), 'wpp_manage_rules' );

$form->add_element( 'text', 'rule_name', array(
	'lable' => __( 'Rule Title', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['rule_name'] ) and ! empty( $data['rule_name'] )) ? $data['rule_name'] : '',
	'desc' => __( 'Enter post listing rule title.', WPP_TEXT_DOMAIN ),
	'required' => true,
	'placeholder' => __( 'Enter Post Listing Rule Title', WPP_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'rule_number', array(
	'lable' => __( '# of Posts', WPP_TEXT_DOMAIN ),
	'value' => $data['rule_number'],
	'desc' => __( 'Enter number of posts you want to display. Leave it blank for displaying all posts.', WPGMP_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="fc-6">',
	'after' => '</div>',
	'default_value' => '',
	'placeholder' => __( 'Enter Number Of Posts To Display', WPP_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'rule_offset', array(
	'lable' => __( 'Posts Offset', WPP_TEXT_DOMAIN ),
	'value' => $data['rule_offset'],
	'desc' => __( 'Enter how many posts you want to skip from starting. Leave "0" if you want to display from starting.', WPGMP_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="fc-6">',
	'after' => '</div>',
	'default_value' => '0',
	'placeholder' => __( 'Provide Starting Offset For Post Listing', WPP_TEXT_DOMAIN ),
));

$period_options = array(
	'title' => __( 'Title',WPP_TEXT_DOMAIN ),
	'date' => __( 'Post Date',WPP_TEXT_DOMAIN ),
	'modified' => __( 'Modified Date',WPP_TEXT_DOMAIN ),
	'comment_count' => __( 'Comments Count',WPP_TEXT_DOMAIN ),
	'author' => __( 'Author',WPP_TEXT_DOMAIN ),
	'rand' => __( 'Random',WPP_TEXT_DOMAIN ),
	);
$form->add_element( 'radio', 'rule_order_by', array(
	'lable' => __( 'Order Posts By', WPP_TEXT_DOMAIN ),
	'radio-val-label' => $period_options,
	'current' => $data['rule_order_by'],
	//'class' => 'chkbox_class ',
	'default_value' => 'random',
	'display_mode' => 'radio-vertical'
));

$form->add_element( 'radio', 'rule_order', array(
	'lable' => __( 'In Order ', WPP_TEXT_DOMAIN ),
	'radio-val-label' => array( 'ASC' => __( 'Ascending',WPP_TEXT_DOMAIN ), 'DESC' => __( 'Decending',WPP_TEXT_DOMAIN ) ),
	'current' => $data['rule_order'],
	'class' => 'chkbox_class ',
	'default_value' => 'ASC',
	'display_mode' => 'radio-vertical'
));

$form->add_element( 'group', 'wpp_basic_rules', array(
	'value' => __( 'Basic Filters For Post Lising', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element( 'checkbox', 'rule_match[wprpw_hasthumbnail]', array(
	'lable' => __( 'Posts Having Featured Image', WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'id' => 'wprpw_hasthumbnail',
	'current' => $data['rule_match']['wprpw_hasthumbnail'],
	'desc' => __( 'Display those posts which has a featured image.', WPP_TEXT_DOMAIN ),
	'class' => 'chkbox_class',
));

$form->add_element( 'checkbox', 'rule_match[ignoresticky]', array(
	'lable' => __( 'Ignore Sticky Posts', WPP_TEXT_DOMAIN ),
	'value' => '1',
	'id' => 'ignoresticky',
	'current' => $data['rule_match']['ignoresticky'],
	'desc' => __( 'Don\'t display sticky posts', WPP_TEXT_DOMAIN ),
	'class' => 'chkbox_class',
));

$all_authors = get_users();
$capable_authors = array();
foreach ( $all_authors as $author ) {

	if ( $author->has_cap( 'edit_posts' ) ) {
		$capable_authors[ $author->ID ] = $author->display_name;
	}
}
$form->add_element( 'multiple_checkbox', 'rule_match[authorname][]', array(
	'lable' => __( 'Author', WPP_TEXT_DOMAIN ),
	'value' => $capable_authors,
	'current' => $data['rule_match']['authorname'],
	'class' => 'chkbox_class ',
	'default_value' => '',
));

$all_categories = get_categories();
$capable_categories = array();
foreach ( $all_categories as $category ) {

		$capable_categories[ $category->term_id ] = $category->name;

}

$form->add_element( 'multiple_checkbox', 'rule_match[posts_categories][]', array(
	'lable' => __( 'Posts Categories', WPP_TEXT_DOMAIN ),
	'value' => $capable_categories,
	'current' => $data['rule_match']['posts_categories'],
	'class' => 'chkbox_class ',
	'default_value' => '',
));

$post_formats = get_theme_support( 'post-formats' );
$all_formats = array();
if ( is_array( $post_formats ) ) {
	foreach ( $post_formats[0] as $format ) {
		$all_formats[ $format ] = ucwords( $format );
	}
}

$form->add_element( 'multiple_checkbox', 'rule_match[post-formats][]', array(
	'lable' => __( 'Post Formats', WPP_TEXT_DOMAIN ),
	'value' => $all_formats,
	'current' => $data['rule_match']['post-formats'],
	'class' => 'chkbox_class ',
	'default_value' => '',
));

$form->add_element( 'text', 'rule_match[post_ids]', array(
	'lable' => __( 'Display Posts By IDs', WPP_TEXT_DOMAIN ),
	'value' => $data['rule_match']['post_ids'],
	'desc' => __( 'Comma seperated Post Id\'s to display. If you provided here ids of post, most of above settings will be ignored.', WPGMP_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="fc-6">',
	'after' => '</div>',
	'default_value' => '',
));

$form->add_element( 'group', 'wpp_exclude_setting', array(
	'value' => __( 'Exclude Posts', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element( 'text', 'rule_match[exclude_post_ids]', array(
	'lable' => __( 'Exclude Posts By IDs', WPP_TEXT_DOMAIN ),
	'value' => $data['rule_match']['exclude_post_ids'],
	'desc' => __( 'Comma seperated Post Id\'s to hide. These posts will be not visible in your listing.', WPP_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="fc-6">',
	'after' => '</div>',
	'default_value' => '',
));

$form->add_element( 'multiple_checkbox', 'rule_match[exclude_authorname][]', array(
	'lable' => __( 'Author', WPP_TEXT_DOMAIN ),
	'value' => $capable_authors,
	'current' => $data['rule_match']['exclude_authorname'],
	'desc' => __( 'Posts by selected authors will be not visible in your listing.', WPP_TEXT_DOMAIN ),
	'class' => 'chkbox_class ',
	'default_value' => '',
));

$form->add_element( 'multiple_checkbox', 'rule_match[exclude_posts_categories][]', array(
	'lable' => __( 'Posts Categories', WPP_TEXT_DOMAIN ),
	'value' => $capable_categories,
	'current' => $data['rule_match']['exclude_posts_categories'],
	'class' => 'chkbox_class ',
	'default_value' => '',
	'desc' => __( 'Posts in selected categories will be not visible in your listing.', WPP_TEXT_DOMAIN ),

));


$form->add_element( 'group', 'wpp_dates_filter', array(
	'value' => __( 'Date(s) Filter', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element( 'checkbox', 'rule_match[date_filters]', array(
	'lable' => __( 'Apply Date(s) Filters', WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'id' => 'date_filters',
	'current' => $data['rule_match']['date_filters'],
	'desc' => __( 'Enable date(s) range filters.', WPP_TEXT_DOMAIN ),
	'class' => 'chkbox_class switch_onoff',
	'data' => array( 'target' => '.wpp_dates_filters' ),
));

$period_options = array(
	'this_week' => __( 'This Week',WPP_TEXT_DOMAIN ),
	'last_week' => __( 'Last Week',WPP_TEXT_DOMAIN ),
	'today' => __( 'Today',WPP_TEXT_DOMAIN ),
	'yesterday' => __( 'Yesterday',WPP_TEXT_DOMAIN ),
	'this_month' => __( 'This Month',WPP_TEXT_DOMAIN ),
	'last_month' => __( 'Last Month',WPP_TEXT_DOMAIN ),
	);
$form->add_element( 'radio', 'rule_match[period]', array(
	'lable' => __( 'Choose Time Span To Filter', WPP_TEXT_DOMAIN ),
	'radio-val-label' => $period_options,
	'current' => $data['rule_match']['period'],
	'class' => 'chkbox_class wpp_dates_filters ',
	'default_value' => '',
	'show' => 'false',
	'display_mode' => 'radio-vertical'
));


$form->add_element( 'submit', 'save_entity_data', array(
	'value' => __( 'Save Rule',WPP_TEXT_DOMAIN ),
));
$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {

	$form->add_element( 'hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['rule_id'] ) ),
	));
}
$form->render();
