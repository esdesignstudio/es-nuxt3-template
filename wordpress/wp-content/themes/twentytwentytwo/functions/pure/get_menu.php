<?php
function get_menu(String $menu_type = null)
{
    $generate = [];
    $temp_parents_ID = null;
    $temp_parents = null;
    $menu_items = wp_get_nav_menu_items($menu_type);

    if ($menu_items) {
        foreach ($menu_items as $item) {
            $ID = $item->ID;
            $parent_ID = $item->menu_item_parent;
            $type = $item->type;
            $url = $item->url;

            $data = [
                'ID' => $ID,
                'type' => $type,
                'parent' => $parent_ID,
                'page_id' => $item->object_id,
                'title' => $item->title,
                'url' => $type === 'custom' ? $url : str_replace(get_site_url(), '', $url),
                'target' => $item->target,
                'menu_children' => [],
            ];

            if ($parent_ID) {
                $temp_parents_ID[] = $ID;
                $temp_parents[] = &$data['menu_children'];

                $deep = array_find_index($temp_parents_ID, function ($value) use ($parent_ID) {
                    return $value == $parent_ID;
                });
                $temp_parents[$deep][$ID] = $data;
                continue;
            }
            $temp_parents_ID = [$ID];
            $temp_parents = [&$data['menu_children']];
            $generate[$ID] = $data;
        }
    }

    return $generate;
}
