<?php

function get_seo($field)
{
    $generate = [
        'site_name' => get_bloginfo('name'),
        'title' => wp_get_document_title(),
        'desc' => get_bloginfo('description'),
        'lang' => function_exists('wpm_get_language') ? wpm_get_language() : 'zh',
        'url' => get_permalink(),
        'og_type' => 'website'
    ];

    if($field && count($field)){
        $generate['title'] = $field['title'] ? $field['title'] . ' - ' . get_bloginfo('description') : $generate['title'];
        $generate['desc'] = $field['desc'] ? $field['desc'] : $generate['desc'];
        $generate['thumb'] = $field['thumb'];
    }

    return $generate;
}
