<?php
	if (is_file("../json/lista1.json")) {
		$datos_lista = file_get_contents("../json/lista1.json");
	}
	
	$arr_list = array(
			"lista_reproduccion"	=> $datos_lista
	);
	
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;

?>
