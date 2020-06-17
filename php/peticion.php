<?php
include 'no_cache_header.php';

try {
    $jsondata = file_get_contents('../json/valores.json');
    echo $jsondata;
} catch (Exception $e) {
    return false;
}

?>