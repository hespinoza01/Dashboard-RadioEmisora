<?php
	
	unlink( $_REQUEST['ruta']);
	include 'update_audios.php';
	echo "<script>window.location='../php/conversor.php';</script>";
?>
