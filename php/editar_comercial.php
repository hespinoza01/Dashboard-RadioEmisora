<?php

require_once 'data.php';

function listar_directorios_ruta($ruta){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta)) {
        //echo '<select name="listcarp" id="listcarp" class="listcarp">'; 
        echo '<select required name="listcarp" id="listcarp" class="formupload">';
        echo '<option value disabled selected>-Selecciona una carpeta-</option>';
       if ($dh = opendir($ruta)) {
        
          while (($file = readdir($dh)) !== false) {
             //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
             //mostraría tanto archivos como directorios
             //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
             if (is_dir($ruta . $file) && $file!="." && $file!=".."){
                //solo si el archivo es un directorio, distinto que "." y ".."
                $val64 = explode("_",$file);
                if($val64[0]!="fonts" && $val64[0]!="images" && $val64[0]!="js"&& $val64[0]!="AUDIOS"&& $val64[0]!="css"&& $val64[0]!="imagenes") {
                    echo "<option value=\"$file\">$file</option>";
                }
                
             }
          }    
       closedir($dh);
       }
       echo '</select>';
    }else
       echo "<br>No es ruta valida";
}

	function mostrar_comercial(){
		$datos_comerciales = new Comerciales();
		$array_comerciales = $datos_comerciales->Load()->Get();
        
		foreach ($array_comerciales as $key => $comercial) {
			if ( $comercial['ID']==$_REQUEST['ID']) {
				echo " 
					<script>
						document.getElementById('id_comercial').value='$comercial[ID]'; // MOSTRAR ID COMERCIAL
	
					    var combo = document.getElementById('tipo_comercial');
					    var cantidad = combo.length;
					    for (let i = 0; i < cantidad; i++) {
							  if (combo[i].getAttribute('value') == $comercial[tipo]) {
								 combo[i].selected = true;							// SELECCIONAR TIPO COMERCIAL
							  }   
					    }
						document.getElementById('name_comercial').value='$comercial[descripcion]';
						document.getElementById('n_tracks_comercial').value='$comercial[Ntracks]';
						
						var combo = document.getElementById('listcarp');
					    var cantidad = combo.length;
					    for (let i = 0; i < cantidad; i++) {
							  if (combo[i].getAttribute('value') == '$comercial[carpeta]') {
								 combo[i].selected = true;							// SELECCIONAR CARPETA COMERCIAL
							  }   
					    }
						
						var combo = document.getElementById('revolver_comercial');
					    var cantidad = combo.length;
					    for (let i = 0; i < cantidad; i++) {
							  if (combo[i].getAttribute('value') == '$comercial[modo_revolver]') {
								 combo[i].selected = true;							// SELECCIONAR REVOLVER COMERCIAL
							  }   
					    }
					</script>	
				";
				
				if(isset($comercial['p_eliminar'])){
					echo "
						<script>
							if($comercial[modo_revolver]==4){
								document.getElementById('p_eliminar').style='display:block;';
								
									var combo = document.getElementById('p_eliminar');
									var cantidad = combo.length;
									for (let i = 0; i < cantidad; i++) {
										  if (combo[i].getAttribute('value') == '$comercial[p_eliminar]') {
											 combo[i].selected = true;							// PORCENTAJE A ELIMINAR
										  }   
									}
							}
							else{
								document.getElementById('p_eliminar').style='display:none;';
							}
						</script>
					";
				}
				
				break;	
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
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
            border: 2px solid red;
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
	<form id="form_comercial" action="../php/guardar_comercial.php?opcion=1&ID=<?php echo $_REQUEST['ID'];?>" method="post">
	<div class="container2">
	  
		 <div class="col-6 fz-20">
		
			<span>ID del Comercial:</span>
			<input type="text" name="id_comercial" id="id_comercial"  class="formupload" autocomplete="off"/>
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
					<?php echo listar_directorios_ruta("../../audios/"); ?>
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
	<input style="margin-left: 45%;" type="submit" value="Modificar Comercial" id="modificar_comercial"></input>
	</form>
	<?php mostrar_comercial(); ?>
	
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