<?php
    require 'sql/conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AulaFix — Crear cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php
    if($_COOKIE["tema"] === "claro") {
?>
        <link rel="stylesheet" href="css/estilos.css">
<?php
    } else {
?>
        <link rel="stylesheet" href="css/estilos-oscuro.css">
<?php
    }
?>
</head>
<body>
    <div class="af-auth-wrapper">
        <div class="af-auth-card">

            <div class="af-auth-header">
                <div class="af-brand-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h1>Crear cuenta</h1>
                <p>Regístrate en AulaFix</p>
            </div>

            <div class="af-auth-body">

                <form action="registrarse.php" method="post">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="col-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="tu@email.com" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-af-primary w-100 py-2">
                        <i class="bi bi-person-check me-2"></i>Crear cuenta
                    </button>
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
                                <div class="af-alert af-alert-success mt-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>¡Usuario creado correctamente!</span>
                                </div>
                                <a href="login.php" class="btn btn-af-outline w-100 mt-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                                </a>
                <?php
                                } else {
                ?>
                                <div class="af-alert af-alert-error mt-3">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>Error al crear el usuario. Comprueba los campos.</span>
                                </div>
                <?php
                                }
                            } else {
                ?>
                                <div class="af-alert af-alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>El email ya está registrado.</span>
                                </div>
                                <a href="login.php" class="btn btn-af-outline w-100 mt-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                                </a>
                <?php
                            }
                        }
                    } catch (Exception $e) {
                        echo $e;
                    }
                ?>

                <hr class="af-divider my-3">

                <p class="text-center mb-0" style="font-size:0.875rem; color:var(--af-muted);">
                    ¿Ya tienes cuenta?
                    <a href="login.php" class="fw-semibold text-decoration-none" style="color:var(--af-secondary);">Inicia sesión</a>
                </p>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
