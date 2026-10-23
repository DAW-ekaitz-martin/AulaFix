<?php
    require 'sql/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear incidencia</title>
</head>
<body>
    <h1>Crear incidencia</h1>
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo">

    <label for="descripcion">Descripción:</label>
    <input type="text" name="descripcion" id="descripcion">

    <label for="id_responsable">Responsable:</label>
    <input type="text" name="id_responsable" id="id_responsable">

    <label for="id_categoria">Categoría:</label>
    <input type="text" name="id_categoria" id="id_categoria">

    <label for="id_estado">Estado:</label>
    <input type="text" name="id_estado" id="id_estado">

    <button type="submit">Enviar</button>
</body>
</html>
