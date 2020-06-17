<?php

require_once 'data.php';
require_once 'data_file.php';

if(isset($_REQUEST['opcion'])){
	if($_REQUEST['opcion']==1 || $_REQUEST['opcion']==3 ){ // crea enlace con emisora
		
		$fullpath = substr($_REQUEST['ruta'], 0, -4);
		$inicio=$fullpath."php/contenido_iframe.php";//"index.html";
		$ruta_prov='../'; 
		//die("inicio: ".$inicio." - ".$_REQUEST['ruta']." - ".$_SERVER['DOCUMENT_ROOT']);
		/*for($i=0;$i<count($prueba)-4;$i++){
				$ruta_prov=$ruta_prov."../";
		}*/
		
		if (is_file("../json/webaudio.json")) {
			$datos_webaudio= new Webaudio();
			$array_webaudio = $datos_webaudio->Load()->Get();

			$duracion=$array_webaudio['tiempo_duracion'];
			$modo_duracion=$array_webaudio['modo_duracion'];
			$iframe_ancho=$array_webaudio['iframe_ancho'];
			$iframe_largo=$array_webaudio['iframe_largo'];
			$carpeta=$array_webaudio['carpeta_link'];
		}
	
		$carpeta=str_replace(' ','_',$carpeta);
		//$ruta=$_REQUEST['ruta_hosting'].$carpeta.'/'.$_REQUEST['enlace'];
		$ruta=$fullpath.$carpeta.'/'.$_REQUEST['enlace'];
		$directorio=$ruta_prov.$carpeta.'/';	
		//die("carpeta: ".$carpeta." - ".$_REQUEST['enlace']." - ".$ruta);	
		//Validamos si la ruta de destino existe, en caso de no existir la creamos
		if(!file_exists($directorio)){
			mkdir($directorio, 0777) or die($directorio." - ".$ruta.": No se puede crear el directorio de extracci&oacute;n");	
		}

		$ruta_absoluta=$directorio.$_REQUEST['enlace'];
		

		$fecha=date('Y-m-d G:i:s');
		if($modo_duracion=='HOURS'){
			$nuevafecha = strtotime ( '+'.$duracion.' hour' , strtotime ( $fecha ) );
		}
		if($modo_duracion=='MINUTES'){
			$nuevafecha = strtotime ( '+'.$duracion.' minute' , strtotime ( $fecha ) ) ;
		}
		if($modo_duracion=='YEARS'){
			$nuevafecha = strtotime ( '+'.intval ($duracion)*(8760).' hour' , strtotime ( $fecha ) );
		}

		if (is_file("../json/webaudio.json")) {
            $webaudio = new Webaudio();
            $webaudio = $webaudio->Load()->Get();
            $modo_duracion = $webaudio['modo_duracion'];
        }

        $modo = array(
            "YEARS" => "AÑOS",
            "MINUTES" => "MINUTOS",
            "HOURS" => "HORAS"
        );		

		// guarda enlance en link_es.json
		$enlace_new = array (
				'enlace' 						=> $_REQUEST['enlace'],
				'fecha_generada' 				=> $fecha,
				'fecha_final'					=> 	date ( 'Y-m-j G:i:s' , $nuevafecha ),
				'modo_duracion'					=> $modo[$modo_duracion]		
		);
		
		if (is_file("../json/link_es.json")) {
			$datos_enlace= new Link_es();
			$array_enlace = $datos_enlace->Load()->Get();
			array_push($array_enlace, $enlace_new);
		}
		else{
			$array_enlace = array();
			array_push($array_enlace, $enlace_new);
		}

		$datos_enlace->Set($array_enlace);
		$code = $datos_enlace->Save();
		
		
		// crea el enlance en el inicio del hosting
		//$fh = fopen($ruta_absoluta, 'w');
		$ruta_prueba=substr($_REQUEST['ruta'],0,-5 );
		if($_REQUEST['opcion']==1 || $_REQUEST['opcion']==3 ){
			$cadena="
						<html>
							<meta name='viewport' content='width=device-width, initial-scale=0.9'>
							<meta http-equiv='X-UA-Compatible' content='ie=edge'>
							<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate'>
							<meta http-equiv='Pragma' content='no-cache'>
							<meta http-equiv='Expires' content='0'>
							<meta http-equiv='Last-Modified' content='0'>
							<link rel='icon' type='image/ico' href='$ruta_prueba/imagenes/BALON-LINKS.png' sizes='250x250'>
							<script src='$ruta_prueba/js/jquery.min.js'></script>
							<script>
															
								function verificar_expiracion(){
									var req = new XMLHttpRequest();
									req.open('GET', '$ruta_prueba/php/verificar_expiration_link.php?enlace=$_REQUEST[enlace]&hosting=$ruta_prov$carpeta/', false);
									req.onload = onLoad;
									req.send(null); 
									function onLoad(e) {
										if(e.target.readyState == 4 && e.target.status == 200) {
												//alert(e.target.responseText);
												if(e.target.responseText=='true'){
													location.reload();
												}
										}
									}
								}
							</script>	
							<body onload='verificar_expiracion();'>
								<iframe id='formInicio' src='$inicio' frameBorder='0' scrolling='no' style='width:100%; height:100%;'></iframe>
							</body>
						</html>
			";
		}

		//fwrite($fh, $cadena);
		$code = write_file($ruta_absoluta, $cadena);
		echo $ruta;
		die();
	}
	if($_REQUEST['opcion']==2){ // comprobar si existe el link
		verificar_link($_REQUEST['enlace']);
	}
	
}

