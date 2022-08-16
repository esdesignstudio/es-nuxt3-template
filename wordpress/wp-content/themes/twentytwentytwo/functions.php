<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */

add_filter( 'acf/settings/rest_api_format', function () {
    return 'standard';
} );

require_once 'functions/index.php';
require_once 'api/index.php';

add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
   $origins[] = 'http://192.168.50.5:3000';
   $origins[] = 'http://localhost:3000';
   $origins[] = 'https://dd-a.tw';
   return $origins;
}

register_taxonomy( 'color', ['products', 'projects'], [
    'label'              => '顏色',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);

register_taxonomy( 'bolon_studio', ['products', 'projects'], [
    'label'              => 'BOLON STUDIO™',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);

register_taxonomy( 'specifications', ['products', 'projects'], [
    'label'              => '規格',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);

register_taxonomy( 'space', ['products', 'projects'], [
    'label'              => '適用區域',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);

register_taxonomy( 'area', ['projects'], [
    'label'              => '面積',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);

register_taxonomy( 'nation', ['projects'], [
    'label'              => '國家',
    'public'             => true,
    'show_ui'            => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'show_in_quick_edit' => true,
    'default_term'       => [
        'name' => '未分類',
        'slug' => 'uncategorized',
    ],
]);
add_theme_support('post-thumbnails');

// 移除相同的相關
add_filter('acf/fields/relationship/query', 'acf_fields_relationship_query', 10, 3);
function acf_fields_relationship_query( $args, $field, $post ) {
    $args['post__not_in'] = array( $post );
    return $args;
}

// if ( ! function_exists( 'twentytwentytwo_support' ) ) :

// 	/**
// 	 * Sets up theme defaults and registers support for various WordPress features.
// 	 *
// 	 * @since Twenty Twenty-Two 1.0
// 	 *
// 	 * @return void
// 	 */
// 	function twentytwentytwo_support() {

// 		// Add support for block styles.
// 		add_theme_support( 'wp-block-styles' );

// 		// Enqueue editor styles.
// 		add_editor_style( 'style.css' );

// 	}

// endif;

// add_action( 'after_setup_theme', 'twentytwentytwo_support' );

// if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

// 	/**
// 	 * Enqueue styles.
// 	 *
// 	 * @since Twenty Twenty-Two 1.0
// 	 *
// 	 * @return void
// 	 */
// 	function twentytwentytwo_styles() {
// 		// Register theme stylesheet.
// 		$theme_version = wp_get_theme()->get( 'Version' );

// 		$version_string = is_string( $theme_version ) ? $theme_version : false;
// 		wp_register_style(
// 			'twentytwentytwo-style',
// 			get_template_directory_uri() . '/style.css',
// 			array(),
// 			$version_string
// 		);

// 		// Add styles inline.
// 		wp_add_inline_style( 'twentytwentytwo-style', twentytwentytwo_get_font_face_styles() );

// 		// Enqueue theme stylesheet.
// 		wp_enqueue_style( 'twentytwentytwo-style' );

// 	}

// endif;

// add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

// if ( ! function_exists( 'twentytwentytwo_editor_styles' ) ) :

// 	/**
// 	 * Enqueue editor styles.
// 	 *
// 	 * @since Twenty Twenty-Two 1.0
// 	 *
// 	 * @return void
// 	 */
// 	function twentytwentytwo_editor_styles() {

// 		// Add styles inline.
// 		wp_add_inline_style( 'wp-block-library', twentytwentytwo_get_font_face_styles() );

// 	}

// endif;

// add_action( 'admin_init', 'twentytwentytwo_editor_styles' );


// if ( ! function_exists( 'twentytwentytwo_get_font_face_styles' ) ) :

// 	/**
// 	 * Get font face styles.
// 	 * Called by functions twentytwentytwo_styles() and twentytwentytwo_editor_styles() above.
// 	 *
// 	 * @since Twenty Twenty-Two 1.0
// 	 *
// 	 * @return string
// 	 */
// 	function twentytwentytwo_get_font_face_styles() {

// 		return "
// 		@font-face{
// 			font-family: 'Source Serif Pro';
// 			font-weight: 200 900;
// 			font-style: normal;
// 			font-stretch: normal;
// 			font-display: swap;
// 			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) . "') format('woff2');
// 		}

// 		@font-face{
// 			font-family: 'Source Serif Pro';
// 			font-weight: 200 900;
// 			font-style: italic;
// 			font-stretch: normal;
// 			font-display: swap;
// 			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Italic.ttf.woff2' ) . "') format('woff2');
// 		}
// 		";

// 	}

// endif;

// if ( ! function_exists( 'twentytwentytwo_preload_webfonts' ) ) :

// 	/**
// 	 * Preloads the main web font to improve performance.
// 	 *
// 	 * Only the main web font (font-style: normal) is preloaded here since that font is always relevant (it is used
// 	 * on every heading, for example). The other font is only needed if there is any applicable content in italic style,
// 	 * and therefore preloading it would in most cases regress performance when that font would otherwise not be loaded
// 	 * at all.
// 	 *
// 	 * @since Twenty Twenty-Two 1.0
// 	 *
// 	 * @return void
// 	 */
// 	function twentytwentytwo_preload_webfonts() {
// 	}

// endif;

// add_action( 'wp_head', 'twentytwentytwo_preload_webfonts' );

// Add block patterns
// require get_template_directory() . '/inc/block-patterns.php';
