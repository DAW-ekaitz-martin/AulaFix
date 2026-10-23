<?php
session_start();
require "includes/header.php";
$usuario = htmlspecialchars($_POST["usuario"] ?? "");
$contrasenia = htmlspecialchars($_POST["contrasenia"] ?? "");
if(($usuario == "admin" && $contrasenia == "hola123") || !empty($_SESSION["usuario"])){
    $_SESSION["usuario"] = $usuario;
    header("location: index.php"); // Envia al usuario a la pantalla principal
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body> <!-- Guardar el inicio de sesion en una sesion -->
        <form action="" method="post">
            <label>Nombre de usuario: <input type="text" name="usuario"><label><br>
            <label>Contraseña: <input type="password" name="contrasenia"><label><br><!-- Escribi contraseña sin la ñ por si luego no lo reconoce -->
            <button type="submit">Iniciar sesion</button>
        </form>
    </body>
</html>
<?php
require "includes/footer.php";
?>