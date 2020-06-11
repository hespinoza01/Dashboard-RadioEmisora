<?php
  session_start();
  unset($_SESSION["usuario"]); 
  unset($_SESSION["clave"]);
  session_destroy();
  header("Location: ../configuracion.html");
  exit;
?>
