<?php 

// 頁籤功能
// $args 為 array 參數，$paged 為目前頁面
function es_get_posts_pagination($args, $paged) 
{
    $paged = (int)$paged;
    $query = new WP_Query($args);

    if ($query -> max_num_pages > 1) {

        // 上一頁
        $pagi_links[] = array(
            'slug'  => $paged !== 1 ? '?page=' . strval($paged - 1) : false,
            'label' => 'prev',
        );

        // 頁籤
        for ($x = 0; $x <= $query -> max_num_pages -1 ; $x++) {

            $paged === $x+1 ? $isActive = true : $isActive = false;
            $curPage = strval($x+1);

            $pagi_links[] = array(
                'slug' => '?page=' . $curPage,
                'label' => $curPage,
                'active' => $isActive
            );
        }

        // 下一頁
        $pagi_links[] = array(
            'slug'  => $paged >= $query -> max_num_pages -1 ? false :  '?page=' . strval($paged + 1),
            'label' => 'next',
        );

        $pagi_data = array(
            'current_page' => $paged,
            'last_page' => $query -> max_num_pages,
            'links' => $pagi_links
        );
        
        return $pagi_data;

    } else {
        return false;
    }

}
?>