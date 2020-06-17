<?php

require_once 'data.php';

	if (is_file("../json/link_es.json")) {
		$datos_enlace= new Link_es();
		$array_enlace = $datos_enlace->Load()->Get();

		foreach ($array_enlace as $key => $enlace) {
			if ( $enlace['enlace']== $_REQUEST['enlace']) {

				$fecha_final=$enlace['fecha_final'];
	
				$segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($fecha_final);
				//echo $segundoTimes;
			
				if($segundoTimes>0){
					unlink($_REQUEST['hosting'].$_REQUEST['enlace']);
					unset($array_enlace[$key]);
					$array_enlace =array_values($array_enlace);

					$datos_enlace->Set($array_enlace);
					$datos_enlace->Save();
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
