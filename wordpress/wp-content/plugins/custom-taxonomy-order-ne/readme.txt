=== Custom Taxonomy Order ===
Contributors: mpol
Tags: term order, category order, taxonomy order, order
Requires at least: 3.7
Tested up to: 6.0
Stable tag: 3.4.4
License: GPLv2 or later


Allows for the ordering of categories and custom taxonomy terms through a simple drag-and-drop interface

== Description ==

Custom Taxonomy Order is a plugin for WordPress which allows for the ordering of taxonomy terms.

It supports the following features:

* Order (custom) terms through a simple drag-and-drop interface.
* No custom coding needed. It uses standard WordPress filters.
* It uses the available WordPress scripts and styles.
* The plugin is lightweight, without any unnecessary scripts to load into the admin.
* It falls in line gracefully with the look and feel of the WordPress interface.
* It uses it's own menu in the backend.
* Translated or translatable.
* Custom functions to order the taxonomies themselves.
* There is no Pro version, everything works in the Free version.

= Compatibility =

This plugin is compatible with [ClassicPress](https://www.classicpress.net).

= Contributions =

This plugin is also available in [GitLab](https://gitlab.com/toomanybicycles/custom-taxonomy-order-ne)


== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Order posts from the 'Term Order' menu in the admin
4. Optionally set whether or not to have queries of the selected taxonomy be sorted by this order automatically.
5. Optionally set `'orderby' => 'term_order', 'order' => 'ASC'` to manually sort queries by this order.
6. Enjoy!

= Upgrade Notice =

If you update from the original Custom Taxonomy Order please deactivate that first, then activate this plugin.

= Removal of database changes =

First you can disable the plugin. Then in wp_options you can delete the field:
* customtaxorder_settings
* customtaxorder_taxonomies

In wp_terms you can remove the column
* term_order


== Frequently Asked Questions ==

= I sorted the terms in the WordPress backend, but I don't see it changed in the frontend =

Did you set the option for that taxonomy to use that custom order? Make sure to check it so the filters run
with your taxonomy.

= How do I sort the terms when using a custom query? =

When you use default functions like get_terms or get_categories, this should not be needed.

If you do need to, you can apply the sorting for the taxonomy by using:
	'orderby' => 'term_order'.

= I use get_term_children but the terms do not get sorted =

This function only fetches the ID's of the terms, so it is impossible to sort them by term_order. If you do need the sort_order, use a function like get_terms with the 'child_of' parameter. That will fetch an array of WP_Term objects that can be sorted.

= I have a custom taxonomy that uses the Tag Cloud functionality, but it doesn't sort like it should. =

If it is a much used plugin, can you tell me what is the name for the taxonomy?
In the customtaxorder_wp_get_object_terms_order_filter it needs to be added, and the get_terms filter should not run
on that taxonomy. The tag_cloud_sort filter should do that.

If it is a custom taxonomy, you can also use a filter:

	<?php
	add_filter( 'customtaxorder_exclude_taxonomies', 'add_taxonomy_to_customtaxorder_exclude_taxonomies' );
	function add_taxonomy_to_customtaxorder_exclude_taxonomies( $taxonomies ) {
		$taxonomies[] = 'directory'; // name of your tag taxonomy.
		return $taxonomies;
	}
	?>

= I'm using the_tags function, but it doesn't sort as it should. =

There is a bug with the the_tags function, where it will sort according to the setting for categories.
This happens in the 'customtaxorder_apply_order_filter' function where the $args has two taxonomies but only one orderby can be returned.

= I use WooCommerce Attributes. =

This plugin only supports sorting the attributes/terms. These are the items like S, M and L.
For sorting the taxonomies like 'size', you need to sort them on Woo's attributes page.

= What capabilities are needed? =

For sorting the terms you need the manage_categories capability.

= Can I sort the taxonomies themselves? =

There is an admin page to sort them, and save them in the database.

You can use a function to sort taxonomies themselves like this:

	<?php
	$taxonomy_objects = get_object_taxonomies( 'post', 'objects' );
	$taxonomy_objects = customtaxorder_sort_taxonomies($taxonomy_objects);
	foreach (  $taxonomy_objects as $tax ) {
		echo $tax->name . "<br />";
	}
	?>

The function requires a parameter with an array of taxonomy objects.

= Can I hook into saving the term order on the dashboard page? =

'customtaxorder_update_order' is being run when saving the order of terms in the admin page.
You could add the following example to your functions.php and work from there.

	<?php
	function my_customtaxorder_update_order( $new_order ) {
		print_r( $new_order );
	}
	add_action('customtaxorder_update_order', 'my_customtaxorder_update_order');
	?>

= Can I hook into changing the term order on the frontend? =

'customtaxorder_terms_ordered' action is being run after term array has been ordered with usort.
Please be aware that this can be triggered multiple times during a request.
You could add the following example to your functions.php and work from there.

	<?php
	function custom_action( $terms_new_order, $terms_old_order ) {
		print_r( $terms_new_order );
	}
	add_action('customtaxorder_terms_ordered', 'custom_action', 10, 2);
	?>

= Can I hook into the settings for each taxonomy to change the sorting behaviour? =

'customtaxorder_settings' filter is being after fetching the settings for all taxonomies.
You could add the following example to your functions.php and work from there.

	<?php
	/*
	 * Example code to change the product_cat settings in WooCommerce on the main shop page.
	 *
	 * Settings:
	 * 0: orderby Term ID
	 * 1: orderby Custom Order
	 * 2: orderby Term Name (alphabetical)
	 * 3: orderby Term Slug (alphabetical)
	 * 4: orderby Post Count (based on term_taxonomy table)
	 *
	 * Taken from:
	 * https://wordpress.org/support/topic/product-categories-sort-how-to-sort-just-the-sub-terms-sub-categories/
	 *
	 */
	function my_customtaxorder_settings( $settings ) {
		//var_dump( $settings['product_cat'] );
		if ( function_exists( 'is_shop' ) ) {
			if ( is_shop() ) {
				$settings['product_cat'] = 1;
			}
		}
		//var_dump( $settings['product_cat'] );
		return $settings;
	}
	add_filter( 'customtaxorder_settings', 'my_customtaxorder_settings' );
	?>

= I use the GET parameter 'orderby' to order posts, but then it is ignoring term order =

In case the GET parameter 'orderby' is set it will be used to order terms, instead of posts, users, or anything else.
Therefore when that GET parameter is set, there is no custom order applied in this plugin.

You can add a filter to possible ignore the orderby parameter in the GET request.
That might be useful if your GET parameter for orderby is used to sort posts, users, or just anything that is not terms.
Example code for using the filter:

	<?php
	function my_customtaxorder_is_get_orderby_set( $get_orderby_set ) {
		return false; // ignore orderby GET parameter
		// return $get_orderby_set; // this would be default behaviour
	}
	add_filter( 'customtaxorder_is_get_orderby_set', 'my_customtaxorder_is_get_orderby_set' );
	?>

= How can I add my own translation? =

Translations can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/custom-taxonomy-order-ne).
You can start translating strings there for your locale. They need to be validated though, so if there's no validator yet,
and you want to apply for being validator, please post it on the support forum. I will make a request on make/polyglots to
have you added as validator for this plugin/locale.


== Screenshots ==

1. Screenshot of the menu page for Custom Taxonomy Order.
The WordPress menu completely left lists the different taxonomies.
The left metabox lists the toplevel terms. Right (or below) are the sub-terms.


== Changelog ==

= 3.4.4 =
* 2022-07-27
* Use correct value for updating `term_relationships` table.
  this should fix some reported issues where the problem was unclear.
* On updating order, list the number of terms that were updated.

= 3.4.3 =
* 2022-04-30
* Ouch, don't do those calculations on frontend either when there are too many terms, it is too expensive.

= 3.4.2 =
* 2022-04-27
* Do not use the previous change on admin dashboard, some WooCommerce pages are way too heavy with terms.

= 3.4.1 =
* 2022-04-27
* Improve ordering nested sub-terms.

= 3.4.0 =
* 2022-03-14
* Support order by post count.
* Add filter 'customtaxorder_get_taxonomies'.

= 3.3.3 =
* 2022-01-24
* Traverse all parents for sorting child terms, not just the toplevel ancestor.
* Some fixes on admin page for sorting subterms.
* Add filter 'customtaxorder_settings'.

= 3.3.2 =
* 2021-12-24
* Add function 'customtaxorder_is_get_orderby_set'.
* Add filter 'customtaxorder_is_get_orderby_set' to optionally change the output of that function.
* Fix warning on PHP 8.1.
* Show taxonomy name as label on dashboard, if label is not set.
* Remove old translation files from plugin, GlotPress should be used.
* Do not show all submenu items on dashboard if they are over 100 (fix for Woo attributes).

= 3.3.1 =
* 2021-11-26
* Bring back removed code for orderby clause in 'get_terms_orderby' filter.
* No need to check if function 'current_user_can' and 'is_multisite' exist.
* Use functions like 'esc_attr', 'esc_html' and 'esc_url' when appropriate.

= 3.3.0 =
* 2021-02-23
* Save settings per taxonomy with capability 'manage_categories', not 'manage_options'.
* Rewrite some code for settings pages.
* Fix deprecated jQuery calls with WP 5.6 and jQuery 3.5.
* Rename script.js to customtaxorder.js.

= 3.2.2 =
* 2020-12-04
* Fix category/tag edit screen, extra field.
* Change 'strval()' to '(string)'.
* Change 'floatval()' to '(float)'.


= 3.2.1 =
* 2020-07-07
* More compatibility with WPML plugin.
* Remove code to set taxonomy 'link_category' to public.

= 3.2.0 =
* 2020-07-06
* Improve compatibility with WPML plugin.
* Add function 'customtaxorder_get_taxonomies()' to remove duplicate code.
* Be able to sort all taxonomies, public and non-public.
* Use more esc_html functions.
* Update About page.
* Only load translations at the dashboard.

= 3.1.0 =
* 2020-03-23
* Add term_order field to edit screen of terms (thanks @li-an).
* Add order button for slug (thanks @eric3d).
* Only load admin functions at dashboard.
* Remove included es_ES po file, it is maintained in GlotPress.

= 3.0.1 =
* 2020-01-07
* Add taxonomy parameter to get_term call for compatibility with WP 4.3.

= 3.0.0 =
* 2019-12-21
* Always overwrite orderby according to our settings.
* Add option to sort by slug.
* Support new wp_initialize_site action for multisite.
* Simplify checking for Link Manager plugin.
* Small fixes found by the phan tool.

= 2.10.0 =
* 2018-10-17
* Sort attributes for WooCommerce automatically.
* Show name and label of taxonomy in the lists.

= 2.9.5 =
* 2018-09-07
* Add support for Dark Mode beta plugin.

= 2.9.4 =
* 2018-07-02
* Add action 'customtaxorder_terms_ordered'.

= 2.9.3 =
* 2018-02-16
* Sort terms with numeric values correctly (thanks empiresafe).

= 2.9.2 =
* 2017-11-30
* Add action 'custom_taxonomy_order_ne_settings_buttons' to settings page.

= 2.9.1 =
* 2017-08-04
* Set Woo attribute terms to public.

= 2.9.0 =
* 2017-07-10
* Drop support for attributes in WooCommerce (deprecated).
* Use the correct number of parameters with add_filter calls.
* Set default order in WP_Term_Query->get_terms() according to our settings (probably WP 4.9).

= 2.8.3 =
* 2017-03-29
* Add Nonce check to admin pages.
* Register setting for taxonomies too, with callback function.
* Run sanitize_text_field on the keyname in the option for customtaxorder_settings.

= 2.8.2 =
* 2016-10-19
* Only skip one sort for tags on frontend, not admin.

= 2.8.1 =
* 2016-10-06
* Sort children with a float as ancestor.child when set to term_order.

= 2.8.0 =
* 2016-10-04
* Remove global var, add function customtaxorder_get_settings().
* Fix PHP notices in customtaxorder_apply_order_filter.

= 2.7.8 =
* 2016-07-26
* Flush object cache when order is changed in taxonomy ordering plugin (props James Bonham).

= 2.7.7 =
* 2016-07-24
* Fix PHP warnings.
* Remove ru_RU translation, it is at 100% in GlotPress.
* Update Donate text.

= 2.7.6 =
* 2016-03-01
* Add filters for custom capabilities.

= 2.7.5 =
* 2016-01-11
* Support Advanced Custom Fields with its Taxonomy Fields.

= 2.7.4 =
* 2016-01-06
* Really fix Woo get_attribute() (thanks eddy_boy).

= 2.7.3 =
* 2015-11-26
* Fix for Woo get_attribute() (thanks mantish).

= 2.7.2 =
* 2015-11-26
* Properly enqueue admin scripts.
* Rename and prefix js functions properly.

= 2.7.1 =
* 2015-11-07
* Explode() expects parameter to be a string, not an array.
* Better dashicon.
* Drop pot, nl_NL, they are maintained at GlotPress.

= 2.7.0 =
* 2015-11-07
* Offer page and functions to support the taxonomies themselves.
* Support WooCommerce attributes.
* Only support WordPress 3.7+, since they really are supported.
* More specific CSS.
* Add icon on admin pages.
* Update pot, nl_NL.

= 2.6.6 =
* 2015-09-05
* Add filter for (not) sorting a tagcloud (thanks sunriseweb).
* For sub-term, start counting at term_order of parent-term, so sorting looks reasonable.
* "Order Alphabetically" button is no button-primary.
* Change textdomain to slug.
* Make admin_notices dismissible.
* Add version to admin CSS.

= 2.6.5 =
* 2015-08-05
* Use correct headings on admin pages.

= 2.6.4 =
* 2015-05-31
* Add About page.
* Update pot and nl_NL.

= 2.6.3 =
* 2015-03-25
* Support Link Manager plugin.

= 2.6.2 =
* 2015-03-21
* Better suppport for WPMU, also set up new blogs (thanks Andrew Patton).

= 2.6.1 =
* 2015-03-13
* Wrap radio buttons inside label, so the label works (thanks Andrew Patton).

= 2.6.0 =
* 2015-02-28
* Separate settingspage to own php-file.
* Add de_DE (thanks Patrick Skiebe).

= 2.5.9 =
* 2015-01-21
* Add test for capability inside admin page as well.

= 2.5.8 =
* 2014-12-11
* Fix conflict with wp-catalogue plugin

= 2.5.7 =
* 2014-09-12
* Fix notices with defensive programming

= 2.5.6 =
* 2014-08-22
* More compatibility with WPML

= 2.5.5 =
* 2014-08-20
* Some Compatibility with WPML Plugin

= 2.5.4 =
* 2014-08-15
* Add action for saving the terms

= 2.5.3 =
* 2014-08-06
* New default settings page
* Filter added for get_the_terms
* Don't filter tags at get_terms filtering
* Updated nl_NL

= 2.5.2 =
* 2014-06-30
* Also be able to sort the builtin taxonomies
* Fix bug with sorting tags

= 2.5.1 =
* 2014--5-13
* Added fr_FR (Jean-Christophe Brebion)

= 2.5.0 =
* 2014-05-02
* Added ru_RU (Alex Rumyantsev)
* Small gettext fixes
* update nl_NL

= 2.4.9 =
* 2014-04-15
* Multisite activation doesn't work if it isn't done network wide

= 2.4.8 =
* 2014-04-11
* Don't usort on an array which doesn't contain objects

= 2.4.7 =
* 2014-03-29
* Also filter at the get_terms hook for get_terms() and wp_list_categories()

= 2.4.6 =
* 2014-03-24
* Update pl_PL

= 2.4.5 =
* 2014-03-23
* Improve html/css

= 2.4.4 =
* 2014-03-23
* Remove obsolete images

= 2.4.3 =
* 2014-03-22
* Add settings link

= 2.4.2 =
* 2014-03-22
* New dashicon

= 2.4.1 =
* 2014-03-22
* Add alphabetical sorting to options as well
* Update Polish and Dutch

= 2.4.0 =
* 2014-03-18
* Add Polish translation (Paweł Data)
* Sort Alphabetically (landwire)

= 2.3.9 =
* 2014-02-25
* Fix activation code to really generate term_order column

= 2.3.8 =
* 2014-02-18
* Ouch, remove testing code

= 2.3.7 =
* 2014-02-18
* Fix activation on network install (Matteo Boria)

= 2.3.6 =
* 2014-01-26
* Also add filter for wp_get_object_terms and wp_get_post_terms

= 2.3.5 =
* 2014-01-26
* Only filter categories when auto-sort is enabled

= 2.3.4 =
* 2014-01-25
* Filter added for get_the_categories

= 2.3.3 =
* 2014-01-25
* Fix errors "undefined index" for undefined options

= 2.3.2 =
* 2014-01-03
* Use print for translated substring (Matteo Boria)
* Add Italian Translation (Matteo Boria)

= 2.3.1 =
* 2013-12-30
* Fix PHP error-notice when activating

= 2.3 =
* 2013-12-10
* Add es_ES translataion, thanks Andrew and Jelena

= 2.2 =
* 2013-10-20
* do init stuff in the init function
* also update term_order in term_relationships table
* security update: validate input with $wpdb->prepare()

= 2.1 =
* 2013-10-10
* renamed/forked as Custom Taxonomy Order New Edition
* fixed a bug with ordering in the backend
* add localisation
* add nl_NL lang

= 2.0 =
* Complete code overhaul and general rewrite to bring things up to speed
* Updated for WordPress 3.2 Admin Design
* Added auto-sort query option
* Several text fixes for overall consistency and clarity.
* Various small bugfixes and optimizations

= 1.0 =
* First Version
