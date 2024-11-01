<?php
if ( ! class_exists( 'WPP_FORM' ) ) {


	class WPP_FORM extends FlipperCode_HTML_Markup{

		function __construct($options = array()) {
			
			$premium_features = "<ul class='fc-pro-features'>
			<li>Supported custom posts type. it works with posts type created using plugins or your own programming.</li>
			<li>Filter by taxonomies. Supported unlimited taxonomies.</li>
			<li>Filter Posts by Terms Name. You can setup hierarchy between custom posts type, taxonomies and terms.</li>
			<li>Filter Posts by custom dates.  You can decide Start & End Date.</li>
			<li>Filter Posts by last N days.</li>
			<li>Filter posts by custom field name and it’s value. Very useful feature where you need not to worry about any programming.</li>
			<li>Combine multiple custom fields condition together to build complex queries.</li>
			<li>8 Beautiful Posts design.</li>
			<li>Ability to open post title, read more, thumbnail, category link, tag link & author link in a new tab.</li>
			<li>Setup default featured image if no feature image is available for the post.</li>
			<li>Display Posts in grid. You can display posts in Single Column, 2  Columns, 3 Columns, 4 Columns, 5 Columns & 6 Columns.</li>
			<li>Grid is fully responsive.</li> 
			<li>Apply lazy loading on the posts listing. Implement ‘Load More’ feature to fetch next page posts listing using ajax.</li>
			<li>Display Posts in Carousel. You can control carousel using backend settings according to your needs.</li> 
			<li>Widget Supported.</li>
			<li>Display Carousel listing in widget as well.</li> 
			<li>Display ‘Load More’ pagination in widget.</li>
     		</ul>";


			$productInfo = array('productName' => __('WP Post Master',WPP_TEXT_DOMAIN),
                        'productSlug' => __('wp-posts-master',WPP_TEXT_DOMAIN),   
                        'productTagLine' => __('Display posts on your wordpress site in matter of seconds with ease. Simple to complex rule buildiung',MTOP_TEXT_DOMAIN),
                        'productTextDomain' => WPP_TEXT_DOMAIN,
                        'productIconImage' => WPP_URL.'core/core-assets/images/wp-poet.png',
                        'productVersion' => WPP_VERSION,
                        'docURL' => 'http://guide.flippercode.com/postslisting/',
                         'videoURL' => 'https://www.youtube.com/playlist?list=PLlCp-8jiD3p31hemWeu_b4Vnt5aF0f49U',
                        'demoURL' => 'https://www.flippercode.com/product/wp-posts-pro/',
                        'productImagePath' => WPP_URL.'core/core-assets/product-images/',
                        'productSaleURL' => 'https://codecanyon.net/item/blog-layout-grid-for-wordpress/7292195',
						'multisiteLicence' => 'https://codecanyon.net/item/blog-layout-grid-for-wordpress/7292195?license=extended&open_purchase_for_item_id=7292195&purchasable=source',
						'premium_features' => $premium_features,
            );
    
			$productInfo = array_merge($productInfo, $options);
			parent::__construct($productInfo);

		}

	}
	
}
