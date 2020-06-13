<?php
function listar_generos(){
    
    if (is_file("../json/generos.json")) {
			$datos_generos= file_get_contents("../json/generos.json");
		$array_generos = json_decode($datos_generos, true);    
		echo "<span><strong>Lista de Géneros [ID-NOMBRE-CARPETA]:</strong></span><input type='hidden' id='n_generos' value='".count($array_generos)."'/><br><br>";
		if(count($array_generos)==0){
			echo "<span>No hay géneros registrados...</span>";
		}
		$i=0;
		$vueltas=0;
		sort($array_generos);
		foreach ($array_generos as $key => $genero) {
				if(count($genero['AUSENTE_PRESENTE'])>0){
					$vueltas=count($genero['AUSENTE_PRESENTE']);	
				}
				echo "<div style='position: absolute; margin-left:300px;' id=$genero[ID]></div><input type='hidden' id='entrada$i' name='entrada$i' value='$genero[ID]'/><span>[$genero[ID]-$genero[Name]-/$genero[carpeta]]:</span><br><br>";
				$i=$i+1;
		}
	
		echo " <script>
				document.getElementById('N_Vueltas').value=$vueltas;
				var n_generos=document.getElementById('n_generos').value;
				
				for(var i=0;i<n_generos;i++){
					var id=document.getElementById('entrada'+i).value;
					var padre = document.getElementById(id);
					document.getElementById(id).innerHTML =''; 
					for(var j=0;j<$vueltas;j++){
						var input = document.createElement('input');
						input.type= 'text';
						input.name = 'ausente'+i+'[]';
						input.id = 'ausente'+i+''+j;
						input.autocomplete='off';
                        input.required = true;
									
						input.style= 'text-align: center; width:20px; margin-left:10px; font-size:25px; color:red; font-weight: bold; border:1.5px solid blue;';
						padre.appendChild(input);	
											
					}
				}
			</script>
	";
		$i=0;
		//$vueltas=0;
		foreach ($array_generos as $key => $genero) {
				//$vueltas=count($genero['AUSENTE_PRESENTE']);
				for($j=0;$j<$vueltas;$j++){
					echo "<script>document.getElementById('ausente'+$i+''+$j).value=".((count($genero['AUSENTE_PRESENTE'])==0)?'0':$genero['AUSENTE_PRESENTE'][$j])."</script>";
					if(count($genero['AUSENTE_PRESENTE'])!=0 && $genero['AUSENTE_PRESENTE'][$j]=='1')
						echo "<script>document.getElementById('ausente'+$i+''+$j).style= 'text-align: center; width:20px; margin-left: 10px; font-size:25px; font-weight: bold; color:green; border:1.5px solid blue;';</script>";
					
				}	
				$i++;				
				//echo "<div style='position: absolute; margin-left:300px;' id=$genero[ID]></div><input type='hidden' id='entrada$i' name='entrada$i' value='$genero[ID]'/><span>[$genero[ID]-$genero[Name]-$genero[carpeta]]:</span><br>";
				//$i=$i+1;
		}
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
                width: 100%; }
        }

        /* // Extra large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) { 
            .container2 {
                display: flex;
            }
            .col-6 {
                width: 50%; }
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
		<h2 align> CONFIGURACIÓN DE AUSENTE PRESENTE </h3>
    </div>	
    <form id="form_ausente" action="../php/guardar_ausente.php" method="post">
	<div class="container2">
			<br>
			<div class="col-6 fz-20">
				<span>Cantidad de Vueltas: &nbsp; </span>
				<input required type="text" name="N_Vueltas" id="N_Vueltas"  autocomplete="off"/> <span>&nbsp; </span>
				
				<input required type="button" value="Mostrar" id="generar_matriz" Onclick="matriz(N_Vueltas.value);"></input>
				<input required type="submit" value="Guardar" id="a_p_guardar"></input>
				<br>
				<br>
				<?php echo listar_generos(); ?>
			</div>
	</div>
    </form>

    <script>
	function matriz(N){
		var n_generos=document.getElementById('n_generos').value;
		//alert(n_generos+","+N);
		for(var i=0;i<n_generos;i++){
			var id=document.getElementById("entrada"+i).value;
			var padre = document.getElementById(id);
			document.getElementById(id).innerHTML =""; 
			for(var j=0;j<N;j++){
				var input = document.createElement("input");
				input.type= "text";
				input.name = "ausente"+i+"[]";
				input.id = "ausente"+i+""+j;
				input.value=1;
				input.autocomplete="off";
                input.required = true;
				input.style= 'text-align: center; width:20px; margin-left: 10px; font-size:25px; font-weight: bold; color:green; border:1.5px solid blue;';
				//input.maxlength="1";
				//input.onkeypress='return event.charCode >= 48 && event.charCode <= 49';
				padre.appendChild(input);				
			}
		}
	}
	
	function check(e) {
		tecla = (document.all) ? e.keyCode : e.which;

		//Tecla de retroceso para borrar, siempre la permite
		if (tecla == 8) {
			return true;
		}

		// Patron de entrada, en este caso solo acepta numeros y letras
		//patron = /[0-1]/;
		tecla_final = String.fromCharCode(tecla);
		return tecla_final;
	}
	
    </script>

</body>
</html>