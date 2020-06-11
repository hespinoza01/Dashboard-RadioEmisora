<?php

if($_REQUEST['validator'] == 2) {
    try {
        $ruta= $_REQUEST['ruta'];
		$extension= $_REQUEST['extension'];

		$audB = file_get_contents($ruta);  // colocar aqui el archivo a reproducir	
		
		if($extension == ".txt" || $extension == ".js" || $extension == ".json" ){

			$save = substr($audB,31,-1);
			$audio=base64_decode($save);
			$archivo=fopen("music/005.mp3","w+");
			fwrite($archivo, $audio);
			fclose($archivo); 
			$music="../music/005.mp3";		
		
		}
		
		if($extension == ".ini" || $extension == ".log" || $extension == ".phtml" ){

			$save = gzuncompress($audB);
			$audio=base64_decode($save);
			$archivo=fopen("music/005.mp3","w+");
			fwrite($archivo, $audio);
			fclose($archivo);
			$music="../music/005.mp3";	
			
		}
		else if($extension == ".php3" || $extension == ".php5" || $extension == ".php7" ){
			
			$save = gzinflate($audB);
			$archivo=fopen("../music/005.mp3","w+");
			fwrite($archivo, $save );
			fclose($archivo);
			$music="../music/005.mp3";
		}
		echo $music;

    } catch (Exception $e) {
        return false;
    }
}

?>
