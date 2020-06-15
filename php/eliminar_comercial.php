<?php

require_once 'data.php';
		
if (is_file("../json/comerciales.json")) {
	$datos_comerciales = new Comerciales();
	$array_comerciales = $datos_comerciales->Load()->Get();
	
	foreach ($array_comerciales as $key => $comercial) {
		if ( $comercial['ID']==$_REQUEST['ID']) {
			unset($array_comerciales[$key]);
			$array_comerciales =array_values($array_comerciales);			
		}
	}

	$datos_comerciales->Set($array_comerciales);
	$code = $datos_comerciales->Save(); 

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
