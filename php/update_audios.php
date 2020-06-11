<?php
	$bandera="NO";
	if (is_file("../json/generos.json")) {
		$datos_generos= file_get_contents("../json/generos.json");
		$array_generos = json_decode($datos_generos, true);
		foreach ($array_generos as $key => $genero) {
				$lista=showFiles('../audios/'.$genero["carpeta"].'/');
				if(count($lista)!=count($genero["lista"])){
					$bandera="OK";/*
					$genero_nuevo = array (
						'ID' 						=> $genero['ID'],
						'Name' 						=> $genero['Name'],
						'AUSENTE_PRESENTE'				=> $genero['AUSENTE_PRESENTE'],
						'Ntracks' 					=> $genero['Ntracks'],
						'carpeta' 					=> $genero['carpeta'],
						'lista'						=> $lista,
						'reproduccion' 					=> $genero['reproduccion'],
						'contador'					=> $genero['contador'],
						'ultima'					=> $genero['ultima'],
						'posicion_Perm' 				=> $genero['posicion_Perm'],
						'seleccion_pasado'				=> $genero['seleccion_pasado'],
						'ID_comerciales_generos' 			=> $genero['ID_comerciales_generos'],
						'modo_revolver'					=> $genero['modo_revolver']
					);
					unset($array_generos[$key]);
					$array_generos = array_values($array_generos);
					array_push($array_generos, $genero_nuevo);*/
				}
				//sort($array_generos);
		}/*
		$fh = fopen("../json/generos.json", 'w');
		fwrite($fh, json_encode($array_generos,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
		$datos_generos= file_get_contents("../json/generos.json");*/
		//echo $code;
	}

	if (is_file("../json/comerciales.json")) {
		$datos_comerciales = file_get_contents("../json/comerciales.json");
		$array_comerciales = json_decode($datos_comerciales, true);
		foreach ($array_comerciales as $key => $comercial) {
				$lista=showFiles('../audios/'.$comercial["carpeta"].'/');
				if(count($lista)!=count($comercial["lista"])){
					$bandera="OK";/*
					$comercial_nuevo = array (
						'ID' 					=> $comercial['ID'],	
						'tipo'					=> $comercial['tipo'],
						'descripcion' 			=> $comercial['descripcion'],
						'Ntracks' 				=> $comercial['Ntracks'],
						'carpeta' 				=> $comercial['carpeta'],
						'lista'					=> $lista,
						'reproduccion' 			=> $comercial['reproduccion'],
						'contador'				=> $comercial['contador'],
						'ultima'				=> $comercial['ultima'],
						'seleccion_pasado'		=> $comercial['seleccion_pasado'],
						'modo_revolver'			=> $comercial['modo_revolver']
					);
					unset($array_comerciales[$key]);
					$array_comerciales = array_values($array_comerciales);
					array_push($array_comerciales, $comercial_nuevo);*/
				}
				//sort($array_comerciales);
		}/*
		$fh = fopen("../json/comerciales.json", 'w');
		fwrite($fh, json_encode($array_comerciales,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
		$datos_comerciales = file_get_contents("../json/comerciales.json");*/
		//echo $code;
	}
	
	function showFiles($path){
		$dir = opendir($path);
		$files = array();
		while ($current = readdir($dir)){
			if( $current != "." && $current != "..") {
					$files[] = $path.$current;
			}
		}
		closedir($dir);
		return $files;
	}
	
?>
