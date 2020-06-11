<?php
	$ruta="../json/lista1.json";
	if (is_file($ruta)) {
		$datos_lista= file_get_contents($ruta);
		$array_lista = json_decode($datos_lista, true);
		foreach ($array_lista as $key => $lista) {
			if ( $lista['current_lista']== $_REQUEST['current_lista']) {
				$lista_nueva=$lista['lista'];
				break;	
			}
		}
	}
	$lista=array(
			'time_control' => date('Y-m-d G:i:s'),
			'current_lista' => $_REQUEST['current_lista'],
			'lista' 		=> $lista_nueva,
			'revolver'		=> false
	);
	$ruta="../json/lista.json";
	$fh = fopen($ruta, 'w');
	$json_lista=json_encode($lista,JSON_UNESCAPED_UNICODE);
	fwrite($fh, $json_lista);
	$code=fclose($fh);
	echo $json_lista;	

?>
