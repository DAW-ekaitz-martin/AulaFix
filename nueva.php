<?php
    session_start();
    if(!$_SESSION["id_usuario"]) {
        header("Location:login.php");
    }
    require 'sql/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AulaFix — Nueva incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
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
<div class="col-lg-7 col-xl-6">

    <!-- Page header -->
    <div class="af-page-header">
        <div class="af-page-icon"><i class="bi bi-plus-circle"></i></div>
        <div>
            <h1>Crear incidencia</h1>
            <p>Rellena los datos para registrar una nueva incidencia</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="af-card">
        <div class="af-card-header">
            <i class="bi bi-pencil-square"></i> Datos de la incidencia
        </div>
        <div class="af-card-body">

            <form action="nueva.php" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Describe brevemente la incidencia" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Detalla la incidencia..."></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="id_responsable" class="form-label">Responsable <span class="text-muted fw-normal">(ID)</span></label>
                        <input type="number" name="id_responsable" id="id_responsable" class="form-control" min="1" max="3" placeholder="1–3">
                    </div>
                    <div class="col-sm-6">
                        <label for="id_categoria" class="form-label">Categoría <span class="text-muted fw-normal">(ID)</span></label>
                        <input type="number" name="id_categoria" id="id_categoria" class="form-control" min="1" max="25" placeholder="1–25">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="prioridad" class="form-label">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-select">
                        <option value="">Seleccionar…</option>
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-af-primary w-100 py-2">
                    <i class="bi bi-send me-2"></i>Crear incidencia
                </button>
            </form>

            <?php
                try {

                    if(isset($_POST["titulo"]) && isset($_POST["descripcion"]) &&
                    isset($_POST["id_responsable"]) && isset($_POST["id_categoria"]) &&
                    isset($_POST["prioridad"])) {
                        $stmt = $cn ->prepare("INSERT INTO incidencias (titulo,descripcion,id_usuario_creador,id_responsable,id_categoria,prioridad) VALUES (?,?,?,?,?,?);");
                        $stmt->bind_param('ssiiis', $_POST["titulo"], $_POST["descripcion"],$_SESSION["id_usuario"], $_POST["id_responsable"], $_POST["id_categoria"], $_POST["prioridad"]);
                        $resultado = $stmt->execute();
                        if($resultado && $stmt->affected_rows > 0 ) {
            ?>
                        <div class="af-alert af-alert-success mt-3">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>¡Incidencia creada con éxito!</span>
                        </div>
            <?php
                        }
            ?>
            <?php
                    }
                } catch (Exception $e) {
            ?>
                    <div class="af-alert af-alert-error mt-3">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Inserción cancelada. Revisa los campos e inténtalo de nuevo.</span>
                    </div>
                    <a href="index.php" class="btn btn-af-outline btn-sm mt-2">
                        <i class="bi bi-arrow-left me-1"></i>Volver al listado
                    </a>
            <?php
                    echo $e;
                }
            ?>

        </div>
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
