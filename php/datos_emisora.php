<?php

require_once 'data.php';

if (is_file("../json/general.json")) {
	$datos_variables = new General();
	$array_variables = $datos_variables->Load()->GetString();
	
}

if (is_file("../json/webaudio.json")) {
	$datos_web = new Webaudio();
	$array_web = $datos_web->Load()->GetString();
	
}

$arr_list = array(
		"lista_variables"	=> $array_variables,
		"lista_web" 	=> $array_web 
);

$json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
echo $json_string;	

?>
