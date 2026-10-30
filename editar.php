<?php
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

        $mensaje = "Incidencia actualizada correctamente.";

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
    <title>Editar Incidencias</title>
</head>
<body>

<h1>Editar Incidencias</h1>

<h3>Buscar incidencia</h3>

<form method="POST">
    <label>ID de la incidencia:</label>
    <input type="number" name="id_incidencia" required>
    <button type="submit" name="buscar">Buscar</button>
</form>

<p><?php echo $mensaje; ?></p>

<a href="index.php">Volver</a>

<?php if ($incidencia) { ?>

    <hr>

    <h3>Editar incidencia</h3>

    <form method="POST">

        <input
            type="hidden"
            name="id_incidencia"
            value="<?php echo $incidencia['id_incidencia']; ?>">

        <p>
            <label>Título:</label><br>
            <input
                type="text"
                name="titulo"
                value="<?php echo htmlspecialchars($incidencia['titulo']); ?>"
                required>
        </p>

        <p>
            <label>Descripción:</label><br>
            <textarea
                name="descripcion"
                rows="4"
                cols="50"
                required><?php echo htmlspecialchars($incidencia['descripcion']); ?></textarea>
        </p>

        <p>
            <label>ID Categoría:</label><br>
            <input
                type="number"
                name="id_categoria"
                value="<?php echo $incidencia['id_categoria']; ?>"
                min="1" max="25"
                required>
        </p>

        <p>
            <label>ID Estado:</label><br>
            <input
                type="text"
                name="estado"
                value="<?php echo $incidencia['estado']; ?>"
                required>
        </p>

        <p>
            <label>Prioridad:</label><br>
            <select name="prioridad">
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
        </p>

        <button type="submit" name="actualizar">
            Guardar cambios
        </button>

    </form>

<?php } ?>

</body>
</html>