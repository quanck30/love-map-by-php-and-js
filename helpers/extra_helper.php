<?php
function get_post(string $str)
{
    $value = filter_input(INPUT_POST, $str);
    return isset($value) ? trim($value) : null;
}
