<?php
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
	
	if (is_file("../json/current.json")) {
		$datos_current = file_get_contents("../json/current.json");
	}
	
	$arr_list = array(
				"lista_reproduccion"	=> $lista_reproduccion,
				"lista_current"     => $datos_current
	);
	
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;

?>
