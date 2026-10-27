<?php
require 'sql/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id_incidencia'] ?? 0);

    if ($id > 0) {

        $stmt = $cn->prepare(
            'DELETE FROM incidencias WHERE id_incidencia = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensaje = "Incidencia eliminada correctamente.";
        } else {
            $mensaje = "No existe ninguna incidencia con ese ID.";
        }

        $stmt->close();

    } else {
        $mensaje = "Introduce un ID válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar incidencia</title>
</head>
<body>

<h2>Eliminar incidencia</h2>

<form method="post">

    <label>ID de la incidencia:</label><br>
    <input type="number" name="id_incidencia" required>

    <br><br>

    <input type="submit" value="Eliminar">
    <br><br>
    <a href="index.php">Volver</a>
</form>

<p><?php echo $mensaje; ?></p>

</body>
</html>