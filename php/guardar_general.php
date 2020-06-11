<?php
	
	$variable_new = array (
		'RANDOM' 			=> $_POST["revolver_generos"],
		'SEPARAR_GENERO'		=> $_POST["separar_genero"],
		'PIZZICATO'			=> $_POST["pizzicato"],
		'current_lista'		=> 0,
		'version'			=> $_POST["sincro"],
		'nronda'			=> $_POST["ronda"],
		'temporal' 			=> "",
		'temporal_A_P' 			=> '0',
		'escalar'			=> '-1',
		'permutacion' 			=> array(),
		'activar_permutacion'		=> false,
		'comerciales_generos'		=> false,
		'permutado_pasado'		=> array(),
		'cont_A_P'			=> '-1',
		'conta'				=> '0',
		'iniciar_R_2'			=> false,
		'tiempo_inactividad'		=> $_POST["inactividad"],
		'usuario'				=> $_POST["usuario"],
		'clave'				=> $_POST["clave"],
		'nombre_emisora'	=> $_POST["nombre_emisora"],
		'color_emisora'		=> $_POST["color_emisora"],
		'letra_emisora'		=> $_POST["letra_emisora"],
		'slogan_emisora' 	=> $_POST["slogan_emisora"],
		'color_slogan' 		=> $_POST["color_slogan"],
		'letra_slogan'		=> $_POST["letra_slogan"],
		'url_logo'			=> $_POST["url_logo"],
		'ancho_logo'		=> $_POST["ancho_logo"],
		'largo_logo'		=> $_POST["largo_logo"],
		'redondeo' 			=> $_POST["redondeo"]
		
	);
	if(isset($_POST['visibilidad'])){
		$visible='block';
	}
	else{
		$visible='none';
	}
	if(isset($_POST['visible_compartir'])){
		$visible_compartir='block';
	}
	else{
		$visible_compartir='none';
	}
	if(isset($_POST['cuadro'])){
		$webaudio = array(
			'cuadro'	=> $_POST['cuadro'], 
			'numero'	=> $_POST['numero'], 
			'signo'	=> $_POST['signo'], 
			'barra1'	=> $_POST['barra1'], 
			'barra2'	=> $_POST['barra2'], 
			'barra3'	=> $_POST['barra3'], 
			'barritas'	=> $_POST['barritas'], 
			'tiempo'	=> $_POST['tiempo'], 
			'puntos'	=> $_POST['puntos'], 
			'slash'	=> $_POST['slash'], 
			'guion'	=> $_POST['guion'], 
			'visibilidad'	=> $visible,
			'tactual' => $_POST['tactual'],
			'trestante' => $_POST['trestante'],
			'tduracion' => $_POST['tduracion'],
			'tiempo_duracion' => $_POST['tiempo_duracion'],
			'modo_duracion'	=> $_POST['modo_duracion'],
			'visible_compartir' => $visible_compartir,
			'iframe_ancho'	=> $_POST['iframe_ancho'],
			'iframe_largo'	=> $_POST['iframe_largo'],
			'carpeta_link'	=> $_POST['carpeta_link']
			
		);

		if(strcmp($_POST['carpeta_link'], $_POST['carpeta_link_vieja']) != 0){
			rename("../".$_POST['carpeta_link_vieja'], "../".$_POST['carpeta_link']);
		}

		$fh = fopen("../json/webaudio.json", 'w');
		fwrite($fh, json_encode($webaudio,JSON_UNESCAPED_UNICODE));
		$code=fclose($fh);
	}
	
	$revolver="false";
	if (is_file("../json/general.json")) {
		$datos_general= file_get_contents("../json/general.json");
		$array_general = json_decode($datos_general, true);
		if($array_general['RANDOM'] != $_POST["revolver_generos"] || $array_general['SEPARAR_GENERO'] != $_POST["separar_genero"] || $array_general['PIZZICATO'] != $_POST["pizzicato"] || $array_general['tiempo_inactividad'] != $_POST["inactividad"] ||
		$array_general['version'] != $_POST["sincro"]||$array_general['nronda'] != $_POST["ronda"]){
			$revolver="true";
		}
		$array_general['RANDOM'] = $_POST["revolver_generos"];
		$array_general['SEPARAR_GENERO'] = $_POST["separar_genero"];
		$array_general['PIZZICATO'] = $_POST["pizzicato"];
		$array_general['tiempo_inactividad'] = $_POST["inactividad"];
		$array_general['version'] = $_POST["sincro"];
		$array_general['nronda'] = $_POST["ronda"];
		$array_general['usuario'] = $_POST["usuario"];
		$array_general['clave'] = $_POST["clave"];
		$array_general['nombre_emisora']= $_POST["nombre_emisora"];
		$array_general['color_emisora']	= $_POST["color_emisora"];
		$array_general['letra_emisora']	= $_POST["letra_emisora"];
		$array_general['slogan_emisora']= $_POST["slogan_emisora"];
		$array_general['color_slogan'] = $_POST["color_slogan"];
		$array_general['letra_slogan']= $_POST["letra_slogan"];
		$array_general['url_logo'] = $_POST["url_logo"];
		$array_general['ancho_logo']= $_POST["ancho_logo"];
		$array_general['largo_logo']= $_POST["largo_logo"];
		$array_general['redondeo'] = $_POST["redondeo"];
		$fh = fopen("../json/general.json", 'w');
		fwrite($fh, json_encode($array_general,JSON_UNESCAPED_UNICODE));
	}
	else{
		$fh = fopen("../json/general.json", 'w');
		fwrite($fh, json_encode($variable_new,JSON_UNESCAPED_UNICODE));
	}
	
	$code=fclose($fh);
	

	
	
	
/*	
	$ruta="../json/lista.json";
	if (is_file($ruta) && $revolver=="true") {
		$datos_lista = file_get_contents($ruta);
		$array_lista = json_decode($datos_lista, true);
		$array_lista['revolver']=$revolver;
		$fh = fopen($ruta, 'w');
		fwrite($fh, json_encode($array_lista,JSON_UNESCAPED_UNICODE));
		$codigo=fclose($fh);
	}*/

	if ($code==true) {
		echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/descarga.jpg'><br><h1 style='margin-left:45%'>Registro Exitoso...</h1>";
		echo "<script>setTimeout(function(){window.location='../php/configuracion_general.php';}, 2000);</script>";
	}
	else{
		echo "<script>alert('Problemas al guardar');</script>";
		echo "<script>window.location='../php/configuracion_general.php';</script>";
	}
?>
