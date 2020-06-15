<?php

require_once 'data.php';

$datos_comerciales = new Comerciales();
$lista=showFiles(AUDIOS_RUTA.$_POST["listcarp"].'/');

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
$code = 0;
if (is_file($ruta)) {
	$array_comerciales = $datos_comerciales->Load()->Get();
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
					'contador'			=> (int)$comercial['contador'],
					'ultima'			=> $comercial['ultima'],
					'seleccion_pasado'	=> $comercial['seleccion_pasado'],
					'modo_revolver'		=> (int)$_POST["revolver_comercial"],
					'p_eliminar'		=> $p_eliminar
				);
				
				unset($array_comerciales[$key]);
				$array_comerciales = array_values($array_comerciales);
				array_push($array_comerciales, $comercial_nuevo);
				
				$datos_comerciales->Set($array_comerciales);
				$datos_comerciales->Save();
			}
			break;	
		}
	}

	if($bandera==true){
		array_push($array_comerciales, $comercial_new);
		/* escribimos array en el archivo EMISORAS.JSON*/

		$datos_comerciales->Set($array_comerciales);
		$code = $datos_comerciales->Save();
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
	
	$datos_comerciales->Set($array_comerciales);
	$code = $datos_comerciales->Save(); 
	
}

if ($code == 0) {
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
