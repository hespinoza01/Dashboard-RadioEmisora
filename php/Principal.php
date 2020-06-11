﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php
	session_start();
//	echo $_SESSION['usuario'];
	if (!isset($_SESSION['usuario']) && !isset($_SESSION['clave'])){
		echo "<img style='margin-left:35%; margin-top:15%;' src='../imagenes/login.png'><br><h1 style='margin-left:35%'>No está autorizado a entrar.</h1>";
		die("<script>setTimeout(function(){window.location='../configuracion.html';}, 2000);</script>");
	} 
	else{
		if (is_file("../json/general.json")) {	
			$datos_general= file_get_contents("../json/general.json");
			$array_general = json_decode($datos_general, true);
		}
		if($_SESSION['usuario']!=$array_general['usuario'] || $_SESSION['clave']!=$array_general['clave']){
			echo "<img style='margin-left:35%; margin-top:15%;' src='../imagenes/login.png'><br><h1 style='margin-left:35%'>Usuario o Clave Incorrecta...</h1>";
			die("<script>setTimeout(function(){window.location='../configuracion.html';}, 2000);</script>");
		}			
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistema Principal</title>

    

    <link rel="stylesheet" href="../css/menu.css" type="text/css" >
	<link rel="stylesheet" href="../css/ventana.css" type="text/css" >
	<link rel="icon" type="image/ico" href="../imagenes/ICO-2.png" sizes="230x230">
	<style type="text/css">
			body {
				overflow-x:hidden;
				overflow-y:hidden;
			}
			
	</style>
	<script src="../js/jquery.min.js"></script>
	 <script src="../js/generar_listas1.js"></script>
	<script language="javascript">
		function cargarFrame(id,src) {
			
			var frame=['formCarpeta','formGeneral','formComerciales','formGeneros','formAusente','formPizzicato','formActual'];
			for(var i=0;i<7;i++){
				var control = document.getElementById(frame[i]);
				if(control!=null){
					var padre=control.parentNode;
					padre.removeChild(control);							
				}
			}
			var iframe = document.createElement("iframe");
			iframe.id = id;
			iframe.src = src;
			iframe.style.position = "absolute";
			iframe.style.top = "80px";
			iframe.style.left = "220px";
			iframe.style.right = "220px";
			iframe.style.width = "80%";
			iframe.style.height = "85%";
			var control = document.getElementById(id)
			if (control==null) {
				document.body.appendChild(iframe);
			}
		}
		
			// SINCRONIZAR CON DATOS GUARDADOS EN JSON
		function procesar_listas(){
			var xhr = new XMLHttpRequest();
			xhr.open('GET', "../php/obtener_variables.php", true);
			xhr.responseType = 'text';
			xhr.onload = function () {
				if (xhr.readyState === xhr.DONE) {
					if (xhr.status === 200) {
						var responjson= JSON.parse(this.responseText);
						console.log(new Date()); // FECHA Y VERSION DEL PROGRAMA
						cargar_variables(responjson);
						//alert('Epaleeeeeeeeeeeeeeeeeee');
						update_configuracion();
						inicializar_variables();
						principio();
		
					}
				}
			};
			xhr.send(null);
		}
	/*	
	function inicializar_lista(){
			//alert('epale');
			var xhr = new XMLHttpRequest();
			xhr.open('GET', "inicializar_lista.php", true);
			xhr.responseType = 'text';
			xhr.send(null);
		}*/

	</script>
	
	
</head>    
<body onload="cargarFrame('formActual','configuracion_actual.php');">

<!-- 
Div de Bienvenida
-->	
	
	<div id="titulo">
		
		
		<label style="font: bold 24px verdana, arial, helvetica, sans-serif; position: absolute; top:15px; margin-left:33%;"> CONFIGURACIÓN DE EMISORA</label> 
		<div class="info-usuario">
			
		</div>
	</div>
<!-- 
Menú Principal del Programa
-->
<div id="principal" style='position: absolute; top:60px;'> <img src="../imagenes/16x16/home.png"> Configuración
	<div id="menu">
		
		  <ul>


	<!-- SEXTA OPCION ACTUAL-->	
					
					<li class="nivel1"><a tabindex="-1" style=" background-repeat: no-repeat;" href="javascript: cargarFrame('formActual','configuracion_actual.php'); "> <img src="../imagenes/16x16/publish.png"> Actual</a>
		
					</li>			
				
	<!-- PRIMERA OPCION Carpetas Audios-->

					<li class="nivel1"><a tabindex="-1" style="background-repeat: no-repeat;" href="javascript: cargarFrame('formCarpeta','conversor.php');"><img src="../imagenes/16x16/project.png"> Carpetas</a>
		
					</li>
		
	<!-- SEGUNDA OPCION General-->	
		
					<li class="nivel1" style="display:block;"><a class="nivel1" tabindex="-1" style="background-repeat: no-repeat;" href="javascript:cargarFrame('formGeneral','configuracion_general.php'); "> <img src="../imagenes/16x16/suppliers.png"> General</a>
				
					</li>
					
	<!-- TERCERA OPCION Comerciales-->	
					
					<li class="nivel1"><a tabindex="-1" style=" background-repeat: no-repeat;" href="javascript:cargarFrame('formComerciales','configuracion_comerciales.php'); "> <img src="../imagenes/16x16/cv.png"> Comerciales</a>
		
					</li>
	
	<!-- CUARTA OPCION Generos-->	
					
					<li class="nivel1" style="display:block;"><a tabindex="-1" style=" background-repeat: no-repeat;" href="javascript: cargarFrame('formGeneros','configuracion_generos.php'); "> <img src="../imagenes/16x16/current-work.png"> Géneros</a>
					
					</li>
	<!-- CUARTA OPCION AUSENTE PRESENTE-->	
					
					<li class="nivel1" style="display:block;"><a tabindex="-1" style=" background-repeat: no-repeat;" href="javascript: cargarFrame('formAusente','configuracion_ausente.php'); "> <img src="../imagenes/16x16/document-library.png"> Ausente Presente</a>
					
					</li>
	
	<!-- QUINTA OPCION PIZZICATO-->	
					
					<li class="nivel1" ><a tabindex="-1" style=" background-repeat: no-repeat;" href="javascript:cargarFrame('formPizzicato','../index-pizzicato.html'); "> <img src="../imagenes/16x16/showreel.png"> Pizzicato</a>
					
					</li>
			
	<!-- SEPTIMA OPCION CERRAR SESION-->	
	
									
					<li class="nivel1"><a tabindex="-1" style=" background-repeat: no-repeat;" href="cerrar_sesion.php"> <img src="../imagenes/16x16/logout.png"> Cerrar Sesión</a></li>
					
		   </ul>

		
		</div>
		
</div>
<img style='position: absolute; margin-top:520px; margin-left:50px;' title='Ir a Emisora' src="../imagenes/sonido.gif" width="100" height="100" onclick="window.open('../index.html','_blank');"/>
<img style='position: absolute; margin-top:430px; margin-left:57px;' title='Refrescar Emisora' src="../imagenes/REFRESCAR.gif" width="90" height="90" onclick="procesar_listas();"/>
</body>
</html>



