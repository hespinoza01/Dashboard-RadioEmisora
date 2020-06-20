<?php 

/*echo realpath(dirname(__FILE__));
echo "</br>";
echo str_replace( basename(__FILE__) , '',str_replace($_SERVER["DOCUMENT_ROOT"],'',str_replace('\\','/',__FILE__ )  ) );
echo "</br>";
echo $_SERVER['DOCUMENT_ROOT'];

echo "</br>";
echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
echo "</br>";
echo basename($_SERVER["SCRIPT_FILENAME"]);

echo "<hr>";

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$path = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$current = "php/".basename($_SERVER["SCRIPT_FILENAME"]);

$full = substr($path, 0, -(strlen($current)));
echo $full;
echo "<hr>";

$num = "000000002";

echo wordwrap($num, 2, "m", true);*/

/*require 'data_file.php';

$file = read_file('../json/general.jon');
print_r($file);

echo "</br>";
echo $_SERVER['DOCUMENT_ROOT'].__DIR__;
echo "</br>";
echo getcwd();
echo "</br>";
echo write_file("e", "s");*/
/*
require_once 'data.php';

$a = new GenerosAP();
$b = new Generos();
$c = new Comerciales();

print_r($a->Load()->GetString());

echo "<hr>";
print_r($b->Load()->GetString());

echo "<hr>";
print_r($c->Load()->GetString());

echo "<hr>";
echo AUDIOS_RUTA;*/

print_r($_POST);
echo $_SERVER['REQUEST_METHOD'];
echo count($_POST).", ".count($_GET);
?>