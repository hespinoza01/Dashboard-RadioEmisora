<?php
		$ruta="../json/lista.json";
		if (is_file($ruta)) {
			$datos_lista = file_get_contents($ruta);
			$array_lista = json_decode($datos_lista, true);
			$array_lista['revolver']='true';
			$fh = fopen($ruta, 'w');
			fwrite($fh, json_encode($array_lista,JSON_UNESCAPED_UNICODE));
			$codigo=fclose($fh);
		}
?>
