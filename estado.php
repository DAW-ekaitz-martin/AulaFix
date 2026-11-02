<?php
session_start();
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

// Actualizar estado y registrar historial
if (isset($_POST["actualizar"])) {

    $id = $_POST["id_incidencia"];
    $estado = $_POST["estado"];

    // Usuario que realiza el cambio
    $id_usuario = $_SESSION['id_usuario'];

    try {

        $cn->begin_transaction();

        // Actualizar estado
        $stmt = $cn->prepare("
            UPDATE incidencias
            SET estado = ?
            WHERE id_incidencia = ?
        ");

        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();

        // Registrar en historial
        $accion = "Cambio de estado a " . $estado;

        $stmt2 = $cn->prepare("
            INSERT INTO historial_incidencias
            (id_incidencia, accion, id_usuario)
            VALUES (?, ?, ?)
        ");

        $stmt2->bind_param(
            "isi",
            $id,
            $accion,
            $id_usuario
        );

        $stmt2->execute();

        $cn->commit();

        $mensaje = "Estado actualizado correctamente.";

    } catch (Exception $e) {

        $cn->rollback();
        $mensaje = "Error al actualizar la incidencia.";
    }

    // Recargar incidencia
    $stmt = $cn->prepare("SELECT * FROM incidencias WHERE id_incidencia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $incidencia = $resultado->fetch_assoc();
    }
}

// Cargar historial
$historial = null;

if ($incidencia) {

    $stmtHistorial = $cn->prepare("
        SELECT
            h.accion,
            h.fecha,
            u.nombre,
            u.apellidos
        FROM historial_incidencias h
        INNER JOIN usuarios u
            ON h.id_usuario = u.id_usuario
        WHERE h.id_incidencia = ?
        ORDER BY h.fecha DESC
    ");

    $stmtHistorial->bind_param(
        "i",
        $incidencia['id_incidencia']
    );

    $stmtHistorial->execute();
    $historial = $stmtHistorial->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AulaFix — Cambiar estado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        /* Timeline (same pattern as detalle.php) */
        .af-timeline {
            padding: 1.25rem 1.5rem;
            position: relative;
        }
        .af-timeline::before {
            content: "";
            position: absolute;
            left: 2.6rem;
            top: 1.5rem;
            bottom: 1.5rem;
            width: 2px;
            background: linear-gradient(to bottom, var(--af-secondary), transparent);
            opacity: .25;
        }
        .af-tl-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.9rem;
            position: relative;
        }
        .af-tl-item:last-child { margin-bottom: 0; }
        .af-tl-dot {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            background: #eef2f7;
            border: 2px solid var(--af-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--af-secondary);
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }
        .af-tl-content {
            flex: 1;
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            border: 1px solid #e9eef5;
            font-size: 0.85rem;
            color: #4b5563;
            line-height: 1.5;
        }
        .af-tl-date {
            font-size: 0.72rem;
            color: var(--af-muted);
            margin-top: 0.2rem;
        }

        /* Estado select options — coloured preview dot */
        .estado-preview {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }
    </style>
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
                <i class="bi bi-arrow-left"></i> Volver al panel
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
        <div class="af-page-icon"><i class="bi bi-arrow-repeat"></i></div>
        <div>
            <h1>Cambiar estado</h1>
            <p>Actualiza el estado de la incidencia y registra el cambio en el historial</p>
        </div>
    </div>

    <!-- Alert -->
    <?php if ($mensaje === "Estado actualizado correctamente."): ?>
    <div class="af-alert af-alert-success mb-3">
        <i class="bi bi-check-circle-fill"></i>
        <span><?php echo htmlspecialchars($mensaje); ?></span>
    </div>
    <?php elseif ($mensaje): ?>
    <div class="af-alert af-alert-error mb-3">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?php echo htmlspecialchars($mensaje); ?></span>
    </div>
    <?php endif; ?>

    <?php if ($incidencia): ?>

    <!-- Form card -->
    <div class="af-card mb-4">
        <div class="af-card-header secondary">
            <i class="bi bi-pencil-square"></i>
            Incidencia #<?php echo $incidencia['id_incidencia']; ?> &mdash; <?php echo htmlspecialchars($incidencia['titulo']); ?>
        </div>
        <div class="af-card-body">

            <!-- Current state -->
            <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e9eef5;">
                <span class="text-secondary" style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px;">Estado actual:</span>
                <?php
                    $estadoSlug = strtolower(str_replace(' ', '-', htmlspecialchars($incidencia['estado'], ENT_QUOTES, "UTF-8")));
                ?>
                <span class="af-badge af-badge-<?= $estadoSlug ?>">
                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>
                    <?php echo htmlspecialchars($incidencia['estado']); ?>
                </span>
            </div>

            <!-- Change form -->
            <form method="POST">
                <input type="hidden" name="id_incidencia" value="<?php echo $incidencia['id_incidencia']; ?>">

                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-arrow-repeat me-1 text-secondary"></i>Nuevo estado
                    </label>
                    <select name="estado" class="form-select">
                        <option value="Abierta"     <?php if ($incidencia['estado'] === 'Abierta')     echo 'selected'; ?>>Abierta</option>
                        <option value="En proceso"  <?php if ($incidencia['estado'] === 'En proceso')  echo 'selected'; ?>>En proceso</option>
                        <option value="Resuelta"    <?php if ($incidencia['estado'] === 'Resuelta')    echo 'selected'; ?>>Resuelta</option>
                        <option value="Cerrada"     <?php if ($incidencia['estado'] === 'Cerrada')     echo 'selected'; ?>>Cerrada</option>
                    </select>
                </div>

                <button type="submit" name="actualizar" class="btn btn-af-primary w-100 py-2">
                    <i class="bi bi-floppy me-2"></i>Guardar nuevo estado
                </button>
            </form>

        </div>
    </div>

    <!-- History card -->
    <div class="af-card">
        <div class="af-card-header">
            <i class="bi bi-clock-history"></i> Historial de cambios
        </div>

        <?php
            $hayHistorial = false;
            $filas = [];
            while ($fila = $historial->fetch_assoc()) {
                $filas[] = $fila;
                $hayHistorial = true;
            }
        ?>

        <?php if ($hayHistorial): ?>
        <div class="af-timeline">
            <?php foreach ($filas as $fila): ?>
            <div class="af-tl-item">
                <div class="af-tl-dot">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="af-tl-content">
                    <div><?php echo htmlspecialchars($fila['accion'], ENT_QUOTES, "UTF-8"); ?></div>
                    <div class="af-tl-date">
                        <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($fila['nombre'] . " " . $fila['apellidos'], ENT_QUOTES, "UTF-8"); ?>
                        &nbsp;&bull;&nbsp;
                        <i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($fila['fecha'], ENT_QUOTES, "UTF-8"); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="af-history-empty py-4">
            <i class="bi bi-clock-history" style="font-size:2.25rem;color:#c4cdd9;display:block;margin-bottom:.75rem;"></i>
            <p class="mb-0 fw-semibold" style="color:#6b7280;">Sin cambios registrados</p>
            <p class="mt-1 mb-0" style="font-size:.82rem;color:#9ca3af;">Aún no se han realizado modificaciones en esta incidencia.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php endif; ?>

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
