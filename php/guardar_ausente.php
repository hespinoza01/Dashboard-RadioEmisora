<?php

require_once 'data.php';

$revolver="false"; // variable para verificar si se ha hecho cambios en la configuracion

if (is_file("../json/generos.json")) {
	$datos_generos= new Generos();
	$array_generos = $datos_generos->Load()->Get();
	$array_generos_editar = array_values($array_generos);
	$i=0;

	unset($array_generos);

	for($i=0;$i<count($array_generos_editar);$i++){
		if($array_generos_editar[$i]['AUSENTE_PRESENTE']!= $_POST['ausente'.$i]){
			$revolver="true";
		}
		
		$prueba=$_POST['ausente'.$i];	
		$array_generos_editar[$i]['AUSENTE_PRESENTE']=$prueba;
	}
	
	$datos_generos->Set($array_generos_editar);
	$code = $datos_generos->Save();
	
	if ($code != 0) {
		echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/descarga.jpg'><br><h1 style='margin-left:45%'>Registro Exitoso...</h1>";
		echo "<script>setTimeout(function(){window.location='../php/configuracion_ausente.php';}, 2000);</script>";
		//echo 200;
	}
	else{
		echo "<script>alert('Problemas al registrar');</script>";
		echo "<script>window.location='../php/configuracion_ausente.php';</script>";
		echo 500;
	}	
}

?>
