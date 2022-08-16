<?php
/*
Plugin Name: Custom Taxonomy Order
Plugin URI: https://wordpress.org/plugins/custom-taxonomy-order-ne/
Description: Allows for the ordering of categories and custom taxonomy terms through a simple drag-and-drop interface.
Version: 3.4.4
Author: Marcel Pol
Author URI: https://timelord.nl/
License: GPLv2 or later
Text Domain: custom-taxonomy-order-ne
Domain Path: /lang/


Copyright 2011 - 2011  Drew Gourley
Copyright 2013 - 2022  Marcel Pol   (marcel@timelord.nl)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.



/*
 * TODO:
 * - Add pagination, just like next_post_link().
 *   https://wordpress.org/support/topic/how-to-create-a-navigation-in-archivephp-with-the-given-order/
 * - Support other orderby's.
 */


// Plugin Version
define('CUSTOMTAXORDER_VER', '3.4.4');


/*
 * Get settings for ordering each taxonomy.
 * Settings:
 * 0: orderby Term ID
 * 1: orderby Custom Order
 * 2: orderby Term Name (alphabetical)
 * 3: orderby Term Slug (alphabetical)
 * 4: orderby Post Count (based on term_taxonomy table)
 *
 * @return array $customtaxorder_settings an array with key: $taxonomy->name and value:
 */
function customtaxorder_get_settings() {
	$customtaxorder_defaults = array('category' => 0);

	$taxonomies = customtaxorder_get_taxonomies() ;
	foreach ( $taxonomies as $taxonomy ) {
		$customtaxorder_defaults[$taxonomy->name] = 0;
	}

	$customtaxorder_defaults = apply_filters( 'customtaxorder_defaults', $customtaxorder_defaults );
	$customtaxorder_settings = get_option( 'customtaxorder_settings' );
	$customtaxorder_settings = wp_parse_args( $customtaxorder_settings, $customtaxorder_defaults );

	$customtaxorder_settings = apply_filters( 'customtaxorder_settings', $customtaxorder_settings );

	return $customtaxorder_settings;
}


/*
 * customtax_cmp
 * Sorting of an array with objects, ordered by term_order
 * Sorting the query with get_terms() doesn't allow sorting with term_order
 */
function customtax_cmp( $a, $b ) {
	if ( (float) $a->term_order == (float) $b->term_order ) {
		return 0;
	} else if ( (float) $a->term_order < (float) $b->term_order ) {
		return -1;
	} else {
		return 1;
	}
}


/*
 * Function to sort the standard WordPress Queries for terms.
 *
 * @return string t.orderby
 *
 */
function customtaxorder_apply_order_filter( $orderby, $args ) {
	$options = customtaxorder_get_settings();

	$taxonomy = 'category';
	if ( isset( $args['taxonomy'] ) ) {
		if ( is_string( $args['taxonomy'] ) && ! empty( $args['taxonomy'] ) ) {
			$taxonomy = $args['taxonomy'];
		} else if ( is_array( $args['taxonomy'] ) && ! empty( $args['taxonomy'] ) ) {
			// Bug: if $args[$taxonomy] is an array with tax->names it will return the orderby for the first tax.
			$taxonomy = array_shift( $args['taxonomy'] );
		}
	}

	if ( ! isset( $options[$taxonomy] ) ) {
		$options[$taxonomy] = 0; // Default if it was not set in options yet.
	}

	if ( $args['orderby'] == 'term_order' ) {
		return 't.term_order';
	} else if ( $args['orderby'] == 'name' ) {
		return 't.name';
	} else if ( $args['orderby'] == 'count' ) {
		return 'tt.count'; // term_taxonomy table
	} else if ( $options[$taxonomy] == 1 && ! customtaxorder_is_get_orderby_set() ) {
		return 't.term_order';
	} else if ( $options[$taxonomy] == 2 && ! customtaxorder_is_get_orderby_set() ) {
		return 't.name';
	} else if ( $options[$taxonomy] == 3 && ! customtaxorder_is_get_orderby_set() ) {
		return 't.slug';
	} else if ( $options[$taxonomy] == 4 && ! customtaxorder_is_get_orderby_set() ) {
		return 'tt.count';
	} else {
		return $orderby;
	}
}
add_filter('get_terms_orderby', 'customtaxorder_apply_order_filter', 10, 2);


