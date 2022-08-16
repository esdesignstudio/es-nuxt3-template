<?php
/* 強制關閉後台登入首頁的小工具 */ 
function wpc_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);        
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);       
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);       
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); 
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);      
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);        
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);        
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard-widgets']);
}
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');
add_filter( 'wp_mail_smtp_admin_dashboard_widget', '__return_false' ); // WP Mail SMTP

remove_action( 'welcome_panel', 'wp_welcome_panel' );

function wpex_wp_welcome_panel() { ?>

	<div class="custom_welcome">
        <div class="custom_welcome__title">
            <h3><?php _e( '歡迎來到 ES Design 客製化網站後台' ); ?></h3>
            <p class="about-description"><?php _e( '此為 ES design 專為客戶客製化設計的主題，內有 ACF Plugin 為必要不可移除，網站程式碼有需要更動建議請聯繫 ES design 設計團隊。如不是由 ES design 團隊所做後續之維護，建議請找懂得使用 PHP 以及熟悉 WordPress Custom Theme 相關 API 以及開發資訊者做修改。' ); ?></p>
        </div>
	<div>

<?php }
add_action( 'welcome_panel', 'wpex_wp_welcome_panel' );