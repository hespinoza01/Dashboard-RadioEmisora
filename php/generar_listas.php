<?php
	$lista_nueva= $_POST['lista_generada'];
	//echo $lista_nueva;
	$ruta="../json/lista.json";
	$fh = fopen($ruta, 'w');
	fwrite($fh, json_encode($lista_nueva,JSON_UNESCAPED_UNICODE));
	$code=fclose($fh);
	

	if (is_file("../json/lista.json")) {
		$datos_lista = file_get_contents("../json/lista.json");
		$array_lista = json_decode($datos_lista, true);
		foreach ($array_lista as $key => $listado) {
			if ( $listado['current_lista'] == $_POST['current_lista']) {
				$lista_reproduccion=$listado;
				break;	
			}
		}

	}
	$lista_nueva= array(
		'time_release' 		=> date('Y-m-d G:i:s'),
		'current_times'  	=> '0',
		'current_tracks' 	=> '0',
		'current_lista'		=> '0'
	);
	$ruta = "../json/current.json";
	if (is_file($ruta)) {
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($lista_nueva,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);

	}
	
	$arr_list = $lista_reproduccion;
	
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;
	
?>
