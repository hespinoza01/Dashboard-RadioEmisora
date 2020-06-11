<?php
	if (is_file("../json/link_es.json")) {
		$datos_enlace= file_get_contents("../json/link_es.json");
		$array_enlace = json_decode($datos_enlace, true);
		foreach ($array_enlace as $key => $enlace) {
			if ( $enlace['enlace']== $_REQUEST['enlace']) {

				$fecha_final=$enlace['fecha_final'];
	
				$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($fecha_final);
				//echo $segundoTimes;
			
				if($segundoTimes>0){
					unlink($_REQUEST['hosting'].$_REQUEST['enlace']);
					unset($array_enlace[$key]);
					$array_enlace =array_values($array_enlace);
					$fh = fopen("../json/link_es.json", 'w');
					fwrite($fh, json_encode($array_enlace,JSON_UNESCAPED_UNICODE));
					$code=fclose($fh); 
					echo 'true';
					die();
				}
				else{
					echo 'false';
					die();
				}
			}
		}
		//echo $_REQUEST['enlace'];
	}
	
?>
