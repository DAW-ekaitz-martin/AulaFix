<?php
session_start();
require "includes/header.php";
unset($_SESSION["id_usuario"]);
header("location: login.php"); //En el pdf de la estructura ponia que era para "Cerrar la sesión". Se puede cambiar
require "includes/footer.php";
?>