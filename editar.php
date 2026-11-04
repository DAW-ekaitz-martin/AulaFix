<?php
require 'sql/conexion.php';

$incidencia = null;
$mensaje = "";

// Buscar incidencia

    $id = $_POST["id_incidencia"];

    $stmt = $cn->prepare("SELECT * FROM incidencias WHERE id_incidencia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $incidencia = $resultado->fetch_assoc();
    } else {
        $mensaje = "No existe ninguna incidencia con ese ID.";
    }

// Actualizar incidencia completa
if (isset($_POST["actualizar"])) {

    $id = $_POST["id_incidencia"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_categoria = $_POST["id_categoria"];
    $estado = $_POST["estado"];
    $prioridad = $_POST["prioridad"];

    $stmt = $cn->prepare("
        UPDATE incidencias
        SET titulo = ?,
            descripcion = ?,
            id_categoria = ?,
            estado = ?,
            prioridad = ?
        WHERE id_incidencia = ?
    ");

    $stmt->bind_param(
        "ssissi",
        $titulo,
        $descripcion,
        $id_categoria,
        $estado,
        $prioridad,
        $id
    );

    if ($stmt->execute()) {

        $mensaje = "ok";

        // Volver a cargar los datos actualizados
        $stmt2 = $cn->prepare("SELECT * FROM incidencias WHERE id_incidencia = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();

        $resultado = $stmt2->get_result();

        if ($resultado->num_rows > 0) {
            $incidencia = $resultado->fetch_assoc();
        }

    } else {
        $mensaje = "Error al actualizar la incidencia.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AulaFix — Editar incidencia</title>
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
<div class="col-lg-8 col-xl-7">

    <!-- Page header -->
    <div class="af-page-header">
        <div class="af-page-icon"><i class="bi bi-pencil-square"></i></div>
        <div>
            <h1>Editar incidencia</h1>
            <p>Busca por ID y modifica los campos que necesites</p>
        </div>
    </div>

    <!-- SEARCH CARD -->
    <div class="af-card">
        <!--<div class="af-card-header">
            <i class="bi bi-search"></i> Buscar incidencia
        </div>
        <div class="af-card-body">
            <form method="POST" class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label class="form-label">ID de la incidencia</label>
                    <input type="number" name="id_incidencia" class="form-control" placeholder="Introduce el ID…" required>
                </div>
                <button type="submit" name="buscar" class="btn btn-af-secondary" style="height:38px;">
                    <i class="bi bi-search me-1"></i>Buscar
                </button>
            </form>-->

            <?php if ($mensaje && $mensaje !== "ok"): ?>
            <div class="af-alert af-alert-error mt-3">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo htmlspecialchars($mensaje); ?></span>
            </div>
            <?php endif; ?>

            <?php if ($mensaje === "ok"): ?>
            <div class="af-alert af-alert-success mt-3">
                <i class="bi bi-check-circle-fill"></i>
                <span>Incidencia actualizada correctamente.</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- EDIT FORM (shown when incidencia is found) -->
    <?php if ($incidencia) { ?>

    <div class="af-card">
        <div class="af-card-header secondary">
            <i class="bi bi-pencil"></i>
            Editando incidencia #<?php echo $incidencia['id_incidencia']; ?>
        </div>
        <div class="af-card-body">

            <form method="POST">

                <input
                    type="hidden"
                    name="id_incidencia"
                    value="<?php echo $incidencia['id_incidencia']; ?>">

                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input
                        type="text"
                        name="titulo"
                        class="form-control"
                        value="<?php echo htmlspecialchars($incidencia['titulo']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="4"
                        required><?php echo htmlspecialchars($incidencia['descripcion']); ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <label class="form-label">ID Categoría</label>
                        <input
                            type="number"
                            name="id_categoria"
                            class="form-control"
                            value="<?php echo $incidencia['id_categoria']; ?>"
                            min="1" max="25"
                            required>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Estado</label>
                        <input
                            type="text"
                            name="estado"
                            class="form-control"
                            value="<?php echo $incidencia['estado']; ?>"
                            disabled>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="Baja"
                                <?php if($incidencia['prioridad']=="Baja") echo "selected"; ?>>
                                Baja
                            </option>

                            <option value="Media"
                                <?php if($incidencia['prioridad']=="Media") echo "selected"; ?>>
                                Media
                            </option>

                            <option value="Alta"
                                <?php if($incidencia['prioridad']=="Alta") echo "selected"; ?>>
                                Alta
                            </option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="actualizar" class="btn btn-af-primary w-100 py-2">
                    <i class="bi bi-floppy me-2"></i>Guardar cambios
                </button>

            </form>
        </div>
    </div>

    <?php } ?>

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