/*
 * Set defaults in Class WP_Term_Query->parse_query();
 * Default is name now. Set it to term_order or slug if desired.
 */
function customtaxorder_get_terms_defaults( $query_var_defaults, $taxonomies ) {
	$options = customtaxorder_get_settings();

	$taxonomy = 'category';
	if ( isset( $query_var_defaults['taxonomy'] ) ) {
		if ( is_string( $query_var_defaults['taxonomy'] ) && ! empty( $query_var_defaults['taxonomy'] ) ) {
			$taxonomy = $query_var_defaults['taxonomy'];
		}
	}

	if ( ! isset( $options[$taxonomy] ) ) {
		$options[$taxonomy] = 0; // Default if it was not set in options yet.
	}

	if ( $options[$taxonomy] == 1 ) {
		$query_var_defaults['orderby'] = 'term_order';
	} else if ( $options[$taxonomy] == 2 ) {
		$query_var_defaults['orderby'] = 'name';
	} else if ( $options[$taxonomy] == 3 ) {
		$query_var_defaults['orderby'] = 'slug';
	}else if ( $options[$taxonomy] == 4 ) {
		$query_var_defaults['orderby'] = 'count';
	}

	return $query_var_defaults;
}
add_filter('get_terms_defaults', 'customtaxorder_get_terms_defaults', 10, 2);


/*
 * customtaxorder_wp_get_object_terms_order_filter
 *
 * Filters:
 * wp_get_object_terms is used to sort in wp_get_object_terms and wp_get_post_terms functions.
 * get_terms is used in wp_list_categories and get_terms functions.
 * get_the_terms is used in the the_tags function.
 * tag_cloud_sort is used in the wp_tag_cloud and wp_generate_tag_cloud functions (but then the get_terms filter here does nothing).
 * term_query_results is used in WP_Term_Query->get_terms() (will probably come in WP Next).
 *
 * Default sorting is by name (according to the codex).
 *
 */
