<?php
function array_find_index(Array $arr = null, Callable $callback = null)
{
    $index = 0;
    foreach ($arr as $key => $value) {
        if ($callback($value, $key, $arr)) {
            return $index;
        }
        $index++;
    }
    return -1;
}
