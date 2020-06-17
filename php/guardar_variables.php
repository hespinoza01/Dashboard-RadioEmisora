<?php
	// PROCESO DE GUARDADO
require_once 'data.php';
	
$ruta="../json/comerciales.json";
if (is_file($ruta)) {
	$array_comerciales = json_decode($_POST['comerciales'], true);
	$datos_comerciales = new Comerciales();

	$datos_comerciales->Set($array_comerciales);
	$code = $datos_comerciales->Save(); 
}

$ruta="../json/general.json";
if (is_file($ruta)) {
	$array_general = json_decode($_POST['variables'], true);
	$datos_general = new General();

	$datos_general->Set($array_general);
	$code = $datos_general->Save(); 
}

$ruta="../json/generos_A_P.json";
$generos_A_P= array(
	'current_lista' =>	 $_POST['current_lista'],
	'generos_A_P' 	=> json_decode($_POST['generos_A_P'], true)
);

$datos_generos_A_P = new GenerosAP();
$datos_generos_A_P->Set($generos_A_P);
$code = $datos_generos_A_P->Save();
	
/*
	$ruta="../json/lista.json";
	$lista_nueva= array(
		'time_control'			=> date('Y-m-d G:i:s'),
		'current_lista' 		=> $_POST['current_lista'],
		'lista'					=> $_POST['lista'],
		'revolver'				=> 'false'
	);
	if (is_file($ruta)) {
		
		$datos_lista = file_get_contents("../json/lista.json");
		$array_lista = json_decode($datos_lista, true);
		$time_control = $array_lista['time_control'];

	//	$start_date_user = new DateTime($time_control);
		
		//$time_start = $start_date_user->diff(new DateTime("NOW"));
		//$segundoTimes = $time_start->days * 24 * 60;
		//$segundoTimes += $time_start->h * 60;
		//$segundoTimes += $time_start->i;
		//$segundoTimes += $time_start->s;
		if($array_general['tiempo_inactividad']!=0){
			$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($time_control);
		}
		else{
			$segundoTimes =31;
		}
		
		//echo $segundoTimes;
		//echo "<script> console.log($segundoTimes)</script>";
		if ($segundoTimes<=30) {
				//echo "ATRAS:".$segundoTimes;
				$json_lista = json_encode($array_lista , JSON_FORCE_OBJECT);
		}else if($segundoTimes>30){
				$fh = fopen($ruta, 'w');
				fwrite($fh, json_encode($lista_nueva,JSON_FORCE_OBJECT));
				$code=fclose($fh);
				$json_lista = json_encode($lista_nueva, JSON_FORCE_OBJECT);
				//echo "PASO:".$segundoTimes;
				
		}
	}
	*/
	// PROCESO DE OBTENCION
	if (is_file("../json/generos.json")) {
		$datos_generos= file_get_contents("../json/generos.json");
	}
	if (is_file("../json/comerciales.json")) {
		$datos_comerciales = file_get_contents("../json/comerciales.json");
	}
	if (is_file("../json/generos_A_P.json")) {
		$datos_generos_A_P= file_get_contents("../json/generos_A_P.json");
	}

	if (is_file("../json/current.json")) {
		$datos_current = file_get_contents("../json/current.json");
		
	}
	if (is_file("../json/general.json")) {
		$datos_variables = file_get_contents("../json/general.json");
	}
	
	$arr_list = array(
			"lista_generos_A_P" =>	$datos_generos_A_P,
			"lista_generos" 	=> $datos_generos,
			"lista_comerciales" 	=> $datos_comerciales,
			//"lista_reproduccion"	=>$json_lista,
			"lista_variables"	=> $datos_variables,
			"lista_current"     => $datos_current
			//"segundos"			=>$segundoTimes
		//	"revolver" => $revolver
	);
	
    $json_string = json_encode($arr_list, JSON_FORCE_OBJECT);
 	echo $json_string;		

?>
