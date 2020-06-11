<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
{
	$vpb_file_name = strip_tags($_FILES['archivo']['name']); //File Name
	//$vpb_file_id = strip_tags($_POST['upload_file_ids']); // File id is gotten from the file name
	$vpb_file_size = $_FILES['archivo']['size']; // File Size
	
	$directorio ='../imagenes/'; // Declaramos un  variable con la ruta donde guardaremos los archivos


	//Validamos si la ruta de destino existe, en caso de no existir la creamos
	if(!file_exists($directorio)){
		mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
	}

	$vpb_final_location = $directorio.$vpb_file_name;

	//Without Validation and does not save filenames in the database

	if(move_uploaded_file($_FILES['archivo']['tmp_name'], $vpb_final_location))
	{
		$porciones = explode(".", $vpb_file_name);
       	$type = pathinfo($vpb_final_location, PATHINFO_EXTENSION);
		$data = file_get_contents($vpb_final_location);

    	// Creamos un archivo .txt que contiene el audio con base64.
		file_put_contents($vpb_final_location, $data);
	
	}
			
	echo "<img style='margin-left:45%; margin-top:15%;' src='../imagenes/subiendo.jpeg'><br><h1 style='margin-left:45%'>Subiendo Imagen...</h1>";
	echo "<script>setTimeout(function(){window.location='../php/configuracion_general.php?ruta=$vpb_final_location'}, 2000);</script>";

}
else{
		//Display general system error
		echo 'general_system_error';
		//echo $_FILES['upload_file']['name'];
}
	
	


?>