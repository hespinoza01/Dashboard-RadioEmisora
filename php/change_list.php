<?php

require_once 'data.php';
require_once 'data_file.php';

$datos_lista1= new Lista1();
$lista_nueva = array();

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
		'current_lista' => (int)$_REQUEST['current_lista'],
		'lista' 		=> json_encode($lista_nueva, JSON_UNESCAPED_UNICODE),
		'revolver'		=> false
);
//logger(json_encode($lista));
$datos_lista = new Lista();
$datos_lista->Set($lista);

$code = $datos_lista->Save();
echo $datos_lista->GetString();	

?>
