<?php
	if (is_file("../json/general.json")) {
		$datos_variables = file_get_contents("../json/general.json");
		$array_variables = json_decode($datos_variables, true);
		
	}
	
	if (is_file("../json/webaudio.json")) {
		$datos_web = file_get_contents("../json/webaudio.json");
		$array_web = json_decode($datos_web, true);
		
	}

	$arr_list = array(
			"lista_variables"	=> $datos_variables,
			"lista_web" 	=> $datos_web 
	);
	
	$json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;	

?>
