<?php

require_once 'data.php';
include 'no_cache_header.php';

function listar_directorios_ruta($ruta){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta)) {
        //echo '<select name="listcarp" id="listcarp" class="listcarp">'; 
        echo '<select required name="listcarp" id="listcarp" class="listcarp">';
        echo '<option value="0" disabled selected>-Selecciona una carpeta-</option>';
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

function listar_directorios_audios($ruta, $name){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta)) {
        echo '<select required name="'.$name.'" id="'.$name.'" class="'.$name.' fz-15">';
        echo '<option value="0" disabled selected>-Selecciona una carpeta-</option>';
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

function listar_directorios_descarga($ruta){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta)) {
        echo '<select name="listnameaudiosdescarga" id="listnameaudiosdescarga" class="listnameaudiosdescarga fz-15">';
        echo '<option value="0" disabled selected>-Selecciona una carpeta-</option>';
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


function listar_carpetas(){
	//phpinfo();
    //require_once 'data.php';
	$ruta = AUDIOS_RUTA;

	 echo "<span><strong>LISTA DE CARPETAS:</strong></span><br><br>";

	 if (is_dir($ruta)) {
	   if ($dh = opendir($ruta)) {
		  	$i=0;
			while (($file = readdir($dh)) !== false) {
				 if (is_dir($ruta . $file) && $file!="." && $file!=".."){
					$val64 = explode("_",$file);
					if($val64[0]!="fonts" && $val64[0]!="js"&& $val64[0]!="AUDIOS"&& $val64[0]!="css"&& $val64[0]!="imagenes") {
						$lista=showFiles(AUDIOS_RUTA.$file.'/');
						sort($lista);
						$tmp_lista=[];
						for($j=0;$j<count($lista);$j++){
							$tmp_lista[$j]=basename($lista[$j])." <a href='../php/eliminar_audio.php?ruta=".$lista[$j]."'>ELIMINAR</a>";
						}
						$lista_string="";
						$lista_string=implode("<br>", $tmp_lista);
						echo (($i<11)?('0'.($i+1)):($i+1))."&nbsp;&nbsp;&nbsp;|<span><strong> CARPETA:</strong>$file<br>";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong> TRACKS:</strong> ".count($lista)."<div style='background-color: beige; border: solid red; width: 100%; height:115px; overflow: auto;'> $lista_string </div></span> ";
						echo "_______________________________________________________________________<br>";
						$i++;
					}
					
				 }
			}
			
		    closedir($dh);
		    if ($i === 0){
					echo "No hay carpetas creadas...<br>";
					echo "_______________________________________________________________________<br>";	
			 } 
	   }
	}

}
	
function showFiles($path){
	$dir = opendir($path);
	$files = array();
	while ($current = readdir($dir)){
		if( $current != "." && $current != "..") {
				$files[] = $path.$current;
			
		}
	}
	closedir($dir);
	return $files;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
        require_once 'no_cache_htmltag.php';
        no_cache_htmltag();
    ?>
    <title>Convertidor</title>
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
        .container {
            border: 1px solid yellow;
            padding: 10px;
            font-family: 'timesOne';
            margin: 0 auto; }
			
		.col-20{
			overflow: auto;
            border: 1px solid black;
            padding: 10px;
		}	
        .col-6 {
            border: 1px solid black;
            padding: 20px; }
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
            .container {
                display: block;
            }
        }

        /* // Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) { 
            .container {
                display: block;
            }
        }

        /* // Large devices (desktops, 992px and up) */
        @media (min-width: 992px) { 
            .container {
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
            .container {
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
        #refrescar {
            background: white;
            border: 1px solid;
            box-shadow: 0px 0px 10px #999;
        }
        


    </style>
</head>
<script type="text/javascript" src="../js/jquery_1.5.2.js"></script>
	<script type="text/javascript" src="../js/vpb_uploader2.js"></script>
	<script type="text/javascript">
$(document).ready(function()
{
	// Call the main function
	new vpb_multiple_file_uploader
	({
		vpb_form_id: "formUpload", // Form ID
		autoSubmit: true,
		vpb_server_url: "../php/upload.php" 
	});
});
</script>
<body>
    <div align="center">	
	<h2 align> CONFIGURACIÓN DE CARPETAS</h3>
    </div>	
    <div class="container">
        <div class="col-6 fz-20">

            <label for="credito"><strong>Seleccione la configuración de archivos a guardar</strong></label> <br>
            <!-- <input type="radio" class="switchopctions_audios" name="switchopctions_audios" onClick="showContent('namecarpaudio', 'listnameaudios', 'switchopctions_audios')" value="1" checked >CREAR CARPETA
            <input type="radio" class="switchopctions_audios" name="switchopctions_audios" onClick="showContent('listnameaudios', 'namecarpaudio', 'switchopctions_audios')" value="2">BUSCAR CARPETA -->
                <input type="radio" id="one_music" class="switchopctions_musica" name="switchopctions_musica" onClick="showContent('nucarp', 'listnamemusica', 'switchopctions_musica'); limpiarAddFile();" value="1" checked >CREAR CARPETA
                <input type="radio" id="radioBuscar" class="switchopctions_musica" name="switchopctions_musica" onClick="showContent('listnamemusica', 'nucarp', 'switchopctions_musica');" value="2">BUSCAR CARPETA
            <br><br>
            <div class="dis_1">
                <span>Nombre Carpeta de Audios</span>
                <!-- <?php // echo listar_directorios_audios("./audios/", "listnameaudios"); ?> -->
                <?php echo listar_directorios_audios(AUDIOS_RUTA, "listnamemusica"); ?>
                <!-- <input type="text" name="namecarpaudio" id="namecarpaudio" class="fz-20" /> -->
                <input type="text" name="nucarp" id="nucarp" class="formupload" />
            </div>
            <div class="dis_2">
                <div id="add_files">
                <!--    <span id="msg_subiendo"></span>
                    <span style="margin-left: 30px;"> Archivo: <strong id="nomfile" class="nomfile">No hay archivos</strong></span> -->
                </div>

                <div class="infoarchivo">
                    <span id="sizefileall" style="color: #ff77ff">0 KB</span>
                    <!-- <span id="sizefile">0 KB</span>
                    <span class="porc" id="porc">0%</span> -->
                    <input type="text" id="cantmusicTotalinput" style="display: none">
                    <span><span style="color: green" id="minmusicatotal">0</span> <span style="color: red">/</span> <strong style="color: blue" id="cantmusicTotal">0</strong></span>
                </div>
            </div>
            
        </div>
        <div class="col-6">

        <!-- <form name="form_id" id="form_id" action="javascript:void(0);" enctype="multipart/form-data" style="width:800px; margin-top:20px;">  
	<input type="file" name="vasplus_multiple_files" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>      
	<input type="submit" value="Upload" style="padding:5px;"/>
</form> -->

        <form name="formUpload" id="formUpload" method="post" action="javascript:void(0);" enctype="multipart/form-data">
            <select required name="optype" id="optype" class="formupload">
                <option value="0" disabled selected>Elegir Extensión de Archivo a Convertir</option>
			<option value="1">SIN CONVERSION</option>
                <option value=".ini">INI</option>
                <option value=".js">JS</option>
                <option value=".json">JSON</option>
                <option value=".log">LOG</option>
                <option value=".pdf">PDF</option>
				<option value=".rtf">RTF</option>
                <option value=".txt">TXT</option>
            </select>
                   

            <input required type="file" class="form-control" id="archivo" name="archivo[]" multiple="" style="margin-top: 15px;" onclick="limpiarAddFile();"/>
            <button type="submit" id="uploader" >Subir</button>
			<div class="ultarchivo">
                <span>Nombre de último archivo convertido</span>
                <span id="ultarchivobase"></span>
            </div>
            

		</form>

       

            <form action="../php/descarga.php" method="post">
                <div class="stydownloader">
                    <?php echo listar_directorios_descarga(AUDIOS_RUTA); ?>
                    <button type="submit" id="descargar" disabled>DOWNLOAD</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 15px">
                <button type="button" id="refrescar"><img src="../imagenes/456.gif" alt="" width="165px"></button>
            </div>

            <div class="dis_3">
                <form action="../php/deletecarp.php" method="post">
                    <span>Eliminar carpetas [ mp3 | ogg | ... ]</span> <br><br>
                    <?php echo listar_directorios_ruta(AUDIOS_RUTA); ?>
                    <button type="submit">Eliminar</button>
                </form>
            </div>

            
        </div>
    </div>
	
	<br>
		<span id='msj' name='msj'></span>
	<br>
	<div class="container">
	  	<div class="col-20 fz-20">	
			<?php listar_carpetas(); ?>
		</div>
	</div>

    <script type="text/javascript">

        var archivo = document.getElementById("archivo");
        // var barra = document.getElementById("barra");
        var nomfile = document.getElementById("nomfile");
		
        // var nomfile = document.getElementsByClassName("nomfile");
        // var sizefile = document.getElementById("sizefile");
        var sizefileall = document.getElementById("sizefileall");
        
        // var porc = document.getElementById("porc");
        var porcAll = document.getElementById("porcAll");

        var optype = document.getElementById("optype");
        var nucarp = document.getElementById("nucarp");
        var namecarpaudio = document.getElementById("namecarpaudio");

        

        var listnameaudiosdescarga = document.getElementById("listnameaudiosdescarga");
        var fileListDisplay = document.getElementById('file-list-display');

        var uploader = document.getElementById('uploader');
        var cantmusicTotal = document.getElementById('cantmusicTotal');
        var cantmusicTotalinput = document.getElementById('cantmusicTotalinput');
        var minmusicatotal = document.getElementById('minmusicatotal');

        var msg_subiendo = document.getElementById("msg_subiendo");
        var ultarchivobase = document.getElementById("ultarchivobase");
        var fileList = [];
        var sumtam = 0;
        var scrollheightdinamic = 0;
        
        ultarchivobase.innerHTML = 'No procesados';        


        // Boton de Refrescar la pagina
        document.getElementById("refrescar").addEventListener('click', function() {
            location.reload(true);
        });

        renderFileList = function (filearc, lop) {
                var file = filearc[lop];
                var formData = new FormData();
                var request = new XMLHttpRequest();
            
                formData.append("archivo", file);
                formData.append("optype", optype.value);
                formData.append("nucarp", nucarp.value);
                //formData.append("namecarpaudio", namecarpaudio.value);
                formData.append("listnameaudios", listnameaudios.value);
                //formData.append("listnamemusica", listnamemusica.value);
                formData.append("switchopctions_audios", audiosRadioValue('switchopctions_audios'));
                formData.append("switchopctions_musica", audiosRadioValue('switchopctions_musica'));

                // sumtam += file.size;
                // let nombre = fileant.name.split('.');

                request.upload.addEventListener("progress", function(e) {
                    var p = Math.round((e.loaded/e.total)*100);
                    // porc.innerHTML = p + "%";
                        
                    if(p <= 100) {
                        // msg_subiendo.innerHTML = "Subiendo...";
                        
                        minmusicatotal.innerHTML = lop + 1;
                        // ultarchivobase.innerHTML = nombre[0]+optype.value;
                        if(p==100) {
                            let valor = lop + 1;
                            if(valor <= cantmusicTotalinput.value) {
                                if(lop >=1 ) {
                                    let nombre = filearc[lop-1].name.split('.');
                                    ultarchivobase.innerHTML = nombre[0]+optype.value;
                                }
                                nomfile.innerHTML = file.name;
                                sizefile.innerHTML = formatBytes(file.size);
                                renderFileList(fileList, valor);
                            }
                        }
                    }
                    if(p == 1) {
                        p = 0;
                        // porc.innerHTML = p + "%";
                        // msg_subiendo.innerHTML = "";
                    }
                });
                request.open("POST", "../php/guardar.php");
                // sizefileall.innerHTML = formatBytes(sumtam);
                request.send(formData);

              
        };

        uploader.addEventListener('click', function() {
            // renderFileList(fileList, 0);
        });

        listnameaudiosdescarga.addEventListener('change', function() {
            document.getElementById("descargar").removeAttribute("disabled"); 
        }, false);
 
        var beforeSend = function( name, size ){
            // your code here
            // nomfile.innerHTML = escape(name);
            sizefile.innerHTML = formatBytes(size);
            alert('nombre: '+name);
        }
        
    </script>

<!-- Funcions que se ocupan en el convertidor -->
<script>

    function limpiarAddFile(){
//	alert("Por aqui");
	document.getElementById("add_files").innerHTML="";
//	document.getElementById("archivo").value="";
    }

 
    function crearPeticion () {
        var peticion = null;
        try {
            peticion = new XMLHttpRequest();
        }catch (IntentarMs) {
            try{
            peticion = new ActiveXObject("Msxml2.XMLHTTP");
            }catch (OtroMs){
            try{
                peticion = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (fallo) {
                peticion = null;
            }
            }
        }
        return peticion;
    }

    function formatBytes(bytes) {
        if(bytes < 1024) return bytes + " Bytes";
        else if(bytes < 1048576) return(bytes / 1024).toFixed(2) + " <span style='color: #000'>KB</span>";
        else if(bytes < 1073741824) return(bytes / 1048576).toFixed(2) + " <span style='color: #000'>MB</span>";
        else return(bytes / 1073741824).toFixed(2) + " <span style='color: #000'>GB</span>";
    }

    function audiosRadioValue(nameSwith) { 
        var ele = document.getElementsByName(nameSwith); 
              
        for(i = 0; i < ele.length; i++) { 
            if(ele[i].checked) 
                return ele[i].value; 
        } 
    }

    function showContent(origen, secun, nameinp) {
        var chboxs = document.getElementsByName(nameinp);
	//alert(origen","+secun);
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){

                document.getElementById(origen).style.display = 'block';
                break;
            }
        }
        document.getElementById(secun).style.display = 'none';
        
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
</script>


</body>
</html>