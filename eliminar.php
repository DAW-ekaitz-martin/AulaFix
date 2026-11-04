<?php
session_start();
if(!$_SESSION["id_usuario"]) {
    header("Location:login.php");
}
require 'sql/conexion.php';

$mensaje = '';


    $id = (int)($_POST['id_incidencia'] ?? 0);

    if ($id > 0 && isset($_POST["eliminar"])) {

        $stmt = $cn->prepare(
            'DELETE FROM incidencias WHERE id_incidencia = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensaje = "ok";
        } else {
            $mensaje = "No existe ninguna incidencia con ese ID.";
        }

        $stmt->close();

    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AulaFix — Eliminar incidencia</title>
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg af-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span class="brand-icon"><i class="bi bi-tools"></i></span>
            AulaFix
        </a>
        <div class="ms-auto">
            <a href="index.php" class="nav-link d-inline-flex align-items-center gap-1" style="color:rgba(255,255,255,.82);">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</nav>

<!-- MAIN -->
<main class="af-main">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-6 col-xl-5">

    <!-- Page header -->
    <div class="af-page-header" style="background:linear-gradient(135deg,#991b1b 0%,#dc2626 100%);">
        <div class="af-page-icon" style="background:rgba(255,255,255,.18);">
            <i class="bi bi-trash"></i>
        </div>
        <div>
            <h1>Eliminar incidencia</h1>
            <p>Esta acción no se puede deshacer</p>
        </div>
    </div>

    <!-- FEEDBACK MESSAGES -->
    <?php if ($mensaje === "ok"): ?>
    <div class="af-alert af-alert-success mb-3">
        <i class="bi bi-check-circle-fill"></i>
        <span>Incidencia eliminada correctamente.</span>
    </div>
    <?php elseif ($mensaje): ?>
    <div class="af-alert af-alert-error mb-3">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?php echo htmlspecialchars($mensaje); ?></span>
    </div>
    <?php endif; ?>

    <!-- DELETE CARD -->
    <div class="af-card">
        <div class="af-card-header" style="background:#991b1b;">
            <i class="bi bi-exclamation-triangle"></i>Confirmación de eliminación de la incidencia #<?=$id?>
        </div>
        <div class="af-card-body">

            <div class="af-alert af-alert-warning mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Estas seguro de que deseas eliminar la incidencia permanentemente.</span>
            </div>

            <form method="post">
                <input type="hidden" name="id_incidencia" value="<?= $id ?>">
                <input type="text" name="eliminar" hidden>
                <button type="submit" class="btn btn-af-danger w-100 py-2">Eliminar incidencia</button>
            </form>

        </div>
    </div>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-af-outline">
            <i class="bi bi-arrow-left me-1"></i>Volver al listado
        </a>
    </div>

</div>
</div>
</div>
</main>

<footer class="af-footer">
    AulaFix &mdash; Gestor de incidencias de biblioteca
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
