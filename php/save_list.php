<?php
	$listas_nuevas= json_decode($_POST['lista'], true);
	

	if($_POST['current_lista']!=0){
		$ruta="../json/lista1.json";
		$datos_lista= file_get_contents($ruta);
		$array_lista = json_decode($datos_lista, true);
		$json_lista= json_encode($array_lista[count($array_lista)-1], JSON_UNESCAPED_UNICODE);	
		$current_lista=$array_lista[count($array_lista)-1]['current_lista'];
		$tmp_lista=$array_lista[count($array_lista)-1]['lista'];
	}
	else{
		$json_lista= json_encode($listas_nuevas[0], JSON_UNESCAPED_UNICODE);
		$current_lista=$listas_nuevas[0]['current_lista'];
		$tmp_lista=$listas_nuevas[0]['lista'];
	}
	$ruta="../json/lista1.json";
	$fh = fopen($ruta, 'w');
	fwrite($fh, json_encode($listas_nuevas,JSON_UNESCAPED_UNICODE));
	$code=fclose($fh);

	$lista=array(
			'time_control' => date('Y-m-d G:i:s'),
			'current_lista' => $current_lista,
			'lista' 		=> $tmp_lista,
			'revolver'		=> false
	);
	
	$ruta="../json/lista.json";
	$fh = fopen($ruta, 'w');
	fwrite($fh, json_encode($lista,JSON_UNESCAPED_UNICODE));
	$code=fclose($fh);
	echo $json_lista;	

?>
