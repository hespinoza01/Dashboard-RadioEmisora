<?php

    $ruta= $_REQUEST['ruta'];
	$extension = $_REQUEST['extension'];
	$audB = file_get_contents($ruta);  // colocar aqui el archivo a reproducir	
	if($extension == "txt" || $extension == "js" || $extension == "json" ){
		$save = substr($audB,8,-1);
		$music=$save;
	}
	if($extension == "ini" || $extension == "rtf"){
		$save = gzuncompress($audB);
		$music='data:audio/mp3;base64,'.$save;
	}
	else if($extension == "log" || $extension == "pdf"){
		$save = gzinflate($audB);
		$music='data:audio/ogg;base64,'.base64_encode($save);
	}
	echo $music;
?>