function customtaxorder_wp_get_object_terms_order_filter( $terms ) {

	$options = customtaxorder_get_settings();

	/*if ( is_admin() ) {
		$doing_ajax = wp_doing_ajax();
		if ( $doing_ajax && $_POST['action'] === 'get-tagcloud' ) {
			var_dump($terms);
			return $terms;
		}
	}*/

	$terms_old_order = $terms;

	if ( empty($terms) || ! is_array($terms) ) {
		return $terms; // only work with an array of terms
	}
	foreach ($terms as $term) {
		if ( is_object($term) && isset( $term->taxonomy ) ) {
			$taxonomy = $term->taxonomy;
		} else {
			return $terms; // not an array with objects
		}
		break; // just the first one :)
	}

	$terms_count = count( $terms );

	if ( ! isset ( $options[$taxonomy] ) ) {
		$options[$taxonomy] = 0; // default if not set in options yet
	}
	if ( $options[$taxonomy] == 1 && ! customtaxorder_is_get_orderby_set() ) {

		// no filtering so the test in wp_generate_tag_cloud() works out right for us
		// filtering will happen in the tag_cloud_sort filter sometime later
		// post_tag = default tags
		// product_tag = woocommerce product tags
		if ( current_filter() == 'get_terms' && ! is_admin() ) {
			$customtaxorder_exclude_taxonomies = array( 'post_tag', 'product_tag' );
			if ( in_array($taxonomy, apply_filters( 'customtaxorder_exclude_taxonomies', $customtaxorder_exclude_taxonomies )) ) {
				return $terms;
			}
		}

		// Sort children after the ancestor, by using a float with "ancestor.childchildchild", traversing all parents.
		foreach ($terms as $term) {
			if ( ! $term->parent == 0 ) {
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
				if ( is_array($ancestors) && ! empty($ancestors) ) {
					$toplevel_ancestor_id = array_pop( $ancestors );
					$toplevel_ancestor_term = get_term($toplevel_ancestor_id, $term->taxonomy);
					if ( is_object($toplevel_ancestor_term) && isset($toplevel_ancestor_term->term_order) ) {
						$front_of_float = (string) $toplevel_ancestor_term->term_order;

						$rear_of_float = '';
						$padding = 100; // Make it sort correctly. Not many websites have more than 900 subterms.
						foreach ( $ancestors as $ancestor_id ) {
							$ancestor_term = get_term($ancestor_id, $term->taxonomy);
							if ( is_object($ancestor_term) && isset($ancestor_term->term_order) ) {

								// calculate padding. Too much padding will have deep float calculations go wrong. See https://floating-point-gui.de/basic/
								// Do not do this with too many terms, some WooCommerce pages are way too heavy with terms.
								if ( $terms_count < 700 ) {
									$args = array(
										'orderby'    => 'term_order',
										'order'      => 'ASC',
										'hide_empty' => false,
										'parent'     => $ancestor_id,
									);
									$sister_terms = get_term_children( $ancestor_id, $taxonomy );
									if ( is_array( $sister_terms ) ) { // should always be an array, since this term does exist.
										$count = count( $sister_terms );
										if ( $count < 10 ) {
											$padding = 0;
										} else if ( $count < 100 ) {
											$padding = 10;
										} else if ( $count < 1000 ) {
											$padding = 100;
										} else if ( $count < 10000 ) {
											$padding = 1000;
										}
									}
								}
								$rear_of_float .= (string) ($ancestor_term->term_order + $padding);
							}
						}

						// calculate padding. Too much padding will have deep float calculations go wrong. See https://floating-point-gui.de/basic/
						// Do not do this with too many terms, some WooCommerce pages are way too heavy with terms.
						if ( $terms_count < 700 ) {
							$args = array(
								'orderby'    => 'term_order',
								'order'      => 'ASC',
								'hide_empty' => false,
								'parent'     => $term->parent,
							);
							$sister_terms = get_term_children( $term->parent, $taxonomy );
							if ( is_array( $sister_terms ) ) { // should always be an array, since this term does exist.
								$count = count( $sister_terms );
								if ( $count < 10 ) {
									$padding = 0;
								} else if ( $count < 100 ) {
									$padding = 10;
								} else if ( $count < 1000 ) {
									$padding = 100;
								} else if ( $count < 10000 ) {
									$padding = 1000;
								}
							}
						}
						$rear_of_float .= (string) ($term->term_order + $padding);
						$term->term_order = (float) ( $front_of_float . '.' . $rear_of_float );
					}
				}
			}
		}
		usort($terms, 'customtax_cmp');
		$terms_new_order = $terms;
		/*
		* Fires after term array has been ordered with usort.
		* Please be aware that this can be triggered multiple times during a request.
		*
		* @since 2.9.4
		*
		* @param array $terms_new_order ordered array with instances of WP_Term_Query.
		* @param array $terms_old_order original array with instances of WP_Term_Query.
		*/
		do_action( 'customtaxorder_terms_ordered', $terms_new_order, $terms_old_order );

		return $terms;
	}
	return $terms;
}
add_filter( 'wp_get_object_terms', 'customtaxorder_wp_get_object_terms_order_filter' );
add_filter( 'get_terms', 'customtaxorder_wp_get_object_terms_order_filter');
add_filter( 'get_the_terms', 'customtaxorder_wp_get_object_terms_order_filter' );
add_filter( 'tag_cloud_sort', 'customtaxorder_wp_get_object_terms_order_filter' );
add_filter( 'term_query_results', 'customtaxorder_wp_get_object_terms_order_filter' );


/*
 * Support Advanced Custom Fields with its Taxonomy Fields.
 */
function customtaxorder_wp_get_object_terms_order_filter_acf( $terms ) {

	if ( empty($terms) || ! is_array($terms) ) {
		return $terms; // only work with an array of terms
	}
	foreach ($terms as $term) {
		if ( ! is_object($term) || ! is_a($term, 'WP_Term') ) {
			return $terms; // not an array with terms
		}
	}

	$terms = customtaxorder_wp_get_object_terms_order_filter( $terms );

	return $terms;
}
add_filter('acf/format_value_for_api', 'customtaxorder_wp_get_object_terms_order_filter_acf', 99 );


/*
 * customtaxorder_order_categories
 * Filter to sort the categories according to term_order
 *
 */
