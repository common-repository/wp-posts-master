<?php
/**
 *  Posts Utility.
 *  @package Posts
 *  @author Flipper Code <hello@flippercode.com>
 */

if ( ! class_exists( 'FlipperCode_Posts' ) ) {

	/**
	 * Import/Export Class
	 */
	class FlipperCode_Posts {
		/**
		 * Has Thumbnail.
		 * @var boolean
		 */
		var $has_thumbnail = false;
		/**
		 * Ignore Sticky Posts.
		 * @var boolean
		 */
		var $ignore_sticky_posts = false;
		/**
		 * Post Offset.
		 * @var integer
		 */
		var $offset = 0;
		/**
		 * Posts Per Page.
		 * @var integer
		 */
		var $posts_per_page = -1;
		/**
		 * Order By.
		 * @var string
		 */
		var $orderby = 'none';
		/**
		 * Ascending or Decending Order.
		 * @var string
		 */
		var $order = '';
		/**
		 * Post Status.
		 * @var string
		 */
		var $post_status = '';
		/**
		 * Array of Post's ID.
		 * @var array
		 */
		var $post__in = array();
		/**
		 * WP_QUERY Arguments.
		 * @var array
		 */
		var $args = array();
		/**
		 * Initialize Posts.
		 */
		public function __construct() {
		}
		/**
		 * Get Args Options for Period.
		 * @param  int   $period Period ID.
		 * @param  array $dates  $date Range.
		 */
		function date_range($period, $dates = array()) {
			$todaydate = date( 'Y-m-d' );
			switch ( $period ) {
				case 'this_week' : 	$week = date( 'W' );
					$year = date( 'Y' );
								$this->args['w'] = $week;
								$this->args['year'] = $year;
			 					break;
				case 'last_week' :
								$previous_week = strtotime( '-1 week +1 day' );

								$start_week = strtotime( 'last sunday midnight',$previous_week );

								$wprpw_rule_startdate = date( 'Y-m-d',$start_week );
								$wprpw_rule_enddate = date( 'Y-m-d',strtotime( 'next saturday',$start_week ) );
			 					break;
				case 'today' : 		$day = date( 'j' );
								$week = date( 'W' );
								$year = date( 'Y' );
								$this->args['day'] = $day;
								$this->args['w'] = $week;
								$this->args['year'] = $year;
			 					break;
				case 'yesterday' : 	$day = date( 'j' ) -1;
								$month = date( 'n' );
								$year = date( 'Y' );
								$this->args['day'] = $day;
								$this->args['monthnum'] = $month;
								$this->args['year'] = $year;
			 					break;
				case 'this_month' : $month = date( 'n' );
								$year = date( 'Y' );
								$this->args['monthnum'] = $month;
								$this->args['year'] = $year;
			 					break;
				break;
				case 'last_month' :
								$wprpw_rule_startdate = date( 'Y-m-d', strtotime( 'first day of last month' ) );
								$wprpw_rule_enddate = date( 'Y-m-d', strtotime( 'last day of last month' ) );
								break;
				break;

			}
			if ( isset( $wprpw_rule_startdate ) and isset( $wprpw_rule_enddate ) ) {
				$wprpw_rule_startdate = date( 'F jS, Y',strtotime( $wprpw_rule_startdate ) );
				$wprpw_rule_enddate = date( 'F jS, Y',strtotime( $wprpw_rule_enddate ) );
				$this->args['date_query'] = array( 'after' => $wprpw_rule_startdate,'before' => $wprpw_rule_enddate );
			}
		}
		/**
		 * Set Taxonomies Arguments.
		 * @param string $taxonomy Taxonomy Name.
		 * @param array  $terms    Terms.
		 */
		function set_taxonomies($taxonomy, $terms) {
			$this->tax_query[] = array(
								'taxonomy' => $taxonomy,
								'field' => 'id',
								'terms' => $terms,
								'operator' => 'IN',
								);

		}
		/**
		 * Hide Taxonomies Arguments.
		 * @param string $taxonomy Taxonomy Name.
		 * @param array  $terms    Terms.
		 */
		function hide_taxonomies($taxonomy, $terms) {
			$this->tax_query[] = array(
								'taxonomy' => $taxonomy,
								'field' => 'id',
								'terms' => $terms,
								'operator' => 'NOT IN',
								);

		}
		/**
		 * Set Custom Fields Arguments.
		 * @param array $rule_customfields Custom Fields.
		 */
		function set_custom_fields($rule_customfields) {
			if ( ! empty( $rule_customfields['key'] ) ) {
				foreach ( $rule_customfields['key'] as $key => $name ) {

					if ( '<' == $rule_customfields['condition'][ $key ] || '<=' == $rule_customfields['condition'][ $key ] || '>' == $rule_customfields['condition'][ $key ] || '>=' == $rule_customfields['condition'][ $key ] || 'BETWEEN' == $rule_customfields['condition'][ $key ] || 'NOT BETWEEN' == $rule_customfields['condition'][ $key ] ) {
						$wprpw_meta_data[ $key ] = array(
						'key' => $name,
						'value' => stripcslashes( $rule_customfields['value'][ $key ] ),
						'compare' => $rule_customfields['condition'][ $key ],
						'type' => 'NUMERIC',
						);
					} else {
						$wprpw_meta_data[ $key ] = array(
						'key' => $name,
						'value' => stripcslashes( $rule_customfields['value'][ $key ] ),
						'compare' => $rule_customfields['condition'][ $key ],
						);
					}
				}
				$this->args['meta_query'] = $wprpw_meta_data;
			}
		}
		/**
		 * Set Post's Format Argument.
		 * @param array $formats Post Formats.
		 */
		function set_post_formats($formats) {
			$this->tax_query[] = array(
									'taxonomy' => 'post_format',
									'field' => 'slug',
									'terms' => $formats,
									'operator' => 'IN',
									);
		}
		/**
		 * Set Post Types Arguments.
		 * @param array $post_types Post Types.
		 */
		function set_post_types($post_types) {
			$this->args['post_type'] = $post_types;
		}
		/**
		 * Set Author Arguments.
		 * @param array $authors Author ID's.
		 */
		function set_author($authors) {
			$this->args['author__in'] = $authors;
		}
		/**
		 * Hide Author Arguments.
		 * @param array $authors Author ID's.
		 */
		function hide_author($authors) {
			$this->args['author__not_in'] = $authors;
		}
		/**
		 * Build Arguments array for WP_Query.
		 */
		function build_query() {
			if ( $this->has_thumbnail == true ) {
				$this->args['meta_query'][ count( $this->args['meta_query'] ) ] = array( 'key' => '_thumbnail_id' );
			}
			if ( $this->ignore_sticky_posts == true ) {
				$this->args['ignore_sticky_posts']	= true;
				$this->args['post__not_in']  = get_option( 'sticky_posts' );
			}

			if ( is_array( $this->tax_query ) and ! empty( $this->tax_query ) ) {

				$this->args['tax_query'] 	= $this->tax_query;
				if ( count( $this->tax_query ) > 1 ) {
					$this->args['tax_query']['relation'] = 'AND';
				}
			}

			if ( is_array( $this->post__in ) and ! empty( $this->post__in ) ) {
				$this->args['post__in'] 	= $this->post__in;
			}

			if ( is_array( $this->post__not_in ) and ! empty( $this->post__not_in ) ) {
				$this->args['post__not_in'] 	= $this->post__not_in;
			}
			if ( $this->offset > 0 ) {
				$this->args['offset'] 			= $this->offset;
			}

			$this->args['posts_per_page']   = $this->posts_per_page;
			$this->args['paged']       		= $this->page;
			if ( $this->orderby != '' ) {
				$this->args['orderby'] 			= $this->orderby;
			}
		    if ( $this->order != '' ) {
		    	$this->args['order'] 			= $this->order;
		    }
			$this->args['post_status']      = $this->post_status;

		}
		/**
		 * Get Arguments
		 * @return array Query Arguments.
		 */
		function get_args() {
			$this->build_query();
			return $this->args;
		}
		/**
		 * Get Posts using WP_QUERY.
		 * @return array Posts.
		 */
		function get_posts() {
			$data['posts'] = '';
			$data['pagination'] = '';

			$the_query = new WP_Query( $this->get_args() );

			$total_pages = $the_query->max_num_pages;

			if ( $total_pages > 1 ) {

				$current_page = max( 1, get_query_var( 'paged' ) );

				$pagination = '<div class="posts-pro-pagination">';

				$big = 999999999; // Need an unlikely integer.
				$translated = __( 'Page', WPP_TEXT_DOMAIN ); // Supply translatable string.

				$pagination .= paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) ),
					'total' => $the_query->max_num_pages,
					) );

				$pagination .= '</div>';

			}
			$data['posts'] = $the_query->posts;
			$data['pagination'] = $pagination;
			wp_reset_postdata();
			return $data;
		}

	}
}
