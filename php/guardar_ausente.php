<?php
	
	$revolver="false"; // variable para verificar si se ha hecho cambios en la configuracion
	
	if (is_file("../json/generos.json")) {
		$datos_generos= file_get_contents("../json/generos.json");
		$array_generos = json_decode($datos_generos, true);
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
		
		$fh = fopen("../json/generos.json", 'w');
		fwrite($fh, json_encode($array_generos_editar,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
		
		if ($code==true) {
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

?>
