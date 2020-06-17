<?php

require_once 'data.php';
include 'no_cache_header.php';

$datos_lista1= new Lista1();

$ruta="../json/lista1.json";
if (is_file($ruta)) {
	$array_lista = $datos_lista1->Load()->Get();

	foreach ($array_lista as $key => $lista) {
		if ( $lista['current_lista']== $_REQUEST['current_lista']) {
			$lista_nueva=$lista['lista'];
			break;	
		}
	}
}

$lista=array(
		'time_control' => date('Y-m-d G:i:s'),
		'current_lista' => $_REQUEST['current_lista'],
		'lista' 		=> $lista_nueva,
		'revolver'		=> false
);

$datos_lista = new Lista();
$datos_lista->Set($lista);

$code = $datos_lista->Save();
echo $datos_lista->GetString();	

?>
