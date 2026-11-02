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

    $estadoSlug     = strtolower(str_replace(' ', '-', htmlspecialchars($inf["estado"],    ENT_QUOTES, "UTF-8")));
    $prioridadSlug  = strtolower(htmlspecialchars($inf["prioridad"], ENT_QUOTES, "UTF-8"));
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
    <style>
        /* ── Detail-page extras ── */
        .af-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 1.75rem;
        }
        .af-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--af-surface);
            border: 1px solid var(--af-border);
            border-radius: 20px;
            padding: 0.3rem 0.85rem;
            font-size: 0.8rem;
            color: var(--af-muted);
            font-weight: 500;
            box-shadow: 0 1px 4px rgba(29,53,87,.06);
        }
        .af-meta-chip i { color: var(--af-secondary); }

        /* Description block */
        .af-desc-block {
            background: #f8fafc;
            border-left: 4px solid var(--af-secondary);
            border-radius: 0 8px 8px 0;
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
            line-height: 1.65;
            color: var(--af-text);
            margin-bottom: 0;
        }

        /* Detail tiles */
        .af-tiles {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1px;
            background: #f0f3f8;
            border-top: 1px solid #f0f3f8;
        }
        .af-tile {
            background: var(--af-surface);
            padding: 1rem 1.25rem;
        }
        .af-tile-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--af-secondary);
            margin-bottom: 0.4rem;
        }
        .af-tile-value {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--af-text);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Timeline history */
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

        /* Action strip */
        .af-action-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            padding: 1.15rem 1.5rem;
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
<div class="col-lg-9 col-xl-8">

    <!-- ── Page header ── -->
    <div class="af-page-header" style="align-items:flex-start;">
        <div class="af-page-icon" style="margin-top:.15rem;"><i class="bi bi-file-earmark-text"></i></div>
        <div class="flex-grow-1 min-width-0">
            <h1 class="text-truncate"><?= htmlspecialchars($inf["titulo"], ENT_QUOTES, "UTF-8") ?></h1>
            <p>Incidencia #<?= htmlspecialchars($inf["id_incidencia"], ENT_QUOTES, "UTF-8") ?></p>
        </div>
        <div class="d-none d-sm-flex flex-column align-items-end gap-1 ms-3 flex-shrink-0">
            <span class="af-badge af-badge-<?= $estadoSlug ?>" style="font-size:.78rem;padding:.35em .9em;">
                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>
                <?= htmlspecialchars($inf["estado"], ENT_QUOTES, "UTF-8") ?>
            </span>
            <span class="af-badge af-badge-<?= $prioridadSlug ?>" style="font-size:.78rem;padding:.35em .9em;">
                <i class="bi bi-flag-fill me-1" style="font-size:.65rem;"></i>
                <?= htmlspecialchars($inf["prioridad"], ENT_QUOTES, "UTF-8") ?>
            </span>
        </div>
    </div>

    <!-- ── Meta chips ── -->
    <div class="af-detail-meta">
        <span class="af-meta-chip">
            <i class="bi bi-person"></i>
            Creador: <strong><?= htmlspecialchars($usuarioCreador["nombre"], ENT_QUOTES, "UTF-8") ?></strong>
        </span>
        <span class="af-meta-chip">
            <i class="bi bi-person-badge"></i>
            Responsable: <strong><?= htmlspecialchars($responsable["nombre"], ENT_QUOTES, "UTF-8") ?></strong>
        </span>
        <span class="af-meta-chip">
            <i class="bi bi-tag"></i>
            <?= htmlspecialchars($categoria["nombre"], ENT_QUOTES, "UTF-8") ?>
        </span>
        <span class="af-meta-chip">
            <i class="bi bi-calendar"></i>
            <?= htmlspecialchars($inf["fecha_creacion"], ENT_QUOTES, "UTF-8") ?>
        </span>
    </div>

    <!-- ── Detail card ── -->
    <div class="af-card mb-4">
        <div class="af-card-header">
            <i class="bi bi-info-circle"></i> Información de la incidencia
        </div>

        <!-- Description -->
        <div class="af-card-body pb-0">
            <p class="af-detail-label mb-1">Descripción</p>
            <div class="af-desc-block">
                <?= htmlspecialchars($inf["descripcion"], ENT_QUOTES, "UTF-8") ?>
            </div>
        </div>

        <!-- Tiles grid -->
        <div class="af-tiles mt-3">
            <div class="af-tile">
                <div class="af-tile-label">Estado</div>
                <div class="af-tile-value">
                    <span class="af-badge af-badge-<?= $estadoSlug ?>">
                        <?= htmlspecialchars($inf["estado"], ENT_QUOTES, "UTF-8") ?>
                    </span>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Prioridad</div>
                <div class="af-tile-value">
                    <span class="af-badge af-badge-<?= $prioridadSlug ?>">
                        <?= htmlspecialchars($inf["prioridad"], ENT_QUOTES, "UTF-8") ?>
                    </span>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Creado</div>
                <div class="af-tile-value">
                    <i class="bi bi-calendar3 text-secondary"></i>
                    <?= htmlspecialchars($inf["fecha_creacion"], ENT_QUOTES, "UTF-8") ?>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Cierre</div>
                <div class="af-tile-value">
                    <?php if ($inf["fecha_cierre"] != null): ?>
                        <i class="bi bi-calendar-check text-success"></i>
                        <?= htmlspecialchars($inf["fecha_cierre"], ENT_QUOTES, "UTF-8") ?>
                    <?php else: ?>
                        <span class="af-badge af-badge-abierta">Sin cerrar</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Creador</div>
                <div class="af-tile-value">
                    <i class="bi bi-person text-secondary"></i>
                    <?= htmlspecialchars($usuarioCreador["nombre"], ENT_QUOTES, "UTF-8") ?>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Responsable</div>
                <div class="af-tile-value">
                    <i class="bi bi-person-badge text-secondary"></i>
                    <?= htmlspecialchars($responsable["nombre"], ENT_QUOTES, "UTF-8") ?>
                </div>
            </div>
            <div class="af-tile">
                <div class="af-tile-label">Categoría</div>
                <div class="af-tile-value">
                    <i class="bi bi-tag text-secondary"></i>
                    <?= htmlspecialchars($categoria["nombre"], ENT_QUOTES, "UTF-8") ?>
                </div>
            </div>
        </div>

        <?php if ($_SESSION["id_usuario"] >= 1 && $_SESSION["id_usuario"] <= 3): ?>
        <div class="af-action-strip border-top" style="border-color:#f0f3f8 !important;">
            <form action="editar.php" method="post" class="d-inline">
                <input type="hidden" name="id_incidencia" value="<?= $inf['id_incidencia'] ?>">
                <button type="submit" class="btn btn-af-secondary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
            </form>
            <form action="estado.php" method="post" class="d-inline">
                <input type="hidden" name="id_incidencia" value="<?= $inf['id_incidencia'] ?>">
                <button type="submit" class="btn btn-af-primary btn-sm">
                    <i class="bi bi-arrow-repeat me-1"></i>Cambiar estado
                </button>
            </form>
            <form action="eliminar.php" method="post" class="d-inline">
                <input type="hidden" name="id_incidencia" value="<?= $inf['id_incidencia'] ?>">
                <button type="submit" class="btn btn-af-danger btn-sm">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- ── History card ── -->
    <div class="af-card">
        <div class="af-card-header">
            <i class="bi bi-clock-history"></i> Historial de cambios
        </div>

        <?php if ($resHistorial->num_rows > 0): ?>
        <div class="af-timeline">
            <?php while ($r = $resHistorial->fetch_assoc()): ?>
            <div class="af-tl-item">
                <div class="af-tl-dot">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="af-tl-content">
                    <div>Cambio realizado por <strong><?php obtenerUsuario($r["id_usuario"], $cn) ?></strong></div>
                    <div class="af-tl-date">
                        <i class="bi bi-clock me-1"></i><?= htmlspecialchars($r["fecha"], ENT_QUOTES, "UTF-8") ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="af-history-empty py-4">
            <i class="bi bi-clock-history" style="font-size:2.25rem;color:#c4cdd9;display:block;margin-bottom:.75rem;"></i>
            <p class="mb-0 fw-semibold" style="color:#6b7280;">Sin cambios registrados</p>
            <p class="mt-1 mb-0" style="font-size:.82rem;color:#9ca3af;">Aún no se han realizado modificaciones en esta incidencia.</p>
        </div>
        <?php endif; ?>
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
