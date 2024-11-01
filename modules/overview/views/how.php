<?php
/**
 * Posts Pro Overviews.
 * @package Posts
 * @author Flipper Code <flippercode>
 **/

$form  = new WPP_FORM();
echo $form->show_header();
  
?>
<div class="flippercode-ui">
 <div class="fc-main fc-docs">
  <div class="fc-container">
     <div class="fc-divider ">
		 <div class="fc-back fc-how">
    <div class="fc-12">
           
			<h4 class="alert alert-info fc-title-blue"> <?php _e( 'How it Works?',WPGMP_TEXT_DOMAIN ); ?> </h4>
                <div class="wpgmp-overview">
                    <?php
                    $link = sprintf( wp_kses( __( 'You can display posts listing based on various useful filters in just two steps. First you need to create your rules to get posts and then display them using readymade templates or create your own template with help of placeholders.', WPGMP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
                    echo $link;?>
                </div>
				
		   <h4 class="alert alert-info fc-title-blue"> <?php _e( 'How to create your First Post Listing?',WPP_TEXT_DOMAIN ); ?> </h4>
           <div class="wpgmp-overview">
            <ol>
                <li><?php
                $url = admin_url( 'admin.php?page=wpp_form_rules' );
                $link = sprintf( wp_kses( __( 'Use our easy to use <a target="_blank" href="%s">rules builder</a> to filter the posts which you want to display. All these rules will be available to choose when you create your template.', WPP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array(), 'target' => '_blank' ) ) ), esc_url( $url ) );
                echo $link;?>
                </li>
                <li><?php
                $url = admin_url( 'admin.php?page=wpp_form_layout' );
                $link = sprintf( wp_kses( __( 'Now <a target="_blank" href="%s">click here</a> to create a template. You can create as many templates you want to add. Using shortcode, you can add posts listing on posts/pages or sidebar.', WPP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array(), 'target' => '_blank' ) ) ), esc_url( $url ) );
                echo $link;?>
                </li>
                <li><?php
                $url = admin_url( 'admin.php?page=wpp_manage_layout' );
                $link = sprintf( wp_kses( __( 'When done with administrative tasks, you can display posts listing on posts/pages Using shortcode. Each template will have own shortcode so go to Manage Templates and copy your shortcode and paste on any page.', WPP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
                echo $link;?>
                </li>
            </ol>
        </div>
        <h4 class="alert alert-info fc-title-blue">
                <?php _e( 'Content Place holders', WPP_TEXT_DOMAIN ) ?>
            </h4>
        <div class="wpgmp-overview">
        
                <?php _e( 'Use following placeholder to create templates and display various data without any programming.', WPP_TEXT_DOMAIN ) ?>
            <ul>
                <li><b><?php _e( 'Post Title',WPP_TEXT_DOMAIN ); ?> :</b><code>{title}</code></li>
                <li><b><?php _e( 'Post Links',WPP_TEXT_DOMAIN ); ?> :</b><code>{post_link}</code></li>
                <li><b><?php _e( 'Post Content',WPP_TEXT_DOMAIN ); ?> :</b><code>{content}</code></li>
                <li><b><?php _e( 'Featured Image',WPP_TEXT_DOMAIN ); ?> :</b><code>{thumbnail}</code></li>
                <li><b><?php _e( 'Read More Link',WPP_TEXT_DOMAIN ); ?> :</b><code>{read_more}</code></li>
                <li><b><?php _e( 'Post Date',WPP_TEXT_DOMAIN ); ?> :</b><code>{date}</code></li>
                <li><b><?php _e( 'Author Name',WPP_TEXT_DOMAIN ); ?> :</b><code>{author}</code></li>
                <li><b><?php _e( 'Post Comments',WPP_TEXT_DOMAIN ); ?> :</b><code>{comments}</code></li>
                <li><b><?php _e( 'Post Categories',WPP_TEXT_DOMAIN ); ?> :</b><code>{categories}</code></li>
                <li><b><?php _e( 'Post Tags',WPP_TEXT_DOMAIN ); ?> :</b><code>{tags}</code></li>
            </ul>
        </div>
         <a target="_blank" href="http://codecanyon.net/item/wordpress-posts-listing-plugin/7292195"><h4 class="alert alert-info fc-title-blue"> <?php _e( 'Additional Features Present In Pro Version :',WPGMP_TEXT_DOMAIN ); ?> </h4></a>
          <div class="wpgmp-overview">
            
<ol>
<li> Supported custom posts type. it works with posts type created using plugins or your own programming.</li>
<li> Filter by taxonomies. Supported unlimited taxonomies. </li>
<li> Filter Posts by Terms Name. You can setup hierarchy between custom posts type, taxonomies and terms. </li>
<li> Filter Posts by custom dates.  You can decide Start &amp; End Date. </li>
<li> Filter Posts by last N days. </li>
<li> Filter posts by custom field name and it’s value. Very useful feature where you need not to worry about any programming.
</li><li> Combine multiple custom fields condition together to build complex queries. </li>
<li> 8 Beautiful Posts design. </li>
<li> Ability to open post title, read more, thumbnail, category link, tag link &amp; author link in a new tab.</li>
<li> Setup default featured image if no feature image is available for the post.</li>
<li> Display Posts in grid. You can display posts in Single Column, 2  Columns, 3 Columns, 4 Columns, 5 Columns &amp; 6 Columns.</li>
<li> Grid is fully responsive. </li>
<li> Apply lazy loading on the posts listing. Implement ‘Load More’ feature to fetch next page posts listing using ajax.</li>
<li>Display Posts in Carousel. You can control carousel using backend settings according to your needs. </li>
<li> Widget Supported.</li>
<li> Display Carousel listing in widget as well. </li>
<li> Display ‘Load More’ pagination in widget.</li>
</ol>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
