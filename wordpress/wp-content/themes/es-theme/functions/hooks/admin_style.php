<?php
function admin_style()
{
    $url = path_join(get_template_directory_uri(), './src/css/');
    wp_enqueue_style('admin', $url . 'admin.css', null, null, 'all');
}
