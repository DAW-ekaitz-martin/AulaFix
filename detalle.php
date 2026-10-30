<?php
session_start();
require "includes/header.php";
require 'sql/conexion.php';
require_once "funciones.php";
$stmt1 = $cn -> prepare("SELECT id_incidencia, titulo, descripcion, fecha_creacion, fecha_cierre, id_usuario_creador, id_responsable, id_categoria, estado, prioridad FROM incidencias WHERE id_incidencia = ?");
$stmt1 -> bind_param("i", $_GET["id_incidencia"]);
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
$res = $stmt5 -> get_result();
$stmt5 -> close();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1><?= $inf["titulo"]?></h1>
        <hr>
        <p>Descripcion: <?= $inf["descripcion"]?></p>
        <p>Fecha creacion: <?= $inf["fecha_creacion"]?></p>
        <p>Fecha cierre: <?= $inf["fecha_cierre"] ?? "abierta"?></p>
        <p>Creador de la incidencia: <?= $usuarioCreador["nombre"]?></p>
        <p>Responsable: <?= $responsable["nombre"]?></p>
        <p>Categoria: <?= $categoria["nombre"]?></p>
        <p>Estado: <?= $inf["estado"]?></p>
        <p>Prioridad: <?= $inf["prioridad"]?></p>
    <hr>
    <h2>Historial de la incidencia</h2>
    <?php if($res -> num_rows > 0){?>
    <ul>
        <?php while($r = $res -> fetch_assoc()){?>
        <li>Cambio realizado por <?php obtenerUsuario($r["id_usuario"], $cn)?> en <?= $r["fecha"]?>.</li>
        <?php }?>
    </ul>
    <?php }else{ ?>
        <p>Aun no se han realizado cambios.</p>
    <?php } ?>
    </body>
</html>