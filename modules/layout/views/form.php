<?php
/**
 * Template for Add & Edit Template
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
$layout_obj = $modelFactory->create_object( 'layout' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['layout_id'] ) ) {
	$layout_obj = $layout_obj->fetch( array( array( 'layout_id', '=', intval( wp_unslash( $_GET['layout_id'] ) ) ) ) );
	$data = (array) $layout_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}
$rule_obj = $modelFactory->create_object( 'rules' );
$rule_obj = $rule_obj->fetch();
$all_rules = array();
if ( is_array( $rule_obj ) ) {
	foreach ( $rule_obj as $rule ) {
		$all_rules[ $rule->rule_id ] = $rule->rule_name;
	}
}
$obj = new FlipperCode_Layout();
$preview_content = $obj->wpp_load_template( $data );
if ( isset( $data['layout_post_setting']['source_code'] ) ) {
	$template_source_code = stripcslashes( $data['layout_post_setting']['source_code'] );
} else {
	$template_source_code = '';
}

//$form  = new FlipperCode_HTML_Markup();
$form  = new WPP_FORM();

$obj = new FlipperCode_Layout();
$all_sample_designs  = $obj->users_templates();
$form->set_header( __( 'Template Settings', WPP_TEXT_DOMAIN ), $response, __( 'Manage Layouts', WPP_TEXT_DOMAIN ), 'wpp_manage_rules' );
$form->spliter = '<div class="fc-12"><div class="switch_tabs">

<ul class="nav nav-tabs">
    <li ><a data-toggle="tab" href="#wpp_designs">'.__( 'Designs', WPP_TEXT_DOMAIN ).'</a></li>
    <li class="active" ><a data-toggle="tab" href="#setting">'.__( 'Settings', WPP_TEXT_DOMAIN ).'</a></li>
    <li><a data-toggle="tab" href="#source_code">'.__( 'Source Code', WPP_TEXT_DOMAIN ).'</a></li>
    <li><a data-toggle="tab" class="wpp_refresh" href="#preview">'.__( 'Preview', WPP_TEXT_DOMAIN ).'</a></li>
</ul>
<input type="button" class="wpp_settings_save fc-btn" name="save_source_code" value="'.__( 'Save Template', WPP_TEXT_DOMAIN ).'">

<div class="tab-content">
 	<div id="wpp_designs" class="tab-pane">
       <div class="wpp_design_sample">
       '.$obj->wpp_template_preview( array( 'template_id' => 1, 'thumbnail' => 'left' ) ).'
       </div>
       <div class="wpp_design_sample">
       '.$obj->wpp_template_preview( array( 'template_id' => 1, 'thumbnail' => 'right' ) ).'
       </div>
       <br style="clear:both">
    </div>
    <div id="setting" class="tab-pane fade in active ">
        %form
    </div>
    <div id="source_code" class="tab-pane">
        <div class="row">
        <div class="fc-6">
        <textarea class="wpp_source_code">'.esc_textarea( $template_source_code ).'</textarea>
        <br><br>
        </div>
        <div class="fc-6">
        <div class="alert alert-info fc-title-blue">'.__( 'You can alter existing design or create your own design using following placeholders.', WPP_TEXT_DOMAIN ).'</div>
        <ul class="wpp_placeholders">
        <li>{title}</li><li>{post_link}</li><li>{content}</li><li>{thumbnail}</li><li>{read_more}</li><li>{date}</li><li>{author}</li><li>{comments}</li><li>{categories}</li><li>{tags}</li>
        </ul>
        </div>
        </div>
    </div>
    <div id="preview" class="tab-pane fade">
    	<div class="posts_preview">'.$preview_content['html'].'</div>
    </div>
</div>

</div></div>';
$form->add_element( 'text', 'layout_title', array(
	'lable' => __( 'Layout Title', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_title'] ) and ! empty( $data['layout_title'] )) ? $data['layout_title'] : '',
	'required' => true,
	'placeholder' => __( 'Layout Title', WPP_TEXT_DOMAIN ),
));

$form->add_element('multiple_checkbox','layout_rule_id[]',array(
	'lable' => __( 'Apply Rules',WPP_TEXT_DOMAIN ),
	'value' => $all_rules,
	'required' => true,
	'current' => (isset( $data['layout_rule_id'] ) and ! empty( $data['layout_rule_id'] )) ? $data['layout_rule_id'] : '',
	));

$form->add_element( 'group', 'wpp_title_setting', array(
	'value' => __( 'Title Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_title]',array(
	'lable' => __( 'Hide Title',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_title'] ) and ! empty( $data['layout_post_setting']['hide_title'] )) ? $data['layout_post_setting']['hide_title'] : '',
	'default_value' => 'false',
	));
$form->add_element('checkbox','layout_post_setting[post_link]',array(
	'lable' => __( 'Link to Post',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['post_link'] ) and ! empty( $data['layout_post_setting']['post_link'] )) ? $data['layout_post_setting']['post_link'] : 'true',
	'default_value' => 'true',
	));

$form->add_element( 'text', 'layout_post_setting[title_html]', array(
	'lable' => __( 'Title Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['title_html'] ) and ! empty( $data['layout_post_setting']['title_html'] )) ? $data['layout_post_setting']['title_html'] : '',
	'desc' => __( 'Modify the title container.', WPP_TEXT_DOMAIN ),
	'default_value' => '<h3 itemprop="name">%s</h3>',
));

$form->add_element( 'group', 'wpp_content_setting', array(
	'value' => __( 'Content Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_excerpt]',array(
	'lable' => __( 'Hide Content',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_excerpt'] ) and ! empty( $data['layout_post_setting']['hide_excerpt'] )) ? $data['layout_post_setting']['hide_excerpt'] : '',
	'default_value' => 'false',
	));

$form->add_element( 'text', 'layout_post_setting[content_html]', array(
	'lable' => __( 'Content Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['content_html'] ) and ! empty( $data['layout_post_setting']['content_html'] )) ? $data['layout_post_setting']['content_html'] : '',
	'desc' => __( 'Modify the content container.', WPP_TEXT_DOMAIN ),
	'default_value' => '%s',
));

$form->add_element( 'select', 'layout_post_setting[content_display]', array(
	'lable' => __( 'Content Show', WPP_TEXT_DOMAIN ),
	'options' => array( 'excerpt' => __( 'Excerpt',WPP_TEXT_DOMAIN ), 'content' => __( 'Full',WPP_TEXT_DOMAIN ) ),
	'current' => (isset( $data['layout_post_setting']['content_display'] ) and ! empty( $data['layout_post_setting']['content_display'] )) ? $data['layout_post_setting']['content_display'] : '',
	'select2' => 'false'
));

$form->add_element( 'text', 'layout_post_setting[post_content_limit]', array(
	'lable' => __( 'Content Limit', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['post_content_limit'] ) and ! empty( $data['layout_post_setting']['post_content_limit'] )) ? $data['layout_post_setting']['post_content_limit'] : '',
	'desc' => __( 'Modify the content limit.', WPP_TEXT_DOMAIN ),
	'default_value' => '55',
));

$form->add_element('checkbox','layout_post_setting[enable_shortcode]',array(
	'lable' => __( 'Render Shortcode',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['enable_shortcode'] ) and ! empty( $data['layout_post_setting']['enable_shortcode'] )) ? $data['layout_post_setting']['enable_shortcode'] : '',
	'default_value' => 'true',
	));

$form->add_element( 'group', 'wpp_featured_image_setting', array(
	'value' => __( 'Featured Image Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_thumbnail]',array(
	'lable' => __( 'Hide Thumbnail',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_thumbnail'] ) and ! empty( $data['layout_post_setting']['hide_thumbnail'] )) ? $data['layout_post_setting']['hide_thumbnail'] : '',
	'default_value' => 'false',
	));

$form->add_element('checkbox','layout_post_setting[thumbnail_link]',array(
	'lable' => __( 'Link to Post',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['thumbnail_link'] ) and ! empty( $data['layout_post_setting']['thumbnail_link'] )) ? $data['layout_post_setting']['thumbnail_link'] : 'true',
	'default_value' => 'true',
	));

$form->add_element( 'text', 'layout_post_setting[thumb_width]', array(
	'lable' => __( 'Thumbnail Width', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['thumb_width'] ) and ! empty( $data['layout_post_setting']['thumb_width'] )) ? $data['layout_post_setting']['thumb_width'] : '',
	'desc' => __( 'Modify the thumbnail width.', WPP_TEXT_DOMAIN ),
	'default_value' => '',
));

$form->add_element( 'text', 'layout_post_setting[thumb_height]', array(
	'lable' => __( 'Thumbnail Height', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['thumb_height'] ) and ! empty( $data['layout_post_setting']['thumb_height'] )) ? $data['layout_post_setting']['thumb_height'] : '',
	'desc' => __( 'Modify the thumbnail height.', WPP_TEXT_DOMAIN ),
	'default_value' => '',
));

$form->add_element( 'select', 'layout_post_setting[thumb_size]', array(
	'lable' => __( 'Thumbnail Size', WPP_TEXT_DOMAIN ),
	'options' => array(
	'thumbnail' => __( 'Thumbnail',WPP_TEXT_DOMAIN ),
	'medium' => __( 'Medium',WPP_TEXT_DOMAIN ),
		'large' => __( 'Large',WPP_TEXT_DOMAIN ),
		'full' => __( 'Full',WPP_TEXT_DOMAIN ),
		),
	'current' => (isset( $data['layout_post_setting']['thumb_size'] ) and ! empty( $data['layout_post_setting']['thumb_size'] )) ? $data['layout_post_setting']['thumb_size'] : '',
	'select2' => 'false'
));

$form->add_element( 'select', 'layout_post_setting[thumb_position]', array(
	'lable' => __( 'Thumbnail Position', WPP_TEXT_DOMAIN ),
	'options' => array(
	'top' => __( 'Top',WPP_TEXT_DOMAIN ),
	'left' => __( 'Left',WPP_TEXT_DOMAIN ),
	'right' => __( 'Right',WPP_TEXT_DOMAIN ),
	),
	'current' => (isset( $data['layout_post_setting']['thumb_position'] ) and ! empty( $data['layout_post_setting']['thumb_position'] )) ? $data['layout_post_setting']['thumb_position'] : '',
	'select2' => 'false'
));

$form->add_element( 'group', 'wpp_read_more_setting', array(
	'value' => __( 'Read More Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_read_more_link]',array(
	'lable' => __( 'Hide Read More',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_read_more_link'] ) and ! empty( $data['layout_post_setting']['hide_read_more_link'] )) ? $data['layout_post_setting']['hide_read_more_link'] : '',
	'default_value' => 'false',
	));

$form->add_element( 'text', 'layout_post_setting[readmore_html]', array(
	'lable' => __( 'Read More Text', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['readmore_html'] ) and ! empty( $data['layout_post_setting']['readmore_html'] )) ? $data['layout_post_setting']['readmore_html'] : '',
	'desc' => __( 'Modify the read more container.', WPP_TEXT_DOMAIN ),
	'default_value' => __( 'Read More',WPP_TEXT_DOMAIN ),
));


$form->add_element( 'group', 'wpp_date_setting', array(
	'value' => __( 'Date Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_publish_date]',array(
	'lable' => __( 'Hide Date',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_publish_date'] ) and ! empty( $data['layout_post_setting']['hide_publish_date'] )) ? $data['layout_post_setting']['hide_publish_date'] : '',
	'default_value' => 'false',
	));

$form->add_element( 'text', 'layout_post_setting[date_format]', array(
	'lable' => __( 'Date Format :', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['date_format'] ) and ! empty( $data['layout_post_setting']['date_format'] )) ? $data['layout_post_setting']['date_format'] : '',
	'desc' => __( 'Modify the date format.', WPP_TEXT_DOMAIN ),
	'default_value' => 'F,d Y',
));

$form->add_element( 'text', 'layout_post_setting[date_html]', array(
	'lable' => __( 'Date Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['date_html'] ) and ! empty( $data['layout_post_setting']['date_html'] )) ? $data['layout_post_setting']['date_html'] : '',
	'desc' => __( 'Modify the date container.', WPP_TEXT_DOMAIN ),
	'default_value' => 'Publish on <span itemprop="datePublished">%s</span>',
));


$form->add_element( 'group', 'wpp_author_setting', array(
	'value' => __( 'Author Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_author]',array(
	'lable' => __( 'Hide Author',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_author'] ) and ! empty( $data['layout_post_setting']['hide_author'] )) ? $data['layout_post_setting']['hide_author'] : '',
	'default_value' => 'false',
	));

$form->add_element('checkbox','layout_post_setting[author_link]',array(
	'lable' => __( 'Author Link',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['author_link'] ) and ! empty( $data['layout_post_setting']['author_link'] )) ? $data['layout_post_setting']['author_link'] : '',
	'default_value' => 'false',
	));

$form->add_element( 'text', 'layout_post_setting[author_html]', array(
	'lable' => __( 'Author Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['author_html'] ) and ! empty( $data['layout_post_setting']['author_html'] )) ? $data['layout_post_setting']['author_html'] : '',
	'desc' => __( 'Modify the author container.', WPP_TEXT_DOMAIN ),
	'default_value' => '<span itemprop="author">by %s</span>',
));

$form->add_element( 'select', 'layout_post_setting[authr_name]', array(
	'lable' => __( 'Display', WPP_TEXT_DOMAIN ),
	'default_value' => 'display_name',
	'options' => array(
	'user_nicename' => __( 'Nice Name',WPP_TEXT_DOMAIN ),
	'display_name' => __( 'Display Name',WPP_TEXT_DOMAIN ),
	'first_name' => __( 'First Name',WPP_TEXT_DOMAIN ),
	'last_name' => __( 'Last Name',WPP_TEXT_DOMAIN ),
	'user_login' => __( 'User Name',WPP_TEXT_DOMAIN ),
	),
	'current' => (isset( $data['layout_post_setting']['authr_name'] ) and ! empty( $data['layout_post_setting']['authr_name'] )) ? $data['layout_post_setting']['authr_name'] : '',
	'select2' => 'false'
));


$form->add_element( 'group', 'wpp_comments_setting', array(
	'value' => __( 'Comments Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_comments]',array(
	'lable' => __( 'Hide Comments',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_comments'] ) and ! empty( $data['layout_post_setting']['hide_comments'] )) ? $data['layout_post_setting']['hide_comments'] : '',
	'default_value' => 'false',
	));

$form->add_element('checkbox','layout_post_setting[comments_link]',array(
	'lable' => __( 'Comments Link',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['comments_link'] ) and ! empty( $data['layout_post_setting']['comments_link'] )) ? $data['layout_post_setting']['comments_link'] : '',
	'default_value' => 'false',
	));


$form->add_element( 'text', 'layout_post_setting[comments_html]', array(
	'lable' => __( 'Comments Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['comments_html'] ) and ! empty( $data['layout_post_setting']['comments_html'] )) ? $data['layout_post_setting']['comments_html'] : '',
	'default_value' => '<span itemprop="comments">'.__( 'Comments',WPP_TEXT_DOMAIN ).'(%s)</span>',
));

$form->add_element( 'group', 'wpp_categories_setting', array(
	'value' => __( 'Categories Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[hide_post_categories]',array(
	'lable' => __( 'Hide Category',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_post_categories'] ) and ! empty( $data['layout_post_setting']['hide_post_categories'] )) ? $data['layout_post_setting']['hide_post_categories'] : '',
	'default_value' => 'false',
	));

$form->add_element('checkbox','layout_post_setting[category_link]',array(
	'lable' => __( 'Category Link',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['category_link'] ) and ! empty( $data['layout_post_setting']['category_link'] )) ? $data['layout_post_setting']['category_link'] : '',
	'default_value' => 'true',
	));


$form->add_element( 'text', 'layout_post_setting[category_html]', array(
	'lable' => __( 'Category Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['category_html'] ) and ! empty( $data['layout_post_setting']['category_html'] )) ? $data['layout_post_setting']['category_html'] : '',
	'desc' => __( 'Modify the category container.', WPP_TEXT_DOMAIN ),
	'default_value' => 'Categories: %s',
));

$form->add_element( 'group', 'wpp_tags_setting', array(
	'value' => __( 'Tags Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));


$form->add_element('checkbox','layout_post_setting[hide_post_tags]',array(
	'lable' => __( 'Hide Tags',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['hide_post_tags'] ) and ! empty( $data['layout_post_setting']['hide_post_tags'] )) ? $data['layout_post_setting']['hide_post_tags'] : '',
	'default_value' => 'false',
	));

$form->add_element('checkbox','layout_post_setting[tags_link]',array(
	'lable' => __( 'Tags Link',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['tags_link'] ) and ! empty( $data['layout_post_setting']['tags_link'] )) ? $data['layout_post_setting']['tags_link'] : '',
	'default_value' => 'true',
	));



$form->add_element( 'text', 'layout_post_setting[tags_html]', array(
	'lable' => __( 'Tags Wrapper', WPP_TEXT_DOMAIN ),
	'value' => (isset( $data['layout_post_setting']['tags_html'] ) and ! empty( $data['layout_post_setting']['tags_html'] )) ? $data['layout_post_setting']['tags_html'] : '',
	'desc' => __( 'Modify the tags container.', WPP_TEXT_DOMAIN ),
	'default_value' => 'Tags: %s',
));

$form->add_element( 'group', 'wpp_pagination_setting', array(
	'value' => __( 'Pagination Settings', WPP_TEXT_DOMAIN ),
	'before' => '<div class="fc-11">',
	'after' => '</div>',
));

$form->add_element('checkbox','layout_post_setting[pagination]',array(
	'lable' => __( 'Apply Pagination',WPP_TEXT_DOMAIN ),
	'value' => 'true',
	'current' => (isset( $data['layout_post_setting']['pagination'] ) and ! empty( $data['layout_post_setting']['pagination'] )) ? $data['layout_post_setting']['pagination'] : '',
	'default_value' => 'false',
	'class' => 'chkbox_class switch_onoff',
	'data' => array( 'target' => '.wpp_pagination_setting' ),
	));

$form->add_element( 'radio', 'layout_post_setting[pagination_style]', array(
	'lable' => __( 'Pagination Style', WPP_TEXT_DOMAIN ),
	'radio-val-label' => array( 'number' => __( 'Pagination',WPP_TEXT_DOMAIN ) ),
	'current' => $data['layout_post_setting']['pagination_style'],
	'class' => 'chkbox_class wpp_pagination_setting',
	'default_value' => 'number',
	'show' => 'false',
));


$form->add_element( 'text', 'layout_post_setting[per_page]', array(
	'lable' => __( 'Posts Per Page', WPP_TEXT_DOMAIN ),
	'value' => $data['layout_post_setting']['per_page'],
	'desc' => __( 'Set posts to display per page. Default is 10.', WPP_TEXT_DOMAIN ),
	'class' => 'form-control wpp_pagination_setting',
	'show' => 'false',
	'default_value' => 10,
));

$form->add_element( 'hidden', 'layout_post_setting[source_code]', array(
	'value' => esc_textarea( $template_source_code ),
));

$form->add_element( 'hidden', 'layout_post_setting[original_source_code]', array(
	'value' => esc_textarea( $preview_content['source_code'] ),
));

$form->add_element( 'submit', 'save_entity_data', array(
	'value' => __( 'Save Template',WPP_TEXT_DOMAIN ),
));
$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));

$form->add_element( 'hidden', 'layout_type', array(
	'value' => $data['layout_type'],
	'default_value' => '1',
));


if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {

	$form->add_element( 'hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['layout_id'] ) ),
	));
}
$form->render();
