<?php
	$lista=showFiles('../audios/'.$_POST["listcarp"].'/');
	sort($lista);
	if($_REQUEST['opcion']==0)
		$ID_GENERO=$_POST["id_genero"];
	if($_REQUEST['opcion']==1)
		$ID_GENERO=$_POST["id_genero"]; //$_REQUEST['ID'];
	if(isset($_POST["p_eliminar"])){
		$p_eliminar=$_POST["p_eliminar"];
	}
	else{
		$p_eliminar='';
	}
	
	$genero_new = array (
		'ID' 						=> $ID_GENERO,
		'Name' 						=> $_POST["name_genero"],
		'AUSENTE_PRESENTE'				=> array(),
		'Ntracks' 					=> $_POST["n_tracks_generos"],
		'carpeta' 					=> $_POST["listcarp"],
		'lista'						=> $lista,
		'reproduccion' 					=> array(),
		'contador'					=> '0',
		'ultima'					=> '',
		'posicion_Perm' 				=> '0',
		'seleccion_pasado'				=> array(),
		'ID_comerciales_generos' 			=> $_POST["comercial_gen"],
		'modo_revolver'					=> $_POST["revolver_lista"],
		'p_eliminar'					=> $p_eliminar
	);
	
	$revolver="false"; // variable para verificar si se ha hecho cambios en la configuracion
	
	$ruta="../json/generos.json";
	if (is_file($ruta)) {
		$datos_generos= file_get_contents($ruta);
		$array_generos = json_decode($datos_generos, true);
		$bandera=true;
		foreach ($array_generos as $key => $genero) {
			if ( $genero['ID']== $_REQUEST['ID']) {
				$bandera=false;
				if($_REQUEST['opcion']==1){
					
					if($genero['ID']!=$_POST["id_genero"] || $genero['Name']!= $_POST["name_genero"] || $genero['Ntracks']!=$_POST["n_tracks_generos"] || $genero['carpeta']!= $_POST["listcarp"] || $genero['ID_comerciales_generos']!=$_POST["comercial_gen"] || $genero['modo_revolver']!= $_POST["revolver_lista"] || $genero['p_eliminar']!= $p_eliminar){
						$revolver="true";
					}
									
					
					$genero_nuevo = array (
						'ID' 							=> $ID_GENERO,
						'Name' 							=> $_POST["name_genero"],
						'AUSENTE_PRESENTE'				=> $genero['AUSENTE_PRESENTE'],
						'Ntracks' 						=> $_POST["n_tracks_generos"],
						'carpeta' 						=> $_POST["listcarp"],
						'lista'							=> $lista,
						'reproduccion' 					=> $genero['reproduccion'],
						'contador'						=> $genero['contador'],
						'ultima'						=> $genero['ultima'],
						'posicion_Perm' 				=> $genero['posicion_Perm'],
						'seleccion_pasado'				=> $genero['seleccion_pasado'],
						'ID_comerciales_generos' 		=> $_POST["comercial_gen"],
						'modo_revolver'					=> $_POST["revolver_lista"],
						'p_eliminar'					=> $p_eliminar
					);
					unset($array_generos[$key]);
					$array_generos = array_values($array_generos);
					array_push($array_generos, $genero_nuevo);
					sort($array_generos);
					$fh = fopen($ruta, 'w');
					fwrite($fh, json_encode($array_generos,JSON_UNESCAPED_UNICODE));
					$code=fclose($fh); 
				}
				break;
				
					
			}
		}
		if($bandera==true){
			array_push($array_generos, $genero_new);
			sort($array_generos);
			/* escribimos array en el archivo EMISORAS.JSON*/
			$fh = fopen($ruta, 'w');
			fwrite($fh, json_encode($array_generos,JSON_UNESCAPED_UNICODE));
			$code=fclose($fh); 
			$revolver="true";
		}
		else{
			if($_REQUEST['opcion']==0){
				echo "<script>alert('ID repetido... Registrar con otro ID.');</script>";
				echo "<script>window.location='../php/configuracion_generos.php';</script>";
				echo 700;
			}
		}
	}
	else{
		$array_generos = array();
		array_push($array_generos, $genero_new);
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($array_generos,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh); 
	}
/*	
	$ruta="../json/lista.json";
	if (is_file($ruta) && $revolver=="true") {
		$datos_lista = file_get_contents($ruta);
		$array_lista = json_decode($datos_lista, true);
		$array_lista['revolver']=$revolver;
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($array_lista,JSON_UNESCAPED_UNICODE));
		$codigo=fclose($fh);
	}
*/	
	if ($code==true) {
		if($_REQUEST['opcion']==1){
				echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/descarga.jpg'><br><h1 style='margin-left:45%'>Modificación Exitosa...</h1>";
		}
		else{
			echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/descarga.jpg'><br><h1 style='margin-left:45%'>Registro Exitoso...</h1>";
		}
		echo "<script>setTimeout(function(){window.location='../php/configuracion_generos.php';}, 2000);</script>";
	}
	else{
		echo "<script>alert('Problemas al registrar');</script>";
		echo "<script>window.location='../php/configuracion_generos.php';</script>";
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