function verificar_link($l_enlace){
	$ruta="../json/link_es.json";
	if (is_file($ruta)) {
		$datos_enlace= new Link_es();
		$array_enlace = $datos_enlace->Load()->Get();
		$bandera='true';

		foreach ($array_enlace as $key => $enlace) {
			if ($enlace['enlace']== $l_enlace) {
				$bandera='false';
				echo $bandera;
				die();
			}
		}
	}
	else{
		echo 'true';
		die();
	}
	echo $bandera;
	die();
}

function mostrar_carpeta(){
		if (is_file("../json/webaudio.json")) {
			$datos_general= new Webaudio();
			$array_general = $datos_general->Load()->Get();
			$carpeta=$array_general['carpeta_link'];
		}	
		echo $carpeta;
}

function mostrar_ancho(){
		if (is_file("../json/webaudio.json")) {
			$datos_general= new Webaudio();
			$array_general = $datos_general->Load()->Get();
			$ancho=$array_general['iframe_ancho'];
		}	
		echo $ancho;
}

function mostrar_largo(){
		if (is_file("../json/webaudio.json")) {
			$datos_general= new Webaudio();
			$array_general = $datos_general->Load()->Get();
			$largo=$array_general['iframe_largo'];
		}	
		echo $largo;
}
?>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0"/>
		<meta http-equiv="Last-Modified" content="0">
		<link rel="icon" type="image/ico" href="../imagenes/FAVICON-LINK.png" sizes="250x250">
		
	</head>
	<style>
		@font-face {
			font-family: 'timesOne';
			src: URL('../fonts/631.ttf') format('truetype');
		}
		@font-face {
			font-family: 'timesTwo';
			src: URL('../fonts/632.ttf') format('truetype');
		}
		@font-face {
			font-family: 'timesTree';
			src: URL('../fonts/634.ttf') format('truetype');
		}
		@font-face {
			font-family: 'timesFour';
			src: URL('../fonts/633.ttf') format('truetype');
		}
		input[type="radio"] {
			-ms-transform: scale(1.5); /* IE 9 */
			-webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
			transform: scale(1.5);
			background-color:red;
		}	
		
		 @media (min-width: 320px) { 
			#titulo_link {
				margin-left:0px;
				font-size: 25px;
				text-align: center;
			}
			#p_link {
				position: absolute;
				font-size:22px;
				margin-top:-25px;
				margin-left:35px;
			}
			#f_link {
				position: absolute;
				font-size:22px;
				margin-top:-35px;
				margin-left:35px;
			}
			#link{
				position: absolute;
				margin-top:-20px;
			}
			#link_frame {
				position: absolute;
				margin-top:-30px;
			}
			
			 input[type='radio']:checked:after {
				width: 12px;
				height: 12px;
				border-radius: 15px;
				top: 1px;
				left: 1px;
				position: relative;
				background-color: red;
				content: '';
				display: inline-block;
				visibility: visible;
				border: 1px solid green;
			}
			#btn_link{
				font-size: 20px; 
				margin-top:-50px;
				margin-left:40px; 
				width:167px; 
				height:56px;
			}
			
			#div_link{
				width:250px; 
				height:100px; 
				margin-top:0px;	
				overflow: auto;
			}
			#btn_copiar{
				font-size: 20px; 
				margin-left:90px; 
				width:157px; 
				height:52px;
			}
			#link_generado{
				font-size: 11px;
				width:100px;
			}
		 }
		 
		@media (min-width: 850px) { 
			#titulo_link {	
				margin-left:420px;
				font-size: 30px;
			}
			#p_link, #f_link {
				position: absolute;
				margin-top:0px;
				font-size:30px;
			}
			input[type='radio']:checked:after {
				width: 12px;
				height: 12px;
				border-radius: 15px;
				top: 0px;
				left: 0px;
				position: relative;
				background-color: red;
				content: '';
				display: inline-block;
				visibility: visible;
				border: 1px solid green;
			}
			#link{
				position: absolute;
				margin-top:10px;
			}
			#link_frame {
				position: absolute;
				margin-top:10px;
			}
			#btn_link{
				font-size: 30px; 
				margin-left:150px; 
				margin-top:0px;
				width:217px; 
				height:76px;
			}
			#div_link{
				//height: auto;
				width:800px; 
				height:150px;
				margin-top:10px;
				overflow:hidden;
			}
			
			#btn_copiar{
				font-size: 30px; 
				margin-left:480px; 
				width:217px; 
				height:62px;
			}
			#link_generado{
				font-size: 20px;
				width:680px;
				margin-left:10px;
			}
		}
		
		
	
		#btn_copiar{
			border-radius: 20px;
			display: inline-block;
			padding: 15px 25px;
			//font-size: 30px;
			cursor: pointer;
			text-align: center;
			text-decoration: none;
			outline: none;
			color: #fff;
			background-color: #4CAF50;
			border: none;
			//border-radius: 15px;
			box-shadow: 0 9px #999;
		}
		#btn_link:active {
			box-shadow: 0px 1px 0px hsl(120,0%,40%),
			0px 2px 0px hsl(120,0%,37%),
			0px 3px 0px hsl(120,0%,35%),
			0px 4px 0px hsl(120,0%,33%),
			0px 5px 0px hsl(120,0%,31%),
			0px 6px 0px hsl(120,0%,20%),
			0px 7px 10px #000;
		}
		#btn_copiar:hover{
			background-color: #3e8e41;
		}
		#btn_copiar:active{
			 background-color: #3e8e41;
			  box-shadow: 0 5px #666;
			  transform: translateY(4px);
		}
		
		#pre {
			font-family:timesFour;
		}
		
	</style>
	<body >
	<div style='margin-left:30px'>
		<span id='titulo_link' name='titulo_link' style=' color:red; font-family:timesOne; '>Genere el Link de la Emisora</span>
		<br><br>
	
		<br>
		<input type="radio" name="link" id="link"  value="0" autocomplete="off" onchange='change_opcion();' checked><span id='p_link' style='color:green; font-family:timesTwo;'> Generar Link (Solo)</span></input>
		<br><br>
		<input type="radio" name="link" id="link_frame"  value="1" autocomplete="off" onchange='change_opcion();'><span id='f_link' style='font-family:timesTwo;  color:green;'> Generar Link en Iframe <span style="color:red;">(</span><span style='color:blue;'>Centrado</span><span style='color:red;'>)</span></span></input>
		<br><br>
		<br><br>
		<button type="button" id="btn_link" style="background-color:darkorange; color:white; font-family:timesTree; " onclick="generar_link();">Generar Link</button>
		<br><br>
	
		<div id='div_link' style='background-color:black; color:yellow;  overload=hidden;'>
		
		<p id='link_generado' name='link_generado' style='position:relative;  color:yellow;  font-family:timesFour; color:yellow;'></p>
		
		</div>
		<br>
		<button type="button" id="btn_copiar" name='btn_copiar' onclick='copiarAlPortapapeles("link_generado");' style="background-color:blue; color:white; font-family:timesTree;">C O P I A R</button>
		<input type="text" name="carpeta_link" id="carpeta_link"  style='visibility:hidden;' class="formupload" value="<?php mostrar_carpeta(); ?>" autocomplete="off"/>
		<input type="text" name="ancho_iframe" id="ancho_iframe"  style='visibility:hidden;' class="formupload" value="<?php mostrar_ancho(); ?>" autocomplete="off"/>
		<input type="text" name="largo_iframe" id="largo_iframe"  style='visibility:hidden;' class="formupload" value="<?php mostrar_largo(); ?>" autocomplete="off"/>
	</div>
	</body>
	<script>
		var ruta=getAbsolutePath();
		var disponibilidad_link='false';
		var  generado='';
		//document.getElementById("link_generado").innerHTML=ruta_hosting()+document.getElementById("carpeta_link").value.replace(' ','_')+"/";
				
		function change_opcion(){
			if(document.getElementById("link").checked){
				//document.getElementById("link_generado").innerHTML=ruta_hosting()+document.getElementById("carpeta_link").value.replace(' ','_')+"/";
			}
			else{
				//var tmp_cadena="<center>&NewLine;&nbsp;&nbsp;&nbsp;<iframe src='"+ruta_hosting()+document.getElementById("carpeta_link").value.replace(' ','_')+"/"+"' &NewLine;&nbsp;&nbsp;&nbsp;frameBorder='0' scrolling='no' style='width:300px; &NewLine;&nbsp;&nbsp;&nbsp;height:300px;'></iframe>&NewLine;</center>";
				//document.getElementById("link_generado").innerHTML="<pre>"+tmp_cadena.replace(/</g,"&lt;")+"</pre>";
		   	}
		}
		
		function crear_enlace(){
			var numeros=random_numeros(8);
			var cadena='';
			for(let i=0,j=3;i<numeros.length;i++){
				cadena=cadena+numeros.charAt(i);
				if(i==j){
					cadena=cadena+"-";
					j=j+4;
				}
			}
			cadena=cadena+random_letras(4);
			//alert(cadena);
			return cadena;
		}
		
		function ruta_hosting(){
			/*var pos_link=ruta.indexOf("//");
			var ruta_parcial=ruta.slice(pos_link+2);
			var pos_link2=ruta_parcial.indexOf("/");
			var ruta_inicial=ruta.slice(0,pos_link+pos_link2+3);
			return ruta_inicial;*/
			let hosting = getAbsolutePath();
			return hosting.substring(0, hosting.length-4);
		}
		
		function construir_enlace(generado){
			var xhr = new XMLHttpRequest();
				xhr.open('GET', "generador_link.php?opcion=1&enlace="+generado+"&ruta_hosting="+ruta_hosting()+"&ruta="+ruta, true);
				xhr.responseType = 'text';
				xhr.onload = function () {
					if (xhr.readyState === xhr.DONE) {
						if (xhr.status === 200) {
							var link=this.responseText;
							document.getElementById("link_generado").innerHTML=link;
						}
					}
				};
				xhr.send(null);
		}
		
		function construir_enlace_frame(generado){
			var xhr = new XMLHttpRequest();
				xhr.open('GET', "generador_link.php?opcion=3&enlace="+generado+"&ruta_hosting="+ruta_hosting()+"&ruta="+ruta, true);
				xhr.responseType = 'text';
				xhr.onload = function () {
					if (xhr.readyState === xhr.DONE) {
						if (xhr.status === 200) {
							var link=this.responseText;
							//alert(link);
							//document.getElementById("link_generado").innerHTML=link;
							var tmp_cadena="<center>&NewLine;&nbsp;&nbsp;&nbsp;<iframe src='"+link+"'  &NewLine;&nbsp;&nbsp;&nbsp;frameBorder='0' scrolling='no' style='width:"+document.getElementById("ancho_iframe").value+"px; &NewLine;&nbsp;&nbsp;&nbsp;height:"+document.getElementById("largo_iframe").value+"px;'></iframe>&NewLine;</center>";
							document.getElementById("link_generado").innerHTML="<pre id='pre'>"+tmp_cadena.replace(/</g,"&lt;")+"</pre>";
					
						}
					}
				};
				xhr.send(null);
		}
		
		function comprobar_enlace(generado){
			var req = new XMLHttpRequest();
			req.open('GET', "generador_link.php?opcion=2&enlace="+generado, false);
			req.onload = onLoad;
			req.send(null); 
			function onLoad(e) {
				if(e.target.readyState == 4 && e.target.status == 200) {
						disponibilidad_link=e.target.responseText;
					
				}
			}
		}			
		
		function generar_copiar_link(){
			if(document.getElementById("link").checked){
				construir_enlace(generado);
			}
			else{
				construir_enlace_frame(generado);
			}
		}
		
		function generar_link(){
			while(disponibilidad_link=='false'){
				generado=crear_enlace();
				var enlace=ruta_hosting()+document.getElementById("carpeta_link").value.replace(' ','_')+"/"+generado;
					//alert(generado);
				comprobar_enlace(generado);
			}
			if(disponibilidad_link=='true'){
				if(document.getElementById("link").checked){
					document.getElementById("link_generado").innerHTML=enlace;
					disponibilidad_link='false';
				}else{
					var tmp_cadena="<center>&NewLine;&nbsp;&nbsp;&nbsp;<iframe src='"+ruta_hosting()+document.getElementById("carpeta_link").value.replace(' ','_')+"/"+generado+"'  &NewLine;&nbsp;&nbsp;&nbsp;frameBorder='0' scrolling='no' style='width:"+document.getElementById("ancho_iframe").value+"px; &NewLine;&nbsp;&nbsp;&nbsp;height:"+document.getElementById("largo_iframe").value+"px;'></iframe>&NewLine;</center>";
					document.getElementById("link_generado").innerHTML="<pre id='pre'>"+tmp_cadena.replace(/</g,"&lt;")+"</pre>";
					disponibilidad_link='false';
				}
			}
			
		}
		
		function random_letras(length) {
		   var result           = '';
		   var characters       = 'ABCDEFGLMPQRTWXYZ';
		   var charactersLength = characters.length;
		   for ( var i = 0; i < length; i++ ) {
			  result += characters.charAt(Math.floor(Math.random() * charactersLength));
		   }
		   return result;
		}
		
		function random_numeros(length) {
		   var result           = '';
		   var characters       = '0123456789';
		   var charactersLength = characters.length;
		   for ( var i = 0; i < length; i++ ) {
			  result += characters.charAt(Math.floor(Math.random() * charactersLength));
		   }
		   return result;
		}
		
			// OBTENER LA RUTA ABSOLUTA
		function getAbsolutePath() {
			var loc = window.location;
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
		}
		
		function copiarAlPortapapeles(id_elemento) {
		  generar_copiar_link();
		  var aux = document.createElement("input");
		  var a_copiar = document.getElementById(id_elemento).innerHTML;
		  if(document.getElementById("link_frame").checked){
			  a_copiar = a_copiar.slice(14,-6);
			  a_copiar = a_copiar.replace(/&lt;/g,"<");
			  a_copiar = a_copiar.replace(/&gt;/g,'>');
			  a_copiar = a_copiar.replace(/&nbsp;/g,'');
		  }
		  //alert(a_copiar);
		  aux.setAttribute("value", a_copiar);
		  document.body.appendChild(aux);
		  aux.select();
		  document.execCommand("copy");
		  document.body.removeChild(aux);
		}
	</script>
</html>
