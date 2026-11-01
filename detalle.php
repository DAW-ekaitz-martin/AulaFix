<?php
session_start();
require "includes/header.php";
require 'sql/conexion.php';
require_once "funciones.php";
$stmt1 = $cn -> prepare("SELECT id_incidencia, titulo, descripcion, fecha_creacion, fecha_cierre, id_usuario_creador, id_responsable, id_categoria, estado, prioridad FROM incidencias WHERE id_incidencia = ?");
$stmt1 -> bind_param("i", $_POST["id_incidencia"]);
$stmt1 -> execute();
$res = $stmt1 -> get_result();
$inf = $res -> fetch_assoc();
$stmt1 -> close();

$stmt2 = $cn -> prepare("SELECT nombre FROM usuarios WHERE id_usuario = ?");
$stmt2 -> bind_param("i", $inf["id_usuario_creador"]);
$stmt2 -> execute();
$res = $stmt2 -> get_result();
$usuarioCreador = $res -> fetch_assoc();
$stmt2 -> close();

$responsable = obtenerUsuario($inf["id_responsable"], $cn);

$stmt4 = $cn -> prepare("SELECT nombre FROM categorias WHERE id_categoria = ?");
$stmt4 -> bind_param("i", $inf["id_categoria"]);
$stmt4 -> execute();
$res = $stmt4 -> get_result();
$categoria = $res -> fetch_assoc();
$stmt4 -> close();

$stmt5 = $cn -> prepare("SELECT fecha, id_usuario FROM historial_incidencias WHERE id_incidencia = ? ORDER BY fecha DESC");
$stmt5 -> bind_param("i", $_POST["id_incidencia"]);
$stmt5 -> execute();
$resHistorial = $stmt5 -> get_result();
$stmt5 -> close();

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AulaFix — Detalle incidencia</title>
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
    <div class="col-lg-9 col-xl-8">

        <!-- Page header -->
        <div class="af-page-header">
            <div class="af-page-icon"><i class="bi bi-file-text"></i></div>
            <div>
                <h1><?= htmlspecialchars($inf["titulo"], ENT_QUOTES, "UTF-8") ?></h1>
                <p>Incidencia #<?= htmlspecialchars($inf["id_incidencia"], ENT_QUOTES, "UTF-8") ?></p>
            </div>
        </div>

        <!-- DETAIL CARD -->
        <div class="af-card mb-4">
            <div class="af-card-header">
                <i class="bi bi-info-circle"></i> Información de la incidencia
            </div>

            <div class="af-detail-grid">
                <div class="af-detail-item full-width">
                    <div class="af-detail-label">Descripción</div>
                    <div class="af-detail-value"><?= htmlspecialchars($inf["descripcion"], ENT_QUOTES, "UTF-8") ?></div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Estado</div>
                    <div class="af-detail-value">
                        <span class="af-badge af-badge-<?= strtolower(str_replace(' ', '-', htmlspecialchars($inf["estado"], ENT_QUOTES, "UTF-8"))) ?>">
                            <?= htmlspecialchars($inf["estado"], ENT_QUOTES, "UTF-8") ?>
                        </span>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Prioridad</div>
                    <div class="af-detail-value">
                        <span class="af-badge af-badge-<?= strtolower(htmlspecialchars($inf["prioridad"], ENT_QUOTES, "UTF-8")) ?>">
                            <?= htmlspecialchars($inf["prioridad"], ENT_QUOTES, "UTF-8") ?>
                        </span>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Fecha de creación</div>
                    <div class="af-detail-value">
                        <i class="bi bi-calendar me-1 text-secondary"></i>
                        <?= htmlspecialchars($inf["fecha_creacion"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Fecha de cierre</div>
                    <div class="af-detail-value">
                        <?php if($inf["fecha_cierre"] != null): ?>
                            <i class="bi bi-calendar-check me-1 text-success"></i>
                            <?= htmlspecialchars($inf["fecha_cierre"], ENT_QUOTES, "UTF-8") ?>
                        <?php else: ?>
                            <span class="af-badge af-badge-abierta">Abierta</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Creador</div>
                    <div class="af-detail-value">
                        <i class="bi bi-person me-1 text-secondary"></i>
                        <?= htmlspecialchars($usuarioCreador["nombre"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Responsable</div>
                    <div class="af-detail-value">
                        <i class="bi bi-person-badge me-1 text-secondary"></i>
                        <?= htmlspecialchars($responsable["nombre"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                </div>
                <div class="af-detail-item">
                    <div class="af-detail-label">Categoría</div>
                    <div class="af-detail-value">
                        <i class="bi bi-tag me-1 text-secondary"></i>
                        <?= htmlspecialchars($categoria["nombre"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORY CARD -->
        <div class="af-card">
            <div class="af-card-header">
                <i class="bi bi-clock-history"></i> Historial de cambios
            </div>

            <?php if($resHistorial -> num_rows > 0){ ?>
            <ul class="af-history-list">
                <?php while($r = $resHistorial -> fetch_assoc()){ ?>
                <li class="af-history-item">
                    <i class="bi bi-arrow-repeat af-history-icon"></i>
                    <span>Cambio realizado por <?php obtenerUsuario($r["id_usuario"], $cn) ?> el <?= htmlspecialchars($r["fecha"], ENT_QUOTES, "UTF-8") ?>.</span>
                </li>
                <?php } ?>
            </ul>
            <?php } else { ?>
            <div class="af-history-empty">
                <i class="bi bi-clock" style="font-size:2rem;color:#c4cdd9;"></i>
                <p class="mt-2 mb-0">Aún no se han realizado cambios en esta incidencia.</p>
            </div>
            <?php } ?>
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
