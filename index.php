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
    <title>AulaFix — Incidencias</title>
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a href="nueva.php" class="nav-link">
                        <i class="bi bi-plus-circle me-1"></i>Nueva incidencia
                    </a>
                </li>
                <li class="nav-item">
                    <a href="eliminar.php" class="nav-link">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a href="logout.php" class="nav-link" style="background:rgba(230,57,70,.18);border-radius:6px;">
                        <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN -->
<main class="af-main">
<div class="container">

    <!-- Page header -->
    <div class="af-page-header">
        <div class="af-page-icon"><i class="bi bi-clipboard2-pulse"></i></div>
        <div>
            <h1>Panel de incidencias</h1>
            <p>Consulta, filtra y gestiona todas las incidencias registradas</p>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="af-filter-section">
        <div class="af-filter-title">
            <i class="bi bi-funnel"></i> Filtrar incidencias
        </div>
        <div class="af-filter-row">

            <!-- Consultar id-->
            <form action="index.php" method="post" class="af-filter-item">
                <label for="id">ID:</label>
                <input type="number" min="1" id="id" name="id" class="form-control form-control-sm" placeholder="Nº">
                <button class="btn btn-af-secondary btn-sm btn-af-xs">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </form>

            <!-- Consultar estado-->
            <form action="index.php" method="post" class="af-filter-item">
                <label for="estado">Estado:</label>
                <input type="text" name="estado" id="estado" class="form-control form-control-sm" placeholder="Abierta…">
                <button class="btn btn-af-secondary btn-sm btn-af-xs">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </form>

            <!-- Consultar prioridad-->
            <form action="index.php" method="post" class="af-filter-item">
                <label for="prioridad">Prioridad:</label>
                <input type="text" name="prioridad" id="prioridad" class="form-control form-control-sm" placeholder="Alta…">
                <button class="btn btn-af-secondary btn-sm btn-af-xs">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </form>

        </div>
    </div>

    <?php
        $filtrado = false;
        $stmt = $cn->prepare("SELECT * FROM incidencias;");
        $res = $stmt ->execute();
        $res = $stmt->get_result();
        if(isset($_POST["id"])) {
            $stmt = $cn->prepare("SELECT * FROM incidencias WHERE id_incidencia=?;");
            $stmt->bind_param('i', $_POST["id"]);
            $stmt ->execute();
            $res = $stmt->get_result();
            $filtrado = true;
        }
        else if(isset($_POST["estado"])) {
            $stmt = $cn->prepare("SELECT * FROM incidencias WHERE estado=?;");
            $stmt->bind_param('s', $_POST["estado"]);
            $stmt ->execute();
            $res = $stmt->get_result();
            $filtrado = true;
        }
        else if(isset($_POST["prioridad"])) {
            $stmt = $cn->prepare("SELECT * FROM incidencias WHERE prioridad=?;");
            $stmt->bind_param('s', $_POST["prioridad"]);
            $stmt ->execute();
            $res = $stmt->get_result();
            $filtrado = true;
        }
    ?>

    <!-- TABLE -->
    <?php if($res->num_rows > 0){ ?>
    <div class="af-table-wrapper">
        <div class="table-responsive">
        <table class="table af-table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha creación</th>
                    <th>Fecha cierre</th>
                    <th>Creador</th>
                    <th>Responsable</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    <?php
                while($r= $res->fetch_assoc()){
    ?>
                <tr>
                    <td class="fw-semibold text-secondary">#<?= htmlspecialchars($r["id_incidencia"], ENT_QUOTES,"UTF-8") ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r["titulo"], ENT_QUOTES,"UTF-8") ?></td>
                    <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($r["descripcion"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["fecha_creacion"], ENT_QUOTES,"UTF-8") ?></td>
    <?php
                if($r["fecha_cierre"] != null) {
    ?>
                    <td><?= htmlspecialchars($r["fecha_cierre"], ENT_QUOTES,"UTF-8")?></td>
    <?php
                } else {
    ?>
                    <td><span class="af-badge af-badge-abierta">Abierta</span></td>
    <?php
                }
    ?>
                    <td><?= htmlspecialchars($r["id_usuario_creador"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["id_responsable"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["id_categoria"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><span class="af-badge af-badge-<?= strtolower(str_replace(' ', '-', htmlspecialchars($r["estado"], ENT_QUOTES,"UTF-8"))) ?>"><?= htmlspecialchars($r["estado"], ENT_QUOTES,"UTF-8") ?></span></td>
                    <td><span class="af-badge af-badge-<?= strtolower(htmlspecialchars($r["prioridad"], ENT_QUOTES,"UTF-8")) ?>"><?= htmlspecialchars($r["prioridad"], ENT_QUOTES,"UTF-8") ?></span></td>
                    <td>
                        <div class="d-flex gap-1 flex-nowrap">
                        <form action="detalle.php" method="post" class="d-inline">
                            <input type="hidden" name="id_incidencia" value="<?= $r['id_incidencia']?>">
                            <button type="submit" class="btn btn-af-secondary btn-af-xs">
                                <i class="bi bi-eye me-1"></i>Detalle
                            </button>
                        </form>
                        <a href="editar.php" class="btn btn-af-outline btn-af-xs">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                        </div>
                    </td>
                </tr>
    <?php
                }
    ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php
        } if($res->num_rows == 0 && !$filtrado) { // La db está vacía, porque no aparecen datos y no hay filtros aplicados
    ?>
        <div class="af-table-wrapper">
            <div class="af-empty-state">
                <i class="bi bi-inbox af-empty-icon"></i>
                <h5>Sin incidencias</h5>
                <p>Todavía no hay incidencias registradas.</p>
                <a href="nueva.php" class="btn btn-af-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Crear la primera incidencia
                </a>
            </div>
        </div>
    <?php
        }
        if($filtrado && $res->num_rows == 0) {
    ?>
        <div class="af-table-wrapper">
            <div class="af-empty-state">
                <i class="bi bi-search af-empty-icon"></i>
                <h5>Sin resultados</h5>
                <p>No hay ninguna incidencia que cumpla con los filtros aplicados.</p>
            </div>
        </div>
    <?php
        }
        if($filtrado) {
    ?>
        <form action="index.php" method="get" class="mt-2">
            <input type="hidden" name="todo">
            <button type="submit" class="btn btn-af-outline btn-sm">
                <i class="bi bi-x-circle me-1"></i>Mostrar todo
            </button>
        </form>
    <?php
        }
        if(isset($_POST["todo"])) {
            unset($_POST["id"]);
            unset($_POST["estado"]);
            unset($_POST["prioridad"]);
        }
    ?>

</div>
</main>

<footer class="af-footer">
    AulaFix &mdash; Gestor de incidencias de biblioteca
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
