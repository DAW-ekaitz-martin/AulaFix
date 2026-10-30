<?php
    require 'sql/conexion.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
</head>
<body>
    <form action="registrarse.php" method="post">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre">

        <label for="apellidos">Apellidos</label>
        <input type="text" name="apellidos">

        <label for="email">Email</label>
        <input type="email" name="email">

        <label for="password">Contraseña</label>
        <input type="password" name="password">

        <button type="submit">Enviar</button>
    </form>
    <?php
        try {
            if(isset($_POST["nombre"]) && isset($_POST["apellidos"]) && isset($_POST["email"])
                && isset($_POST["password"])) {
                $stmt = $cn->prepare("SELECT * FROM usuarios WHERE email=?;");
                $stmt->bind_param('s',$_POST["email"]);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows == 0) {
                    $stmt2 = $cn->prepare("INSERT INTO usuarios (nombre,apellidos,email,password) VALUES (?,?,?,?);");
                    $stmt2->bind_param('ssss',$_POST["nombre"],$_POST["apellidos"],$_POST["email"],$_POST["password"]);
                    $r = $stmt2->execute();
                    if($r) {
    ?>
                        <p>Usuario añadido correctamente</p>
                        <a href="login.php">Iniciar sesión</a>
    <?php
                    } else {
        ?>
                        <p>Error al añadir el usuario</p>
                        <p>Compruebe los campos y vuelva a intentarlo</p>
    <?php
                    }
                } else {
        ?>
                    <p>El usuario ya existe</p>
                    <a href="login.php">Iniciar sesión</a>
    <?php
                }
            }
        } catch (Exception $e) {
            echo $e;
        }
    ?>
</body>
</html>