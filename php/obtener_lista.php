<?php

require_once 'data.php';
require_once 'data_file.php';
include 'no_cache_header.php';

if (is_file("../json/lista.json")) {
	$datos_lista = new Lista();
	$array_lista = $datos_lista->Load()->Get();

	foreach ($array_lista as $key => $listado) {
		if ( $listado['current_lista'] == $_POST['current_lista']) {
			$lista_reproduccion=$listado;
			break;	
		}
	}

}

if (is_file("../json/current.json")) {
	$datos_current = read_file("../json/current.json", false);
}

$arr_list = array(
	"lista_reproduccion"	=> $lista_reproduccion,
	"lista_current"     => $datos_current
);

$json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
	echo $json_string;

?>
