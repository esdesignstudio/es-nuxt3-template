<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://iamankitpanchal.com/
 * @since      1.7.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/public
 * @author     Ankit Panchal <ankitmaru@live.in>
 */
class hab_Hide_Admin_Bar_Based_On_User_Roles_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.7.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hide-admin-bar-based-on-user-roles-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hide-admin-bar-based-on-user-roles-public.js', array( 'jquery' ), $this->version, false );

	}

	public function hab_hide_admin_bar(){
		global $wpdb;

		$curUserObj = wp_get_current_user();
		$settings = get_option("hab_settings");		

    	$plgUserRoles = ( isset($settings["hab_userRoles"]) ) ? $settings["hab_userRoles"] : "";
    	$hab_capabilities = ( isset($settings["hab_capabilities"]) )  ? explode(",",$settings["hab_capabilities"]) : "";
    	$hab_disableforall = ( isset($settings["hab_disableforall"]) ) ? $settings["hab_disableforall"] : "";

    	$hab_disableforallGuests = ( isset($settings["hab_disableforallGuests"]) ) ? $settings["hab_disableforallGuests"] : "";

    	$userCap = 0;
    	if( is_array($hab_capabilities) ) {
	    	foreach( $hab_capabilities as $caps ){
		    	if( current_user_can( $caps ) ) { 
		    		$userCap = 1;
		    		break;
		    	}
	    	}
    	}

    	if( $hab_disableforall == 'yes' ){
    		show_admin_bar( false );
    	} else {
    		if( is_array($plgUserRoles) && array_intersect($plgUserRoles, $curUserObj->roles ) ) { 
	    		show_admin_bar( false );
	    	}
	    	if( $userCap == 1 ){
	    		show_admin_bar( false );
	    	}
	    	if( $hab_disableforallGuests == 'yes' && !is_user_logged_in() ){
	    		show_admin_bar( false );
	    	}
    	}
    	
	}


}
