<?php

require_once 'data.php';
require_once 'data_file.php';

	if (is_file("../json/generos.json")){ //or die('no')) file_put_contents("php://output", "=====>> ".'no existe'); //{
		$datos_generos= new Generos();
		$array_generos = $datos_generos->Load()->Get();

		foreach ($array_generos as $key => $genero) {
				$lista=showFiles(AUDIOS_RUTA.$genero["carpeta"].'/');
				if(count($lista)!=count($genero["lista"])){
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
						'modo_revolver'					=> $genero['modo_revolver'],
						'p_eliminar'					=> $genero['p_eliminar']
					);
					unset($array_generos[$key]);
					$array_generos = array_values($array_generos);
					array_push($array_generos, $genero_nuevo);
				}
				//sort($array_generos);
		}
		$datos_generos->Set($array_generos);
		$code = $datos_generos->Save();
		$datos_generos= read_file("../json/generos.json", false);
		//echo $code;
	}

	if (is_file("../json/comerciales.json")) {
		$datos_comerciales = new Comerciales();
		$array_comerciales = $datos_comerciales->Load()->Get();

		foreach ($array_comerciales as $key => $comercial) {
				$lista=showFiles(AUDIOS_RUTA.$comercial["carpeta"].'/');
				if(count($lista)!=count($comercial["lista"])){
					
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
						'modo_revolver'			=> $comercial['modo_revolver'],
						'p_eliminar'			=> $comercial['p_eliminar']
					);
					unset($array_comerciales[$key]);
					$array_comerciales = array_values($array_comerciales);
					array_push($array_comerciales, $comercial_nuevo);
				}
				//sort($array_comerciales);
		}
		$datos_comerciales->Set($array_comerciales);
		$code =$datos_comerciales->Save();
		$datos_comerciales = read_file("../json/comerciales.json", false);
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
	//echo 'super'
?>
