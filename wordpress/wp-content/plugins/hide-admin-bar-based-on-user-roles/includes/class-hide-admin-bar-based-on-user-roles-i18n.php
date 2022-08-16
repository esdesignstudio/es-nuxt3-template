<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://iamankitpanchal.com/
 * @since      1.7.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.7.0
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/includes
 * @author     Ankit Panchal <ankitmaru@live.in>
 */
class hab_Hide_Admin_Bar_Based_On_User_Roles_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.7.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hide-admin-bar-based-on-user-roles',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
