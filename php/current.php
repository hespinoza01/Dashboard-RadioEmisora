<?php

require_once 'data.php';

$lista_nueva= array(
	'time_release' 		=> date('Y-m-d G:i:s'),
	'current_times'  	=> $_REQUEST["current_times"],
	'current_tracks' 	=> $_REQUEST["current_track"],
	'current_lista'		=> $_REQUEST["current_lista"]
);

$current = new Current();
$current->Set($lista_nueva);
$code = $current->Save();

$revolver = 'false';
if (is_file("../json/lista.json")) {
	$datos_lista = new Lista();
	$array_lista = $datos_lista->Load()->Get();
	
}
/*
if($_REQUEST["current_times"]%10==0){
	include 'update_audios.php';
	if($bandera=='OK'){
		$revolver = 'true';
	}
}*/
if($array_lista['revolver']=='true'){
	$revolver = 'true';
}

echo $revolver;

?>
