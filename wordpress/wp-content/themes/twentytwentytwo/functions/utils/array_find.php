<?php
function array_find(Array $arr, Callable $callback = null)
{
    foreach ($arr as $key => $value) {
        if ($callback($value, $key, $arr)) {
            return $value;
        }
    }
    return null;
}
