<?php 

// 替換入口 logo
function new_login_logo() {                                                             /* 自訂登入畫面LOGO */ 
    echo '<style type="text/css">
    .login h1 a { background-image:url('.get_template_directory_uri().'/asset/imgs/company-logo.png) !important; background-size: 270px 110px!important; width:270px!important; height:110px !important; }</style>';
}
add_action('login_head', 'new_login_logo' );

// 變更自訂登入畫面上LOGO的連結
function custom_loginlogo_url($url) { return get_bloginfo('url'); }                     
add_filter('login_headerurl', 'custom_loginlogo_url' );

// 移除控制台左上角WP-LOGO
function remove_wp_logo( $wp_admin_bar ) { $wp_admin_bar->remove_node( 'wp-logo' ); } 
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

// 修改後台底下的wordpress文字宣告
function custom_dashboard_footer () {
    echo '網站設計單位 : <a href="https://e-s.tw" target="_blank">ES Design</a>，技術採用：開源程式<a href="https://wordpress.org/" target="_blank">Wordpress CMS</a>'; 
}
add_filter('admin_footer_text', 'custom_dashboard_footer');

// 隱藏後台右下角wp版本號
function change_footer_version() {return 'Design is a relationship';}
add_filter( 'update_footer', 'change_footer_version', 9999);

//登入用的css
function eslogin_style() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/asset/login.css' );
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/asset/login.css' );
}
add_action( 'login_enqueue_scripts', 'eslogin_style' );

//後台使用的 css
function admin_style() {
    wp_enqueue_style('admin-styles', get_template_directory_uri().'/asset/custom.css');
    wp_enqueue_script('admin-scripts', get_template_directory_uri().'/asset/scripts.js');
}
add_action('admin_enqueue_scripts', 'admin_style');


//關閉 Gutenberg
add_filter( 'use_block_editor_for_post', 'es_disable_gutenberg', 10, 2 );
function es_disable_gutenberg( $can_edit, $post ) {
  if( $post->post_type == 'page' && get_page_template_slug( $post->ID ) == 'page-home.php' ) {
    return true;
  }

  return false;
}

// 移除所有留言
// https://gist.github.com/mattclements/eab5ef656b2f946c4bfb
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
?>