function customtaxorder_order_categories( $categories ) {
	$options = customtaxorder_get_settings();

	$terms_old_order = $categories;

	if ( ! isset( $options['category'] ) ) {
		$options['category'] = 0; // default if not set in options yet
	}
	if ( $options['category'] == 1 && ! customtaxorder_is_get_orderby_set() ) {
		usort($categories, 'customtax_cmp');

		$terms_new_order = $categories;
		/*
		* Fires after term array has been ordered with usort.
		* Please be aware that this can be triggered multiple times during a request.
		*
		* @since 2.9.4
		*
		* @param array $terms_new_order ordered array with instances of WP_Term_Query.
		* @param array $terms_old_order original array with instances of WP_Term_Query.
		*/
		do_action( 'customtaxorder_terms_ordered', $terms_new_order, $terms_old_order );

		return $categories;
	}
	return $categories;
}
add_filter( 'get_the_categories', 'customtaxorder_order_categories' );


/*
 * Get list of taxonomies.
 *
 * @return array list of taxonomies.
 *
 * @since 3.2.0
 */
function customtaxorder_get_taxonomies() {

	$args = array(); // Just get them all and don't mess about with setting some taxonomies to public.
	$output = 'objects';
	$taxonomies = get_taxonomies( $args, $output );

	$taxonomies = apply_filters( 'customtaxorder_get_taxonomies', $taxonomies );

	return $taxonomies;

}


/*
 * Check if the GET parameter orderby is set.
 * In case it is set it is assumed it is used to order terms, instead of posts, users, or anything else.
 *
 * @return bool true if the parameter is set. false if not set or if overridden by a filter.
 *
 * @since 3.3.2
 */
function customtaxorder_is_get_orderby_set() {

	$get_orderby_set = false;

	if ( isset( $_GET['orderby'] ) ) {
		$get_orderby_set = true;
	}

	/*
	 * Add filter to possible ignore the orderby parameter in the case that is set in the GET request.
	 * Might be usefull if your _GET parameter for orderby is used to sort posts, users, or just anything that is not terms.
	 *
	 * @param  bool true if the orderby parameter in the GET request is set.
	 * @return bool always return false in case you want to ignore this GET parameter.
	 *
	 * @since 3.3.2
	 *
	 * Example code for using the filter:
	 *
	 * function my_customtaxorder_is_get_orderby_set( $get_orderby_set ) {
	 *     return false; // ignore orderby GET parameter
	 *     // return $get_orderby_set; // this would be default behaviour
	 * }
	 * add_filter( 'customtaxorder_is_get_orderby_set', 'my_customtaxorder_is_get_orderby_set' );
	 *
	 */
	$get_orderby_set = (bool) apply_filters( 'customtaxorder_is_get_orderby_set', $get_orderby_set );

	return $get_orderby_set;

}


/*
 * Function called at activation time.
 */
function _customtaxorder_activate() {
	global $wpdb;
	$init_query = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_order'");
	if ( $init_query == 0 ) {
		$wpdb->query("ALTER TABLE $wpdb->terms ADD term_order INT( 4 ) NULL DEFAULT '0'");
	}
}


function customtaxorder_activate( $networkwide ) {
	global $wpdb;
	if ( is_multisite() ) {
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ( $blogids as $blog_id ) {
			switch_to_blog( $blog_id );
			_customtaxorder_activate();
			restore_current_blog();
		}
	} else {
		_customtaxorder_activate();
	}
}
register_activation_hook( __FILE__, 'customtaxorder_activate' );


/*
 * Install database column for new blog on MultiSite.
 * Deprecated action since WP 5.1.0.
 *
 */
function customtaxorder_activate_new_site( $blog_id ) {
	switch_to_blog( $blog_id );
	_customtaxorder_activate();
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'customtaxorder_activate_new_site' );


/*
 * Install database column for new blog on MultiSite.
 * Used since WP 5.1.0.
 * Do not use 'wp_insert_site' action, since the options table doesn't exist yet at that time.
 *
 * @since 2.10.1
 */
function customtaxorder_wp_initialize_site( $blog ) {
	switch_to_blog( $blog->id );
	_customtaxorder_activate();
	restore_current_blog();
}
add_action( 'wp_initialize_site', 'customtaxorder_wp_initialize_site' );


if ( is_admin() ) {
	// Admin functions
	require_once 'admin-customtaxorder.php';
	// Settingspage
	require_once 'page-customtaxorder.php';
}
// functions for sorting taxonomies
require_once 'taxonomies.php';
