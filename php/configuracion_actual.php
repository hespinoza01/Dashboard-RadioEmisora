<?php

require_once 'data.php';

function listar_variables(){
				
	echo "<span><strong>NOTAS:</strong></span><br>";
	echo "<span>RANDOM GENEROS [0- SIN RANDOM, 1- FISHER YATES, 2- SATTOLO, 3- PERMUTACION]</span><br>";
	echo "<span>SEPARAR GENEROS [1.- LIBRE , 2.- SEPARAR (APLICA A 5 O MAS GENEROS CON RANDOM 1 Y 2)]</span><br>";
	echo "<span>EFECTOS PIZZICATO [0- SIN EFECTO , 1- GENEROS, 2- COMERCIALES, 3- AMBOS]</span><br>";
	echo "_______________________________________________________________________<br>";
	echo "<span><strong>VARIABLES GENERALES:</strong></span><br><br>";
	
	$datos_general= new General();
	$array_general = $datos_general->Load()->Get();


    if(!array_key_exists('success', read_file($datos_general->Path()))){
    	if(count($array_general)==0){
    		echo "No hay variables registradas...<br>";
    		echo "_______________________________________________________________________<br>";			
    	}else{
    		echo "<span>RANDOM GENEROS: $array_general[RANDOM]</span><br>";
    		echo "<span>SEPARAR GENERO: $array_general[SEPARAR_GENERO]</span><br>";
    		echo "<span>EFECTOS PIZZICATO: $array_general[PIZZICATO]</span><br>";
    		echo "<span>TIEMPO DE INACTIVIDAD: $array_general[tiempo_inactividad] MINUTOS</span><br>";
    		echo "<span>SINCRONIZAR: $array_general[version]</span><br>";
    		echo "<span>CANTIDAD DE RONDAS: $array_general[nronda]</span><br>";
    		echo "_______________________________________________________________________<br>";
    	}
    }else{
		echo "No hay variables registradas...<br>";
		echo "_______________________________________________________________________<br>";
	}

} 

function listar_comerciales(){
	echo "<strong>NOTAS:</strong> Modo de Revolver las Listas<br>1.- Selecciona 1 tracks con fisher yates, lo coloca en la primera posicion y los demas se revuelven con sattolo.
		<br>2.- Selecciona 1 tracks con fisher yates, se queda en la misma posicion y los demas se revuelven con sattolo.
		<br>3.- Selecciona 1 tracks con fisher yates, lo coloca en la ultima posicion y los demas se revuelven con sattolo.
		<br>4.- Selecciona 1 algoritmo de revolver (fisher yates o sattolo) y revuelve la lista.<br>";
	echo "_______________________________________________________________________<br>";
	echo "<span><strong>LISTA DE COMERCIALES:</strong></span><br><br>";
	if (is_file("../json/comerciales.json")) {
		$datos_comerciales = new Comerciales();
		$array_comerciales = $datos_comerciales->Load()->Get();
		//$array_comercial_listar = array_values($array_comerciales);
		
		sort($array_comerciales);
		if(count($array_comerciales)==0){
			echo "No hay comerciales registrados...<br>";
			echo "_______________________________________________________________________<br>";			
		}
		for($i=0;$i<count($array_comerciales);$i++){
			echo (($i<11)?('0'.($i+1)):($i+1))."&nbsp;&nbsp;&nbsp;|<span><strong>ID:</strong> ".$array_comerciales[$i]['ID']."  <strong>Tipo:</strong> ".(($array_comerciales[$i]['tipo']==1)?'1-GENERAL':(($array_comerciales[$i]['tipo']==2)?'2-GENEROS':'3.-ENTRADAS'));
			echo " <strong>Descripcion:</strong> ".$array_comerciales[$i]['descripcion']." <strong>Tracks:</strong> ".$array_comerciales[$i]['Ntracks'];
			echo " <strong>Revolver:</strong> ".$array_comerciales[$i]['modo_revolver']."<a name='eliminar-comercial' href='../php/eliminar_comercial.php?ID=".$array_comerciales[$i]['ID']."&pagina=actual'> ELIMINAR </a><br>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Carpeta:</strong> ../".$array_comerciales[$i]['carpeta']."/ </span><br>";
			$array_lista=[];
			for($j=0;$j<count($array_comerciales[$i]['lista']);$j++){
				$array_lista[$j]=basename($array_comerciales[$i]['lista'][$j]);
			}
			$lista="";
			$lista=implode("<br>",$array_lista);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Lista: ".count($array_comerciales[$i]['lista'])." Audios</strong><div style='background-color: yellow; border: solid green; width: 100%; height:115px; overflow: auto;'> <span> $lista </span> </div>";
			echo "_______________________________________________________________________<br>";			
		}
	}
	else{
		echo "No hay comerciales registrados...<br>";
		echo "_______________________________________________________________________<br>";
	}
}
	
function listar_generos(){
	echo "<span><strong>LISTA DE GENEROS:</strong></span><br><br>";
	if (is_file("../json/generos.json")) {
		$datos_generos= new Generos();
		$array_generos = $datos_generos->Load()->Get();

		sort($array_generos);

		if(count($array_generos)==0){
			echo "No hay generos registrados...<br>";
			echo "_______________________________________________________________________<br>";			
		}

		for($i=0;$i<count($array_generos);$i++){
			echo (($i<11)?('0'.($i+1)):($i+1))."&nbsp;&nbsp;&nbsp;|<span><strong>ID:</strong> ".$array_generos[$i]['ID'];

			echo " <strong>Nombre:</strong> ".$array_generos[$i]['Name']." <strong>Tracks:</strong> ".$array_generos[$i]['Ntracks'];

			echo " <strong>Revolver:</strong> ".$array_generos[$i]['modo_revolver']." <strong>ID Comercial: </strong>".(($array_generos[$i]['ID_comerciales_generos']!='')?$array_generos[$i]['ID_comerciales_generos']:'No asignado')."<a name='eliminar-genero' href='../php/eliminar_genero.php?ID=".$array_generos[$i]['ID']."&pagina=actual'> ELIMINAR </a><br>";

			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Carpeta:</strong> ../".$array_generos[$i]['carpeta']."/ <br>";

			$ausente=implode(',&nbsp;',$array_generos[$i]['AUSENTE_PRESENTE']);
			$array_lista=[];

			for($j=0;$j<count($array_generos[$i]['lista']);$j++){
				$array_lista[$j]=basename($array_generos[$i]['lista'][$j]);
			}

			$lista="";
			$lista=implode('<br>',$array_lista);

			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Ausente Presente:</strong> [$ausente] </span><br> ";

			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<strong>Lista: ".count($array_generos[$i]['lista'])." Audios</strong><div style='position:relative;background-color: pink; border: solid blue; width: 100%; height:115px; overflow: auto;'> $lista </div></span> ";
            
			echo "_______________________________________________________________________<br>";			
		}
	}
	else{
		echo "No hay generos registrados...<br>";
		echo "_______________________________________________________________________<br>";	
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="iso-8859-1">
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
        .lista{
		width:auto;
	}
	.col-6 {
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
                width: 97%; }
        }

        /* // Extra large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) { 
            .container2 {
                display: flex;
            }
            .col-6 {
                width: 97%; }
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

 


    </style>

</head>

<body>
	
   <div align="center">	
		<h2 align> CONFIGURACIÓN ACTUAL </h3>
    </div>	
    <div class="container2">
		<div id="conf_actual" class="col-6 fz-20">
			<?php 
				listar_variables();
				listar_comerciales();
				listar_generos();
			?>
		 </div>
	
    </div>
	

</body>
</html>