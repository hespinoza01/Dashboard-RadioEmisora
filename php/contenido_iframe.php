<?php  

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$serverhost = $_SERVER['HTTP_HOST'];

$path = $protocol.$serverhost.'/index.html';

header('Location: '.$path);
die();

?>