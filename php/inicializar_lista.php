<?php

require_once 'data.php';

$ruta="../json/lista.json";
if (is_file($ruta)) {
	$datos_lista = new Lista();
	$array_lista = $datos_lista->Load()->Get();

	$array_lista['revolver']='true';

	$datos_lista->Set($array_lista);
	$codigo = $datos_lista->Save();
}
?>
