<?php
session_start();
require 'sql/conexion.php';

$incidencia = null;
$mensaje = "";

// Buscar incidencia
if (isset($_POST["buscar"])) {

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
    <title>Cambiar estado incidencia</title>
</head>
<body>

<h1>Cambiar estado de incidencia</h1>

<form method="POST">

    <label>ID de incidencia:</label>
    <input type="number" name="id_incidencia" required>

    <button type="submit" name="buscar">
        Buscar
    </button>

</form>

<p><?php echo $mensaje; ?></p>

<a href="index.php">Volver</a>

<?php if ($incidencia) { ?>

<hr>

<h2>Incidencia #<?php echo $incidencia['id_incidencia']; ?></h2>

<p><strong>Título:</strong>
<?php echo htmlspecialchars($incidencia['titulo']); ?>
</p>

<p><strong>Estado actual:</strong>
<?php echo $incidencia['estado']; ?>
</p>

<form method="POST">

    <input
        type="hidden"
        name="id_incidencia"
        value="<?php echo $incidencia['id_incidencia']; ?>">

    <label>Nuevo estado:</label>

    <select name="estado">
        <option value="Abierta">Abierta</option>
        <option value="En proceso">En proceso</option>
        <option value="Resuelta">Resuelta</option>
        <option value="Cerrada">Cerrada</option>
    </select>

    <br><br>

    <button type="submit" name="actualizar">
        Actualizar estado
    </button>

</form>

<hr>

<h2>Historial de cambios</h2>

<table border="1">

<tr>
    <th>Acción</th>
    <th>Usuario</th>
    <th>Fecha</th>
</tr>

<?php while ($fila = $historial->fetch_assoc()) { ?>

<tr>
    <td><?php echo $fila['accion']; ?></td>
    <td><?php echo $fila['nombre'] . " " . $fila['apellidos']; ?></td>
    <td><?php echo $fila['fecha']; ?></td>
</tr>

<?php } ?>

</table>

<?php } ?>

</body>
</html>
