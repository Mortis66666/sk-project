<?php
include("javascript.php");
function debug_log($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    execute("console.log('Debug: " . $output . "')");
}
