<?php
session_start();
require "includes/header.php";
require 'sql/conexion.php';
$email = htmlspecialchars($_POST["email"] ?? "");
$contrasenia = htmlspecialchars($_POST["contrasenia"] ?? "");
if(!empty($_SESSION["id_usuario"])){ 
    header("location: index.php");
}else if(!empty($email) && !empty($contrasenia)){
    $stmt = $cn -> prepare("SELECT id_usuario, email, password FROM usuarios WHERE email LIKE ?");
    $stmt -> bind_param("s", $email);
    $stmt -> execute();
    $res = $stmt -> get_result();
    if($res -> num_rows > 0){
        $inf = $res -> fetch_assoc();
        if($inf["email"] == $email && $inf["password"] == $contrasenia){
            header("location: index.php");
            $_SESSION["id_usuario"] = $inf["id_usuario"];
        }else{
            ?>
            <h2>Inicio de sesion invalido</h2>
            <p>Revise los campos y pruebe otra vez</p>
            <?php
        }
    }else{
        ?>
        <h2>Inicio de sesion invalido</h2>
        <p>Revise los campos y pruebe otra vez</p>
        <?php
    }
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