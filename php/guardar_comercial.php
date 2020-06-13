<?php
	$lista=showFiles('../../audios/'.$_POST["listcarp"].'/');
	sort($lista);
	if($_REQUEST['opcion']==0)
		$ID_COMERCIAL=$_POST["id_comercial"];
	if($_REQUEST['opcion']==1)
		$ID_COMERCIAL=$_POST["id_comercial"];
	if(isset($_POST["p_eliminar"])){
		$p_eliminar=$_POST["p_eliminar"];
	}
	else{
		$p_eliminar='';
	}
	
	$comercial_new = array (
		'ID' 				=> $ID_COMERCIAL,	
		'tipo'				=> $_POST["tipo_comercial"],
		'descripcion' 			=> $_POST["name_comercial"],
		'Ntracks' 			=> $_POST["n_tracks_comercial"],
		'carpeta' 			=> $_POST["listcarp"],
		'lista'				=> $lista,
		'reproduccion' 			=> array(),
		'contador'			=> '0',
		'ultima'			=> '',
		'seleccion_pasado'		=> array(),
		'modo_revolver'			=> $_POST["revolver_comercial"],
		'p_eliminar'					=> $p_eliminar
	);
	
	$revolver="false"; // variable para verificar si se ha hecho cambios en la configuracion
	
	$ruta="../json/comerciales.json";
	if (is_file($ruta)) {
		$datos_comerciales = file_get_contents($ruta);
		$array_comerciales = json_decode($datos_comerciales, true);
		$bandera=true;
		foreach ($array_comerciales as $key => $comercial) {
			if ( $comercial['ID']==$_REQUEST['ID']) {
				$bandera=false;
				if($_REQUEST['opcion']==1){
					
					if($comercial['ID']!=$_POST["id_comercial"] || $comercial['tipo']!= $_POST["tipo_comercial"] || $comercial['descripcion']!=$_POST["name_comercial"] || $comercial['Ntracks']!= $_POST["n_tracks_comercial"]|| $comercial['carpeta']!= $_POST["listcarp"] || $comercial['modo_revolver']!= $_POST["revolver_comercial"]){
						$revolver="true";
					}
					
					$comercial_nuevo = array (
						'ID' 				=> $ID_COMERCIAL,	
						'tipo'				=> $_POST["tipo_comercial"],
						'descripcion' 		=> $_POST["name_comercial"],
						'Ntracks' 			=> $_POST["n_tracks_comercial"],
						'carpeta' 			=> $_POST["listcarp"],
						'lista'				=> $lista,
						'reproduccion' 		=> $comercial['reproduccion'],
						'contador'			=> $comercial['contador'],
						'ultima'			=> $comercial['ultima'],
						'seleccion_pasado'	=> $comercial['seleccion_pasado'],
						'modo_revolver'		=> $_POST["revolver_comercial"],
						'p_eliminar'		=> $p_eliminar
					);
					
					unset($array_comerciales[$key]);
					$array_comerciales = array_values($array_comerciales);
					array_push($array_comerciales, $comercial_nuevo);
					$fh = fopen($ruta, 'w');
					fwrite($fh, json_encode($array_comerciales,JSON_UNESCAPED_UNICODE));
					$code=fclose($fh); 
				}
				break;	
			}
		}
		if($bandera==true){
			array_push($array_comerciales, $comercial_new);
			/* escribimos array en el archivo EMISORAS.JSON*/
			$fh = fopen($ruta, 'w');
			fwrite($fh, json_encode($array_comerciales,JSON_UNESCAPED_UNICODE));
			$code=fclose($fh); 
			$revolver="true";
		}
		else{
			if($_REQUEST['opcion']==0){
				echo "<script>alert('ID repetido... Registrar con otro ID.');</script>";
				echo "<script>window.location='../php/configuracion_comerciales.php';</script>";
				echo 700;
			}
		}
	}
	else{
		$array_comerciales = array();
		array_push($array_comerciales, $comercial_new);
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($array_comerciales,JSON_UNESCAPED_UNICODE));
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
		echo "<script>setTimeout(function(){window.location='../php/configuracion_comerciales.php';}, 2000);</script>";
	}
	else{
		echo "<script>alert('Problemas al registrar');</script>";
		echo "<script>window.location='../php/configuracion_comerciales.php';</script>";
		//echo 500;
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
