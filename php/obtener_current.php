<?php
	$conectividad='SI';
	$crear='NO';
	
	
	if (is_file("../json/current.json")) {
		$datos_current = file_get_contents("../json/current.json");
		$array_current = json_decode($datos_current, true);
		$time_release=$array_current['time_release'];
		$current_lista=$array_current['current_lista'];
		$current_track=$array_current['current_tracks'];
		$current_times=$array_current['current_times'];
		$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($time_release);
		if($segundoTimes>5){
			$conectividad='NO';
			if (is_file("../json/lista.json")) {
				$datos_lista = file_get_contents("../json/lista.json");
				$array_lista = json_decode($datos_lista, true);
				$time_control=$array_lista['time_control'];
				$segundoTimes2 = strtotime(date('Y-m-d G:i:s')) - strtotime($time_control);
				if($segundoTimes2>60){
					$crear='SI';
					unlink("../json/lista.json");
				}
			}
		}
	}
	
	$arr_current=array(
		'conectividad' => $conectividad,
		'current_lista' =>	$current_lista,
		'current_track' => $current_track,
		'crear'			=> $crear,
		'current_times'		=> $current_times
	);
	$json_string = json_encode($arr_current, JSON_FORCE_OBJECT);
 	echo $json_string;
	
?>
