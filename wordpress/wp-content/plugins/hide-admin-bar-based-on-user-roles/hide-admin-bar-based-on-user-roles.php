<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://iamankitpanchal.com/
 * @since             1.0.0
 * @package           Hide_Admin_Bar_Based_On_User_Roles
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Hide Admin Bar From Front End
 * Plugin URI:        https://wordpress.org/plugins/hide-admin-bar-based-on-user-roles/
 * Description:       This plugin is very useful to hide admin bar based on selected user roles and user capabilities.
 * Version:           3.1.0
 * Author:            Ankit Panchal
 * Author URI:        https://iamankitpanchal.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hide-admin-bar-based-on-user-roles
 * Domain Path:       /languages
 */

/*
Hide Admin Bar Based on User Roles is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Hide Admin Bar Based on User Roles is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Hide Admin Bar Based on User Roles. If not, see {URI to Plugin License}.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.7.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HIDE_ADMIN_BAR_BASED_ON_USER_ROLES', '3.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hide-admin-bar-based-on-user-roles-activator.php
 */
function hab_activate_hide_admin_bar_based_on_user_roles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles-activator.php';
	hab_Hide_Admin_Bar_Based_On_User_Roles_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hide-admin-bar-based-on-user-roles-deactivator.php
 */
function hab_deactivate_hide_admin_bar_based_on_user_roles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles-deactivator.php';
	hab_Hide_Admin_Bar_Based_On_User_Roles_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'hab_activate_hide_admin_bar_based_on_user_roles' );
register_deactivation_hook( __FILE__, 'hab_deactivate_hide_admin_bar_based_on_user_roles' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.7.0
 */
function hab_run_hide_admin_bar_based_on_user_roles() {

	$plugin = new hab_Hide_Admin_Bar_Based_On_User_Roles();
	$plugin->run();

}
hab_run_hide_admin_bar_based_on_user_roles();