<?php

require_once 'data.php';
require_once 'data_file.php';
include 'no_cache_header.php';

	$datos_generos = new Generos();
	$datos_comerciales = new Comerciales();
	$datos_generos_A_P = new GenerosAP();
	$datos_current = new current();
	$datos_lista = new Lista();
	$datos_variables = new General();

	$datos_generos->Load();
	$datos_comerciales->Load();
	$datos_generos_A_P->Load();
	$datos_current->Load();
	$datos_lista->Load();
	$datos_variables->Load();

	
	$timerelease = $datos_current->Get()['time_release'];
	$current_lista= $datos_current->Get()['current_lista'];
	
	$cabeza='';
	$time_control = $datos_lista->Get()['time_control'];
	$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($time_control);

	if ($segundoTimes<=15) {
		//echo "ATRAS:".$segundoTimes;
		$cabeza="ATRAS";
	}else if($segundoTimes>15){
		$cabeza="ADELANTE";
		//echo "PASO:".$segundoTimes;
	}

	$time_delete = $datos_variables->Get()['tiempo_inactividad'];
	
	// verificamos el tiempo de conectividad
	$start_date = new DateTime($timerelease);
    $since_start = $start_date->diff(new DateTime("NOW"));
	$minutes = $since_start->days * 24 * 60;
	$minutes += $since_start->h * 60;
	$minutes += $since_start->i;
	
	 // Agregmos que el tiempo corra sin problema
    $start_date_user = new DateTime($timerelease);
    $time_start = $start_date_user->diff(new DateTime("NOW"));
    $segundoTimes = $time_start->days * 24 * 60;
    $segundoTimes += $time_start->h * 60;
    $segundoTimes += $time_start->i;
    $segundoTimes += $time_start->s;
	
	if ($time_delete <= $minutes){
		$revolver = false;
	}
	else{
		$revolver = true;
	}
	
	
	$arr_list = array(
			"lista_generos_A_P" =>	$datos_generos_A_P->GetString(),
			"lista_generos" 	=> $datos_generos->GetString(),
			"lista_comerciales" 	=> $datos_comerciales->GetString(),
			"lista_reproduccion"	=> $datos_lista->GetString(),
			"lista_variables"	=> $datos_variables->GetString(),
			"lista_current"     => $datos_current->GetString(),
			"revolver" => $revolver,
			"cabeza" => $cabeza
	);
	//logger(json_encode($arr_list, JSON_FORCE_OBJECT));
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;	

?>
