<?php
require_once 'data.php';
include 'no_cache_header.php';

if(isset($_REQUEST['opcion'])){
	if($_REQUEST['opcion']==1){
		$prueba=explode("/",$_REQUEST['ruta']);
		$ruta_prov='';
		for($i=0;$i<count($prueba)-4;$i++){
				$ruta_prov=$ruta_prov."../";
		}
				
		if (is_file("../json/webaudio.json")) {
			$datos_webaudio = new Webaudio();
			$carpeta=$datos_webaudio->Load()->Get()['carpeta_link'];
		}

		$ruta_antes=$ruta_prov.$carpeta.'/';

		if (is_file("../json/link_es.json")) {
			$datos_enlace= new Link_es();
			$array_enlace = $datos_enlace->Load()->Get();

			foreach ($array_enlace as $key => $enlace) {
				$fecha_final=$enlace['fecha_final'];
				$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($fecha_final);

				if($segundoTimes>0){
					unlink($ruta_antes.$enlace['enlace']);
					unset($array_enlace[$key]);
				}
			}

            $datos_enlace->Set($array_enlace);
            $datos_enlace->Save();	
		}
	}
}
	

function listar_variables(){

	if (is_file("../json/general.json")) {	
		$datos_general= new General();
		$array_general = $datos_general->Load()->Get();

		if(count($array_general)==0){
			echo "No hay variables registradas...<br>";
			echo "_______________________________________________________________________<br>";			
		}
		else{
			echo "
				<script>
					var combo = document.getElementById('revolver_generos');
					var cantidad = combo.length;
					for (let i = 0; i < cantidad; i++) {
						if (combo[i].value == '$array_general[RANDOM]') {
							 combo[i].selected = true;							// SELECCIONAR RANDOM
						}   
					}
					var combo = document.getElementById('separar_genero');
					var cantidad = combo.length;
					for (let i = 0; i < cantidad; i++) {
						if (combo[i].value == '$array_general[SEPARAR_GENERO]') {
							 combo[i].selected = true;							// SELECCIONAR SEPARAR GENERO
						}   
					}
					var combo = document.getElementById('pizzicato');
					var cantidad = combo.length;
					for (let i = 0; i < cantidad; i++) {
						if (combo[i].value == '$array_general[PIZZICATO]') {
							 combo[i].selected = true;							// SELECCIONAR PIZZICATO
						}   
					}
					document.getElementById('inactividad').value='$array_general[tiempo_inactividad]'; // MUESTRA TIEMPO DE INACTIVIDAD
					var combo = document.getElementById('sincro');
					var cantidad = combo.length;
					for (let i = 0; i < cantidad; i++) {
						if (combo[i].value == '$array_general[version]') {
							 combo[i].selected = true;							// SELECCIONAR SINCRONIZAR
						}   
					}
					
					document.getElementById('ronda').value='$array_general[nronda]'; // MUESTRA LA CANTIDAD DE RONDAS
					document.getElementById('usuario').value='$array_general[usuario]'; // MUESTRA EL USUARIO
					document.getElementById('clave').value='$array_general[clave]'; // MUESTRA EL USUARIO
					
					// VARIABLES DE DATOS DE EMISORA
					document.getElementById('nombre_emisora').value='$array_general[nombre_emisora]'; 
					document.getElementById('letra_emisora').value='$array_general[letra_emisora]'; 	
					document.getElementById('slogan_emisora').value='$array_general[slogan_emisora]'; 
					document.getElementById('letra_slogan').value='$array_general[letra_slogan]'; 
					 
					document.getElementById('ancho_logo').value='$array_general[ancho_logo]'; 
					document.getElementById('largo_logo').value='$array_general[largo_logo]'; 
					document.getElementById('redondeo').value='$array_general[redondeo]'; 
					document.getElementById('color_emisora').value='$array_general[color_emisora]';
					document.getElementById('color_slogan').value='$array_general[color_slogan]';
	
					
				</script>
			";
			if(isset($_REQUEST['ruta'])){
				$ruta=substr ($_REQUEST['ruta'],3);
				echo "<script> document.getElementById('url_logo').value='$ruta'; </script>";
			}
			else{
				echo "<script> document.getElementById('url_logo').value='$array_general[url_logo]'; </script>";
			}
		}
	}

	if (is_file("../json/webaudio.json")) {	
		$datos_general= new Webaudio();
		$array_general = $datos_general->Load()->Get();
	
			echo "
				<script>
				
					document.getElementById('cuadro').value='$array_general[cuadro]'; // 
					document.getElementById('numero').value='$array_general[numero]'; // 
					document.getElementById('signo').value='$array_general[signo]'; // 
					
					
					document.getElementById('barra1').value='$array_general[barra1]'; 
					document.getElementById('barra2').value='$array_general[barra2]'; 	
					document.getElementById('barra3').value='$array_general[barra3]'; 
					document.getElementById('barritas').value='$array_general[barritas]'; 
					 
					document.getElementById('tiempo').value='$array_general[tiempo]'; 
					document.getElementById('puntos').value='$array_general[puntos]'; 
					document.getElementById('slash').value='$array_general[slash]'; 
					document.getElementById('guion').value='$array_general[guion]';
					if('$array_general[visibilidad]'=='block')
						document.getElementById('visibilidad').checked='true';	
					
					document.getElementById('tactual').value='$array_general[tactual]'; 
					document.getElementById('trestante').value='$array_general[trestante]';
					document.getElementById('tduracion').value='$array_general[tduracion]';
					
					document.getElementById('tiempo_duracion').value='$array_general[tiempo_duracion]';
					var combo = document.getElementById('modo_duracion');
					var cantidad = combo.length;
					for (let i = 0; i < cantidad; i++) {
						if (combo[i].value == '$array_general[modo_duracion]') {
							 combo[i].selected = true;							// SELECCIONAR MODO DURACION DEL LINK GENERADO
						}   
					}
					
					if('$array_general[visible_compartir]'=='block')
						document.getElementById('visible_compartir').checked='true';	
					
					
					document.getElementById('iframe_ancho').value='$array_general[iframe_ancho]';
					document.getElementById('iframe_largo').value='$array_general[iframe_largo]';
					
					document.getElementById('carpeta_link').value='$array_general[carpeta_link]';
                    document.getElementById('carpeta_link_vieja').value='$array_general[carpeta_link]';
					
				</script>
			";
			
		
	}
}



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="iso-8859-1">
    <?php
        require_once 'no_cache_htmltag.php';
        no_cache_htmltag();
    ?>
    <title>CONFIGURACION RADIO</title>
    <style>
        @font-face {
            font-family: 'timesOne';
            src: URL('../fonts/001.ttf') format('truetype');
        }
        @font-face {
            font-family: 'timesTwo';
            src: URL('../fonts/002.ttf') format('truetype');
        }
        @font-face {
            font-family: 'timesThree';
            src: URL('../fonts/003.ttf') format('truetype');
        }
        @font-face {
            font-family: 'timesSeven';
            src: URL('../fonts/007.ttf') format('truetype');
        }
        .container2 {
            border: 1px solid yellow;
            padding: 10px;
            font-family: 'timesOne';
            margin: 0 auto; }
	.container_comercial{
			width: 403px;
			height: 480px;
			overflow: auto;
			   border: 1px solid black;
            padding: 20px;
			font-size: 15px;
			
		}
		.col-20{
			overflow: auto;
            border: 1px solid black;
            padding: 10px;
		}
		
        .col-6 {
		//margin-top: 10px;
	   // width: 300px;
	//height: 480px;
	 //   	overflow: auto;
            border: 1px solid black;
            padding: 10px; }
        .formupload{
            display: block;
            font-size: 15px;
            width: 100%;
            margin-top: 5px; }
        .fz-15 {
            font-size: 15px; }
        .fz-20 {
            font-size: 15px; }
        .fw-900 {
            font-weight: 900; }
        .dis_1 span, .dis_1 input{
            display: block;
            width: 100%; }
        .dis_2 div {
            padding: 10px 0px; }
        .dis_2 > div:nth-child(2) > span{
            margin-left: 30px; }
        .porc {
            background: #b5e61d;
            padding: 10px; }
        .dis_3 {
            border-top: 1px solid #000;
            margin-top: 10px;
            text-align: center;
            padding-top: 10px; }
            .dis_3 form > select, .dis_3 form > button { font-size: 17px; }
            .dis_3 form > span{ color: red; }
            .dis_3 form > button { 
                background: red;
                border: 1px solid red;
                color: #FFF;
            }

        .listnameaudios, .listnamemusica { display: none; }
        .infoarchivo, #ultarchivobase { font-family: 'timesThree'; text-align: center; font-size: 24px }
        .listnameaudiosdescarga {
            padding: 5px;
            font-size: 17px; }
        .list_group_music {
            border-bottom: 1px solid #000; padding: 5px 0px; text-align: left; }
        .vpb_files_remove_left_inner {
            color: red;
            cursor: pointer;
        }
        .progressCount {
            font-family: 'timesSeven'; font-size: 25px;
        }
        #optype {
            border: 3px solid red;
            box-shadow: 3px 3px 3px #999;
        }
        /* // Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) { 
            .container2 {
                display: block;
            }
        }

        /* // Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) { 
            .container2 {
                display: block;
            }
        }

        /* // Large devices (desktops, 992px and up) */
        @media (min-width: 992px) { 
            .container2 {
                display: flex;
                width: 900px;
            }
            .col-6 {
                width: 50%; }
			.col-20 {
                width: 98%; }
        }

        /* // Extra large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) { 
            .container2 {
                display: flex;
            }
            .col-6 {
                width: 50%; }
			.col-20 {
                width: 98%; }
        }
        .ultarchivo span {
            display: block;
            margin-top: 15px;
        }
        .ultarchivo span:nth-child(2) {
            background: #000;
            padding:10px;
            text-align: center;
            color: yellow;
        }
        .stydownloader {
            margin-top: 15px;
            text-align: center;
        }
        .stydownloader button {
            background: yellow;
            border: 1px solid yellow;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        #add_files {
            height: 260px;
            overflow: auto;
            border: 1px solid #000;
            margin-top: 15px;
            padding: 10px;
            box-shadow: 5px 5px 5px #999;
            margin-bottom: 15px;
        }
		 #add_files2 {
            height: 500px;
            overflow: auto;
            border: 1px solid #000;
            margin-top: 15px;
            padding: 10px;
            box-shadow: 5px 5px 5px #999;
            margin-bottom: 15px;
        }
        #refrescar {
            background: white;
            border: 1px solid;
            box-shadow: 0px 0px 10px #999;
        }
        


    </style>

</head>

<body>
	
   <div align="center">	
		<h2 align> CONFIGURACIÓN GENERAL </h2>
    </div>	
 <form id="form_comercial" action="../php/guardar_general.php" method="post">
	
    <div class="container2">
		<div class="col-6 fz-20">
			<span>Modo de Revolver los Géneros:</span>
			<select required name="revolver_generos" id="revolver_generos" class="formupload">
                		<option value="-1" disabled selected>Modo Revolver</option>
                		<option value="0">0- SIN RANDOM</option>
				<option value="1">1- FISHER YATES</option>
                		<option value="2">2- SATTOLLO</option>
				<option value="3">3- PERMUTACIÓN</option>
            		</select>
			<br>
			<span>Separar Géneros Iguales:</span>
			<select required name="separar_genero" id="separar_genero" class="formupload">
                <option value="-1" disabled selected>Modo de Separar</option>
                <option value="1">1- LIBRE</option>
				<option value="2">2- SEPARAR</option>
            </select>
			<br>
			<span>Efectos Pizzicato:</span>
			<select required name="pizzicato" id="pizzicato" class="formupload">
                <option value="-1" disabled selected>Modo Pizzicato</option>
                <option value="0">0- SIN EFECTO</option>
				<option value="1">1- EFECTOS EN LOS GÉNEROS</option>
                <option value="2">2- EFECTOS EN LOS COMERCIALES</option>
				<option value="3">3- EFECTOS EN AMBOS</option>
            </select>
			<br>
		 </div>
		 <div class="col-6 fz-20">
			<span>Tiempo de Inactividad (Minutos):</span>
			<input required type="text" name="inactividad" id="inactividad" class="formupload" value="0" autocomplete="off"/>
			<br>
			<span>Sincronizar:</span>
			<select required name="sincro" id="sincro" class="formupload">
                <option value="-1" disabled selected>Modo Sincronizar</option>
                <option value="0">0- CANCIONES</option>
				<option value="1">1- CANCIONES Y TIEMPO</option>
            </select>
			<br>
			<span>Cantidad de Rondas:</span>
			<input required type="text" name="ronda" id="ronda" class="formupload" value="0" autocomplete="off"/>
			<br>
		 </div>
    </div>
	<br>
    <div class="container2">
		
		<div class="col-6 fz-20">
			<span>Nombre Emisora:</span>
			<input required type="text" name="nombre_emisora" id="nombre_emisora" class="formupload" value="" autocomplete="off"/>
			<br>
			<span>Color:</span>
			<input required type="text" name="color_emisora" id="color_emisora" style='width:80px' value="0" autocomplete="off"/>
		
			<span>Tamaño Nombre(px):</span>
			<input required type="text" name="letra_emisora" id="letra_emisora" style='width:40px' value="0" autocomplete="off"/>
			<br>
			_____________________________________
			<br>
			<span>URL o Ruta de Logo:</span>
			<input required type="text" name="url_logo" id="url_logo" class="formupload" value="" autocomplete="off"/>
			<span >Buscar Logo:</span>
			<input type="file"  id="archivo2" name="archivo2"  style="margin-top: 15px;" />
			<button type="button" onclick="cargar_imagen();" >Subir</button>
			<br>
			<br>
			<span>Ancho Logo(px):</span>
			<input required type="text" name="ancho_logo" id="ancho_logo" style='width:40px' value="0" autocomplete="off"/>
			<span>Largo Logo(px):</span>
			<input required type="text" name="largo_logo" id="largo_logo" style='width:40px' value="0" autocomplete="off"/>
			<br>
			<br>
			<span>% Redondeo:</span>
			<input required type="text" name="redondeo" id="redondeo" style='width:40px' value="0" autocomplete="off"/> 
		
		 </div>
		 <div class="col-6 fz-20">
			<span>Slogan Emisora:</span>
			<input required type="text" name="slogan_emisora" id="slogan_emisora" class="formupload" value="" autocomplete="off"/>
			<br>
			<span>Color:</span>
			<input required type="text" name="color_slogan" id="color_slogan" style='width:80px' value="0" autocomplete="off"/>
			<span>Tamaño Slogan(px):</span>
			<input required type="text" name="letra_slogan" id="letra_slogan" style='width:40px' value="0" autocomplete="off"/>
		 	<br>
			_____________________________________
			<br>
			<span>Usuario:</span>
			<input required type="text" name="usuario" id="usuario" class="formupload" value="" autocomplete="off"/>
			<br>
			<span>Clave:</span>
			<input required type="password" name="clave" id="clave" class="formupload" value="" autocomplete="off"/>
			<input type="checkbox" name='mostrarClave' id='mostrarClave' style="color:red;   border:1.5px solid blue;" onchange='verClave();' id="cbox1" value=""> Mostrar Clave
			<br>
		 </div>
    </div>
	<br>
	<br>
	   <div class="container2">
		
		<div class="col-6 fz-20">
			<span>Parámetros Web Audio: (Solo colores)</span>
			<br>
			<br>
			<span>Cuadro %:</span>
			<input required type="text" name="cuadro" id="cuadro" style='width:80px' value="" autocomplete="off"/>
			<span>Número %:</span>
			<input required type="text" name="numero" id="numero" style='width:80px' value="" autocomplete="off"/>
			<br>
			<br>
			<span>Signo %:</span>
			<input required type="text" name="signo" id="signo" style='width:80px' value="" autocomplete="off"/>
			<br>
			<br>
			<span>Barra 1:</span>
			<input required type="text" name="barra1" id="barra1" style='width:80px' value="" autocomplete="off"/>
			<span>Barra 2:</span>
			<input required type="text" name="barra2" id="barra2" style='width:80px' value="" autocomplete="off"/>
			<br>
			<br>
			<span>Barra 3:</span>
			<input required type="text" name="barra3" id="barra3" style='width:80px' value="" autocomplete="off"/>
			<span>Barritas:</span>
			<input required type="text" name="barritas" id="barritas" style='width:80px' value="" autocomplete="off"/>
			<br>
			_____________________________________
			<br>
			<span>Tiempo: (Solo colores)</span>
			<br>
			<br>
			<span>Números:</span>
			<input required type="text" name="tiempo" id="tiempo" style='width:80px' value="" autocomplete="off"/>
			<span>2 Puntos:</span>
			<input required type="text" name="puntos" id="puntos" style='width:80px' value="" autocomplete="off"/>
			<br>
			<br>
			<span>Slash:</span>
			<input required type="text" name="slash" id="slash" style='width:80px' value="" autocomplete="off"/>
			<span>Guión Negativo:</span>
			<input required type="text" name="guion" id="guion" style='width:80px' value="" autocomplete="off"/>
			<br>
			_____________________________________
			<br>
			<span>Datos de Link Generados: </span> <img onclick='verificar_link();' src="../imagenes/refresh.png" title='Refrescar Links' width='25' height='25' /> <button onclick="onClickVerLinks(); return false;" style="width: 75px; height: 25px;" title="Ver Links">Ver Links</button>
			<br><br>
			<span>Carpeta:</span>
			<input required type="text" name="carpeta_link" id="carpeta_link" style='width:230px' value="Link" autocomplete="off"/>
            <input required type="text" name="carpeta_link_vieja" id="carpeta_link_vieja" style='width:230px; display: none;' value="Link" autocomplete="off">
			<br><br>
			<span>Duración: </span>
			<input required type="text" name="tiempo_duracion" id="tiempo_duracion" style='width:80px' value="" autocomplete="off"/>
			<select required name="modo_duracion" id="modo_duracion" style='width:130px'>
                <option value="-1" disabled selected>Modo Duración</option>
                <option value="MINUTES">MINUTOS</option>
				<option value="HOURS">HORAS</option>
				<option value="YEARS">AÑOS</option>
            </select>
			
			
		 </div>
		 <div class="col-6 fz-20">
			<span>Tiempo: (Tamaño de Números)</span>
			<br>
			<br>
			<span>Transcurrido (px):</span>
			<input required type="text" name="tactual" id="tactual" style='width:80px' value="" autocomplete="off"/>
			<br>
			<br>
			<span>Total (px):</span>
			<input required type="text" name="tduracion" id="tduracion" style='width:80px' value="" autocomplete="off"/>
			<span>Restante (px):</span>
			<input required type="text" name="trestante" id="trestante" style='width:80px' value="" autocomplete="off"/>
			<br>
			_____________________________________
			<br>
			<span>Tamaño de Iframe del Link Generado: </span>
			<br>
			<br>
			<span>Ancho (px): </span>
			<input required type="text" name="iframe_ancho" id="iframe_ancho" style='width:80px' value="" autocomplete="off"/>
			<span>Largo (px): </span>
			<input required type="text" name="iframe_largo" id="iframe_largo" style='width:80px' value="" autocomplete="off"/>
		
			<br>
			_____________________________________
			<br>
			<span>Visibilidad: Control Lista</span>
			<input type="checkbox" name="visibilidad" id="visibilidad" autocomplete="off"/>
			Compartir</span>
			<input type="checkbox" name="visible_compartir" id="visible_compartir" autocomplete="off"/>
			<br>
			_____________________________________
			<br><br>
			<img src="../imagenes/webaudio1.png" />
			
		 </div>
    </div>
	<br>
	<br>
	<input type="submit" style="margin-left: 45%;" value="Guardar General" id="guardar_general"></input>
 </form>
  <form name="formLogo" style='visibility: hidden; position:absolute; margin-top:-39%; margin-left:10%;' id="formLogo" method="post" action="../php/subir_imagen.php" enctype="multipart/form-data">
	<input type="file"  id="archivo" name="archivo"  style="margin-top: 15px;" />
  </form>
 <br>
 <br>
 
	<?php listar_variables(); ?>
	
	
	<script>
		var ruta=getAbsolutePath();
	
		function verificar_link(){
			//alert('pendiente');
			var xhr = new XMLHttpRequest();
			xhr.open('GET', "configuracion_general.php?opcion=1&ruta="+ruta, true);
			xhr.send(null);
		}
		
		function cargar_imagen(){
			document.getElementById("archivo").files=document.getElementById("archivo2").files;
			document.getElementById("formLogo").submit();
			//alert('aqui');
		}
	
		function verClave(){
			if(document.getElementById("mostrarClave").checked) {
				document.getElementById("clave").type='text';
				//alert('hola');
        // Checkbox is checked..
			} else {
				document.getElementById("clave").type='password';
				//alert('pepe');
        // Checkbox is not checked..
			}
		}
		
				// OBTENER LA RUTA ABSOLUTA
		function getAbsolutePath() {
			var loc = window.location;
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
		}

        function onClickVerLinks(){
            window.open('lista_links.php','_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=30%,left=200,width=900,height=600');
        }
		
	</script>
	
</body>
</html>