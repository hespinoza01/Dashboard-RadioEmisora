<?php  

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$serverhost = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$current = "php/".basename($_SERVER["SCRIPT_FILENAME"]);

$fullpath = substr($serverhost, 0, -(strlen($current)));

$path = $fullpath.'index.html';

header('Location: '.$path);
die();

?>