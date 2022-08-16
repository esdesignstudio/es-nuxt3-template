<?php


// // 作品展示 CPT
// add_action('init', 'executive_events_post_type', 0);
// function executive_events_post_type()
// {
//   register_post_type(
//     'events',
//     array(
//       'labels' => array(
//         'name'          => __('作品'),
//         'singular_name' => __('作品'),
//         'add_new_item'  => __('新增作品'),
//         'edit_item'     => __('編輯作品'),
//         'add_new'       => __('新增活動'),
//       ),
//       'has_archive'   => true,
//       'hierarchical'  => true,
//       'menu_icon'     => 'dashicons-portfolio', //iCon圖標 https://developer.wordpress.org/resource/dashicons/
//       'public'        => true,
//       'rewrite'       => array('slug' => 'events', 'with_front' => false),
//       'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'genesis-seo', 'genesis-cpt-archives-settings'),
//       'taxonomies'    => array('events-type'),
//       'menu_position' => 20,
//     )
//   );
// }

// // 活動CPT
// add_action('init', 'executive_type_taxonomy');
// function executive_type_taxonomy()
// {
//   register_taxonomy(
//     'events-type',
//     'events',
//     array(
//       'labels' => array(
//         'name'          => _x('活動分類', 'taxonomy general name', 'esdesign'),
//         'add_new_item'  => __('新增活動分類', 'esdesign'),
//         'new_item_name' => __('新增活動分類', 'esdesign'),
//       ),
//       'exclude_from_search' => true,
//       'has_archive'         => true,
//       'hierarchical'        => true,
//       'rewrite'             => array('slug' => 'events-cats', 'with_front' => false),
//       'show_ui'             => true,
//       'show_tagcloud'       => false,
//     )
//   );
// }

