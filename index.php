<?php
    session_start();
    if(!$_SESSION["id_usuario"]) {
        header("Location:login.php");
    }
    require 'sql/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Filtrar por</h2>
<!-- Consultar id-->
    <form action="index.php" method="post">
        <label for="id">ID:</label><br>
        <input type="number" min="1" id="id" name="id">
        <button>Filtrar</button>
    </form>
<!-- Consultar estado-->
    <form action="index.php" method="post">
        <label for="estado">Estado:</label><br>
        <input type="text" name="estado" id="estado">
        <button>Filtrar</button>
    </form>
<!-- Consultar prioridad-->
    <form action="index.php" method="post">
        <label for="prioridad">Prioridad</label><br>
        <input type="text" name="prioridad" id="prioridad">
        <button>Filtrar</button>
    </form><br>
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
    <?php   if($res->num_rows > 0){?>
                <table border="1" cellpadding="6">
                    <tr><th>ID</th> <th>Titulo</th> <th>Descripción</th> <th>Fecha Creación</th> <th>Fecha de cierre</th> <th>Id creador</th> <th>Id responsable</th> <th>Id categoría</th> <th>Estado</th> <th>Prioridad</th> <th>Acciones</th></tr>
    <?php 
                while($r= $res->fetch_assoc()){
    ?>
                <tr>
                    <td><?= htmlspecialchars($r["id_incidencia"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["titulo"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["descripcion"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["fecha_creacion"], ENT_QUOTES,"UTF-8") ?></td>
    <?php
                if($r["fecha_cierre"] != null) {
    ?>
                    <td><?= htmlspecialchars($r["fecha_cierre"], ENT_QUOTES,"UTF-8")?></td>
    <?php
                } else {
    ?>
                    <td><p>Abierta</p></td>
    <?php
                }
    ?>
                    <td><?= htmlspecialchars($r["id_usuario_creador"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["id_responsable"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["id_categoria"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["estado"], ENT_QUOTES,"UTF-8") ?></td>
                    <td><?= htmlspecialchars($r["prioridad"], ENT_QUOTES,"UTF-8") ?></td>
                    <td>
                        <a href="editar.php">Editar</a>
                    </td>
                </tr>
    <?php
                }
    
            } if($res->num_rows == 0 && !$filtrado) { // La db está vacía, porque no aparecen datos y no hay filtros aplicados
    ?>
                <p>Todavía no hay incidencias.</p>
                <p>Añade una para verla</p>
    <?php
            }
            if($filtrado && $res->num_rows == 0) {
    ?>
                <p>No hay ninguna incidencia que cumpla con los filtros</p>
    <?php
            }
            if($filtrado) {
    ?>
                <form action="index.php" method="get">
                    <input type="hidden" name="todo">
                    <button type="submit">Mostrar todo</button><br>
                </form>
    <?php
            }
            if(isset($_POST["todo"])) {
                unset($_POST["id"]);
                unset($_POST["estado"]);
                unset($_POST["prioridad"]);
            }
    
    ?>
                <a href="nueva.php">Crear una nueva incidencia</a> <br>
                <a href="eliminar.php">Eliminar</a> <br>
                <a href="logout.php">Cerrar Sesión</a>
</body>
</html>