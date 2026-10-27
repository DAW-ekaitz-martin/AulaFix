<?php
session_start();
require "includes/header.php";
require 'sql/conexion.php';
$email = htmlspecialchars($_POST["email"] ?? "");
$contrasenia = htmlspecialchars($_POST["contrasenia"] ?? "");
if(!empty($email) && !empty($contrasenia)){
    $stmt = $cn -> prepare("SELECT id_usuario, email, password FROM usuarios WHERE email LIKE ?");
    $stmt -> bind_param("s", $email);
    $stmt -> execute();
    $res = $stmt -> get_result();
    if($res -> num_rows > 0){
        $inf = $res -> fetch_assoc();
        if($inf["email"] == $email && $inf["password"] == $contrasenia){
            header("location: index.php"); // Envia al usuario a la pantalla principal
            $_SESSION["id_usuario"] = $inf["id_usuario"];
        }
    }
}
if(!empty($_SESSION["id"])){ // Pongo la verificacion de id abajo para no repetir codigo (despues de añadir el id en sesion que la compruebe para iniciar sesion)
    header("location: index.php"); // Envia al usuario a la pantalla principal
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body> <!-- Guardar el inicio de sesion en una sesion -->
        <form action="login.php" method="post">
            <label>Email de usuario: <input type="text" name="email"><label><br>
            <label>Contraseña: <input type="password" name="contrasenia"><label><br><!-- Escribi contraseña sin la ñ por si luego no lo reconoce -->
            <button type="submit">Iniciar sesion</button>
        </form>
    </body>
</html>
<?php
require "includes/footer.php";
?>