﻿<?php

require_once 'data.php';
include 'no_cache_header.php';

function listar_directorios_ruta($ruta){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta)) {
        //echo '<select name="listcarp" id="listcarp" class="listcarp">'; 
        echo '<select required name="listcarp" id="listcarp" class="formupload">';
        echo '<option value disabled selected>-Selecciona una carpeta-</option>';
           if ($dh = opendir($ruta)) {
                $dirs = scandir($ruta);
                $list_dirs = array_filter($dirs, function($item) use($ruta){ return is_dir($ruta.$item) && !in_array($item, ['.','..']); });

                asort($list_dirs);

                foreach ($list_dirs as $dir) {
                    //solo si el archivo es un directorio, distinto que "." y ".."
                    $val64 = explode("_",$dir);
                    if($val64[0]!="fonts" && $val64[0]!="images" && $val64[0]!="js"&& $val64[0]!="AUDIOS"&& $val64[0]!="css"&& $val64[0]!="imagenes") {
                        echo "<option value=\"$dir\">$dir</option>";
                        }
                  }    
          
               closedir($dh);
            }
       echo '</select>';
    }else
       echo "<br>No es ruta valida";
}

	function listar_comerciales(){
		echo "<span><strong>LISTA DE COMERCIALES:</strong></span><br><br>";
		if (is_file("../json/comerciales.json")) {
			$datos_comerciales = new Comerciales();
			$array_comerciales = $datos_comerciales->Load()->Get();

			if(count($array_comerciales)==0){
				echo "No hay comerciales registrados...<br>";
				echo "_______________________________________________________________________<br>";			
			}

			sort($array_comerciales);

			for($i=0;$i<count($array_comerciales);$i++){
				echo (($i<11)?('0'.($i+1)):($i+1))."&nbsp;&nbsp;&nbsp;|<span><strong>ID:</strong> ".$array_comerciales[$i]['ID']."  <strong>Tipo:</strong> ".(($array_comerciales[$i]['tipo']==1)?'1-GENERAL':(($array_comerciales[$i]['tipo']==2)?'2-GENEROS':'3.-ENTRADAS'));

				echo " <strong>Descripcion:</strong> ".$array_comerciales[$i]['descripcion']." <strong>Tracks:</strong> ".$array_comerciales[$i]['Ntracks'];

				echo " <strong>Revolver:</strong> ".$array_comerciales[$i]['modo_revolver']."<a name='eliminar-comercial' href='../php/eliminar_comercial.php?ID=".$array_comerciales[$i]['ID']."&pagina=comerciales'> ELIMINAR </a>";

				echo " <a name='editar-comercial' href='../php/editar_comercial.php?ID=".$array_comerciales[$i]['ID']."&pagina=comerciales'> EDITAR </a><br>";

				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Carpeta:</strong> ../".$array_comerciales[$i]['carpeta']."/ </span><br>";

				$array_lista=[];

				for($j=0;$j<count($array_comerciales[$i]['lista']);$j++){
					$array_lista[$j]=basename($array_comerciales[$i]['lista'][$j]);
				}

				$lista="";
				$lista=implode("<br>",$array_lista);
                
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Lista: ".count($array_comerciales[$i]['lista'])." Audios</strong><div style='background-color: yellow; border: solid green; width: 100%; height:115px; overflow: auto;'> $lista </div></span>";
				echo "_______________________________________________________________________<br>";			
			}
		}
		else{
			echo "No hay comerciales registrados...<br>";
			echo "_______________________________________________________________________<br>";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
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
		<h2 align> CONFIGURACIÓN DE COMERCIALES </h3>
    </div>	
	<form id="form_comercial" action="../php/guardar_comercial.php?opcion=0&ID=" method="post">
	<div class="container2">
	  
		 <div class="col-6 fz-20">
		
			<span>ID del Comercial:</span>
			<input required type="text" name="id_comercial" id="id_comercial"  class="formupload" autocomplete="off"/>
			<br>
			<span>Tipo Comercial:</span>
			<select required name="tipo_comercial" id="tipo_comercial" class="formupload">
                <option value disabled selected>Tipo Comercial</option>
              	<option value="1">1- General</option>
                <option value="2">2- De Géneros</option>
				<option value="3">3- De Entradas</option>
            </select>
			<br>

			<span>Descripción del Comercial:</span>
			<input required type="text" name="name_comercial" id="name_comercial"  class="formupload" autocomplete="off"/>
			<br>
			
		 </div>
			
		 <div class="col-6 fz-20">
						<span>Cantidad de Tracks:</span>
			<input required type="text" name="n_tracks_comercial" id="n_tracks_comercial" class="formupload" value='0' autocomplete="off"/>
			<br>
			
			<span>Carpeta del Comercial:</span>			
               	<div id="carp_comercial"> 
					<?php echo listar_directorios_ruta(AUDIOS_RUTA); ?>
				</div>

			<br>
			<span>Modo de Revolver:</span>
			<select required name="revolver_comercial" id="revolver_comercial" class="formupload" onchange="verificar_modo(this.value);">
                <option value disabled selected>Modo Revolver</option>
              	<option value="1">1</option>
                <option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
            </select>
			<br>
					<select required name="p_eliminar" id="p_eliminar" style=' display: none; font-size: 15px; width: 30%; margin-top: 5px;'>
						<option value="-1" disabled selected>% Eliminar</option>
						<option value="12.5">12.5%</option>
						<option value="25">25.0%</option>
						<option value="37.5">37.5%</option>
					</select>
		 </div>
		
	</div>
	<br>
	<input style="margin-left: 45%;" type="submit" value="Guardar Comercial" id="guardar_comercial"></input>
	</form>
	<br>
	<br>
	<div class="container2">
	  	<div class="col-20 fz-20">	
			<?php listar_comerciales(); ?>
		</div>
	</div>
	
	<script>
		function verificar_modo(modo){
			if(modo==4){
				document.getElementById("p_eliminar").style='display:block;'; 
			}
			else{
				document.getElementById("p_eliminar").style='display:none;'; 
			}
			
		}
	</script>
</body>
</html>