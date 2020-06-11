<?php
 session_start();
 // Variables de sesión:
 $_SESSION['usuario'] =  $_POST['txtUsuario'];
 $_SESSION['clave'] = $_POST['txtClave'];
 header("Location: Principal.php");
?>
