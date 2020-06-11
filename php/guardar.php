<?php
	
	$formato = $_POST['optype'];
	$nomcarp = $_POST['nucarp'];
	$listnamemusica = $_POST['listnamemusica'];
	// Manejador Input - Select
	$namecarpaudio = $_POST['namecarpaudio']; // Obtenemos el valor del input
	$nameselectaudio = $_POST['listnameaudios'];

	$archivo = $_FILES['archivo'];
	$filename = strtolower($archivo['name']); //Obtenemos el nombre original del archivo
	$source = $archivo['tmp_name']; //Obtenemos un nombre temporal del archivo

	$switchopctions_audios = $_POST['switchopctions_audios'];
	$switchopctions_musica = $_POST['switchopctions_musica'];

	if ($switchopctions_audios==1) { // Nueva Carpeta
		$directorio_base64 = '../audios/'.$namecarpaudio.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos de BASE64
	} else { // Carpeta
		$directorio_base64 = '../audios/'.$nameselectaudio.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos de BASE64
	}

	if ($switchopctions_musica==1) { // Nueva Carpeta
		$directorio = '../audios/'.$nomcarp.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos
	} else { // Carpeta
		$directorio = '../audios/'.$listnamemusica.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos
	}



	

	//Validamos si la ruta de destino existe, en caso de no existir la creamos
	if(!file_exists($directorio)){
		mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
	}

	//Validamos si el directorio de base64, existe, sino es asi la creamos
	if(!file_exists($directorio_base64)){
		mkdir($directorio_base64, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
	}

	$dir=opendir($directorio); //Abrimos el directorio de destino
	$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo

	// move_uploaded_file($source, $target_path);

	//Movemos y validamos que el archivo se haya cargado correctamente
	//El primer campo es el origen y el segundo el destino
	if(move_uploaded_file($source, $target_path)) {
        	// Obtenemo la musica para enmascararla
	        $porciones = explode(".", $filename);
        	$type = pathinfo($target_path, PATHINFO_EXTENSION);
        	$data = file_get_contents($target_path);
		if(strcmp($formato,'0')!=0){
			if(strcmp($formato,'.txt')==0 ||strcmp($formato,'.js')==0 || strcmp($formato,'.json')==0){ 
        			$base64 = 'base64="data:audio/' . $type . ';base64,' . base64_encode($data).'"';
			}
			else{ 
				if(strcmp($formato,'.ini')==0 ||strcmp($formato,'.log')==0 || strcmp($formato,'.phtml')==0){
					$data=base64_encode($data);
					$data=gzcompress($data);
					$base64=$data;
				}
				else{
					if(strcmp($formato,'.php3')==0 ||strcmp($formato,'.php5')==0 || strcmp($formato,'.php7')==0){
						$data=gzdeflate($data,9);
						$base64=$data;
					}
					else{
						$base64=$data;
					}
				}
			}
        		// Creamos un archivo .txt que contiene el audio con base64.
	        	file_put_contents($directorio_base64.$porciones[0].$formato, $base64);
			echo "El archivo $filename se ha almacenado en forma exitosa.<br>";
		}
	} else {	
		echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
	}
	closedir($dir); //Cerramos el directorio de destino

?>