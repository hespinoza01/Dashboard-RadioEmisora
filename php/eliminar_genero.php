<?php

require_once 'data.php';

if (is_file("../json/generos.json")) {
	$datos_generos = new Generos();
	$array_generos = $datos_generos->Load()->Get();
	
	foreach ($array_generos as $key => $genero) {
		if ( $genero['ID']==$_REQUEST['ID']) {
			unset($array_generos[$key]);
			$array_generos =array_values($array_generos);			
		}
	}

	$datos_generos->Set($array_generos);
	$code = $datos_generos->Save(); 

	if ($code != 0) {
		echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/eliminar.jpg'><br><h1 style='margin-left:45%'>Eliminación Exitosa...</h1>";
		echo "<script>setTimeout(function(){window.location='../php/configuracion_".$_REQUEST['pagina'].".php';}, 2000);</script>";
	}
	else{
		echo "<script>alert('Problemas al eliminar');</script>";
		echo "<script>window.location='../php/configuracion_actual.php';</script>";
		echo 500;
	}
}

?>
