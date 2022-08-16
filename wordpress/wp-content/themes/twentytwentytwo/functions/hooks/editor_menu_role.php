<?php
function editor_menu_role()
{
    $user = wp_get_current_user();
    if (in_array('editor', (array) $user->roles)) {
        if (!current_user_can('edit_theme_options')) {
            $role_object = get_role('editor');
            $role_object->add_cap('edit_theme_options');
        }
    }
}
