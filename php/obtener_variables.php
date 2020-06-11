<?php
	if (is_file("../json/generos.json")) {
		$datos_generos= file_get_contents("../json/generos.json");
	}
	if (is_file("../json/comerciales.json")) {
		$datos_comerciales = file_get_contents("../json/comerciales.json");
	}
	if (is_file("../json/generos_A_P.json")) {
		$datos_generos_A_P= file_get_contents("../json/generos_A_P.json");
	}
	else{
		$ruta="../json/generos_A_P.json";
		$generos_A_P = array(
			'current_lista' => '0',
			'generos_A_P'	=> array(array(
					'ID' 						=> 0,
					'Name' 						=> "",
					'AUSENTE_PRESENTE'				=> array(),
					'Ntracks' 					=> "",
					'carpeta' 					=> "",
					'lista'						=> array(),
					'reproduccion' 					=> array(),
					'contador'					=> '0',
					'ultima'					=> '',
					'posicion_Perm' 				=> '0',
					'seleccion_pasado'				=> array(),
					'ID_comerciales_generos' 			=> '0',
					'modo_revolver'					=> '0'
					)
			)
		);
	
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($generos_A_P,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
		$datos_generos_A_P=file_get_contents("../json/generos_A_P.json");
	}
	if (is_file("../json/current.json")) {
		$datos_current = file_get_contents("../json/current.json");
		$array_current = json_decode($datos_current, true);
		$timerelease = $array_current['time_release'];
		$current_lista= $array_current['current_lista'];
	}
	else{
		$lista_nueva= array(
			'time_release' 		=> date('Y-m-d G:i:s'),
			'current_times'  	=> 0,
			'current_tracks' 	=> 0,
			'current_lista'		=> 0
		);
		$datos_current = json_encode($lista_nueva,JSON_UNESCAPED_UNICODE);
		$timerelease = $lista_nueva['time_release'];
		$current_lista= $lista_nueva['current_lista'];
	}
	
	$ruta="../json/lista.json";
	$cabeza='';
	if (is_file("../json/lista.json")) {
		$datos_lista = file_get_contents("../json/lista.json");
		$array_lista = json_decode($datos_lista, true);
		$time_control = $array_lista['time_control'];
		$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($time_control);
		$lista_reproduccion=json_encode($array_lista,JSON_UNESCAPED_UNICODE);
		if ($segundoTimes<=15) {
				
				//echo "ATRAS:".$segundoTimes;
				$cabeza="ATRAS";
		}else if($segundoTimes>15){
				$cabeza="ADELANTE";
				//echo "PASO:".$segundoTimes;
		}
		
	}
	else{
		$lista_nueva= array(
			'time_control' 		=> date('Y-m-d G:i:s'),
			'current_lista'		=> 0,
			'lista'				=> json_encode(array(),JSON_UNESCAPED_UNICODE)
		);
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($lista_nueva,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
		$lista_reproduccion=file_get_contents("../json/lista.json");
	}

	if (is_file("../json/general.json")) {
		$datos_variables = file_get_contents("../json/general.json");
		$array_variables = json_decode($datos_variables, true);
		$time_delete = $array_variables['tiempo_inactividad'];
	}
	
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
			"lista_generos_A_P" =>	$datos_generos_A_P,
			"lista_generos" 	=> $datos_generos,
			"lista_comerciales" 	=> $datos_comerciales,
			"lista_reproduccion"	=> $lista_reproduccion,
			"lista_variables"	=> $datos_variables,
			"lista_current"     => $datos_current,
			"revolver" => $revolver,
			"cabeza" => $cabeza
	);
	
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;	

?>
