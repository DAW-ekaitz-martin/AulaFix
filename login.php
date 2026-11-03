<?php
session_start();
setcookie("tema","claro",time()+ 604800,"/");
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
            $loginError = true;
        }
    }else{
        $loginError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AulaFix — Iniciar sesión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <div class="af-auth-wrapper">
            <div class="af-auth-card">

                <div class="af-auth-header">
                    <div class="af-brand-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h1>AulaFix</h1>
                    <p>Gestor de incidencias de biblioteca</p>
                </div>

                <div class="af-auth-body">

                    <?php if (!empty($loginError)): ?>
                    <div class="af-alert af-alert-error mb-3">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Email o contraseña incorrectos. Inténtalo de nuevo.</span>
                    </div>
                    <?php endif; ?>

                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Email de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="tu@email.com" autocomplete="email">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="contrasenia" class="form-control" placeholder="••••••••" autocomplete="current-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-af-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                        </button>
                    </form>

                    <hr class="af-divider my-3">

                    <p class="text-center mb-0" style="font-size:0.875rem; color:var(--af-muted);">
                        ¿No tienes cuenta?
                        <a href="registrarse.php" class="fw-semibold text-decoration-none" style="color:var(--af-secondary);">Regístrate aquí</a>
                    </p>

                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php
require "includes/footer.php";
?>
