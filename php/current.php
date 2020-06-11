<?php
	$lista_nueva= array(
		'time_release' 		=> date('Y-m-d G:i:s'),
		'current_times'  	=> $_REQUEST["current_times"],
		'current_tracks' 	=> $_REQUEST["current_track"],
		'current_lista'		=> $_REQUEST["current_lista"]
	);
	$ruta = "../json/current.json";
	$fh = fopen($ruta, 'w');
	fwrite($fh, json_encode($lista_nueva,JSON_UNESCAPED_UNICODE));
	$code=fclose($fh);
	$revolver = 'false';
	if (is_file("../json/lista.json")) {
		$datos_lista = file_get_contents("../json/lista.json");
		$array_lista = json_decode($datos_lista, true);
		
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
