<?php

require_once 'data.php';

$listas_nuevas= json_decode($_POST['lista'], true);


if($_POST['current_lista']!=0){
	$ruta="../json/lista1.json";
	$datos_lista= new Lista1();
	$array_lista = $datos_lista->Load()->Get();

	$json_lista= json_encode($array_lista[count($array_lista)-1], JSON_UNESCAPED_UNICODE);	
	$current_lista=$array_lista[count($array_lista)-1]['current_lista'];
	$tmp_lista=$array_lista[count($array_lista)-1]['lista'];
}
else{
	$json_lista= json_encode($listas_nuevas[0], JSON_UNESCAPED_UNICODE);
	$current_lista=$listas_nuevas[0]['current_lista'];
	$tmp_lista=$listas_nuevas[0]['lista'];
}

$ruta="../json/lista1.json";

$datos_lista= new Lista1();
$datos_lista->Set($listas_nuevas);
$code = $datos_lista->Save();

$lista=array(
		'time_control' => date('Y-m-d G:i:s'),
		'current_lista' => $current_lista,
		'lista' 		=> $tmp_lista,
		'revolver'		=> false
);

$ruta="../json/lista.json";
$datos_lista= new Lista1();
$datos_lista->Set($lista);
$datos_lista->Save();

echo $json_lista;	

?>
