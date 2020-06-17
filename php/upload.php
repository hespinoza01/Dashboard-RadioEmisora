<?php

require_once 'data.php';

if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
{
	$vpb_file_name = strip_tags($_FILES['upload_file']['name']); //File Name
	$vpb_file_id = strip_tags($_POST['upload_file_ids']); // File id is gotten from the file name
	$vpb_file_size = $_FILES['upload_file']['size']; // File Size
	

	
	$formato = $_POST['optype'];
	$nomcarp = $_POST['nucarp'];
	$listnamemusica = $_POST['listnamemusica'];
	$switchopctions_musica = $_POST['switchopctions_musica'];

	if ($switchopctions_musica==1) { // Nueva Carpeta
		$directorio =AUDIOS_RUTA.$nomcarp.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos
	} else { // Carpeta
		$directorio =AUDIOS_RUTA.$listnamemusica.'/'; // Declaramos un  variable con la ruta donde guardaremos los archivos
	}

	//Validamos si la ruta de destino existe, en caso de no existir la creamos
	if(!file_exists($directorio)){
		mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
	}

	$vpb_final_location = $directorio.$vpb_file_name;

	//Without Validation and does not save filenames in the database

	if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $vpb_final_location))
	{
		$porciones = explode(".", $vpb_file_name);
       		$type = pathinfo($vpb_final_location, PATHINFO_EXTENSION);
		
        	$data = file_get_contents($vpb_final_location);
		if(strcmp($formato,'0')!=0){
			if(strcmp($formato,'.txt')==0 ||strcmp($formato,'.js')==0 || strcmp($formato,'.json')==0){ 
	        		$base64 = 'base64="data:audio/ogg;base64,' . base64_encode($data).'"';
				unlink($directorio.$vpb_file_name);	
	    		}
			else{
				if(strcmp($formato,'.ini')==0 ||strcmp($formato,'.rtf')==0){
					$data=base64_encode($data);
					$data=gzcompress($data);
					$base64=$data;
					unlink($directorio.$vpb_file_name);	
				}
				else{
					if(strcmp($formato,'.log')==0 || strcmp($formato,'.pdf')==0){
						$data=gzdeflate($data,9);
						$base64=$data;
						unlink($directorio.$vpb_file_name);	
					}
					else{
						$base64=$data;
					}
				}
			}
	
	        	// Creamos un archivo .txt que contiene el audio con base64.
			file_put_contents($directorio.$porciones[0].$formato, $base64);

		}
	}

			
	//Display the file id
	echo $vpb_file_id;
	echo	$directorio;
}
else{
		//Display general system error
		echo 'general_system_error';
		//echo $_FILES['upload_file']['name'];
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