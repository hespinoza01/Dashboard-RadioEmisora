<?php

require_once 'data.php';

$lista_nueva= array(
	'time_control'			=> date('Y-m-d G:i:s'),
	'current_lista' 		=> $_POST['current_lista'],
	'lista'					=> $_POST['lista'],
	'generos_A_P'			=> $_POST['generos_A_P'],
	'comerciales'			=> $_POST['comerciales'],
	'variables'				=> $_POST['variables']
);


$ruta="../json/lista.json";
if (is_file($ruta)) {
	$datos_lista = new Lista();
	$array_lista = $datos_lista->Load()->Get();
	//$current_lista2 = $array_lista['current_lista'];
	$time_control = $array_lista['time_control'];
	
	$start_date_user = new DateTime($time_control);
		$time_start = $start_date_user->diff(new DateTime("NOW"));
	$segundoTimes = $time_start->days * 24 * 60;
	$segundoTimes += $time_start->h * 60;
	$segundoTimes += $time_start->i;
	$segundoTimes += $time_start->s;
	
	if ($segundoTimes<=10) {
		//echo "ATRAS:".$segundoTimes;
		$json_string = json_encode($array_lista , JSON_FORCE_OBJECT);
	}else if($segundoTimes>10){
		$datos_lista->Set($lista_nueva);
		$code = $datos_lista->Save();
		$json_string = json_encode($lista_nueva, JSON_FORCE_OBJECT);
	//	echo "PASO:".$segundoTimes;
	}
	
	
	
	echo $json_string;	
		
}
else{
	$datos_lista = new Lista();
	$datos_lista->Set($lista_nueva);
	$datos_lista->Save();
	$json_string = json_encode($lista_nueva, JSON_FORCE_OBJECT);
	echo $json_string;	
}

?>
