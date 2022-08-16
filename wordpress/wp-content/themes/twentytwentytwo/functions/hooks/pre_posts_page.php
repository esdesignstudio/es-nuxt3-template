<?php
function pre_posts_page($query)
{
    $custom_post_types = get_post_types([
        'public'   => true,
        '_builtin' => false,
    ], 'names');

    foreach($custom_post_types as $key => $type){
        if (is_post_type_archive($type)) {
            return;
        }
    }

    if($query->is_category() && $query->is_main_query()) {
        $query->set('post_type', $custom_post_types);
        $query->set('posts_per_page', 9);
    }

    if ($query->is_tag() && $query->is_main_query()) {
        $query->set('post_type', $custom_post_types);
        $query->set('posts_per_page', 9);
    }

    if ($query->is_search() && $query->is_main_query()) {
        $query->set('post_type', $custom_post_types);
        $query->set('posts_per_page', 9);
    }
}
