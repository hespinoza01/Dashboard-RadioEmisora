<?php
		
	if (is_file("../json/generos.json")) {
		$datos_generos = file_get_contents("../json/generos.json");
		$array_generos = json_decode($datos_generos, true);
		
		foreach ($array_generos as $key => $genero) {
			if ( $genero['ID']==$_REQUEST['ID']) {
				unset($array_generos[$key]);
				$array_generos =array_values($array_generos);			
			}
		}
		$fh = fopen("../json/generos.json", 'w');
		fwrite($fh, json_encode($array_generos,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh); 
		if ($code==true) {
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
