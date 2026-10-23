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

// Actualizar incidencia
if (isset($_POST["actualizar"])) {

    $id = $_POST["id_incidencia"];
    $campo = $_POST["campo"];
    $valor = $_POST["valor"];

    $camposPermitidos = [
        "titulo",
        "descripcion",
        "id_categoria",
        "id_estado",
        "prioridad"
    ];

    if (in_array($campo, $camposPermitidos)) {

        $sql = "UPDATE incidencias SET $campo = ? WHERE id_incidencia = ?";
        $stmt = $cn->prepare($sql);
        $stmt->bind_param("si", $valor, $id);

        if ($stmt->execute()) {
            $mensaje = "Incidencia actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar.";
        }
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

<?php if($incidencia){ ?>

<hr>

<h3>Datos actuales</h3>

<p><strong>ID:</strong> <?php echo $incidencia["id_incidencia"]; ?></p>

<p><strong>Título:</strong>
<?php echo $incidencia["titulo"]; ?>
</p>

<p><strong>Descripción:</strong>
<?php echo $incidencia["descripcion"]; ?>
</p>

<p><strong>Categoría:</strong>
<?php echo $incidencia["id_categoria"]; ?>
</p>

<p><strong>Estado:</strong>
<?php echo $incidencia["id_estado"]; ?>
</p>

<p><strong>Prioridad:</strong>
<?php echo $incidencia["prioridad"]; ?>
</p>

<hr>

<h3>Modificar dato</h3>

<form method="POST">

    <input type="hidden"
           name="id_incidencia"
           value="<?php echo $incidencia['id_incidencia']; ?>">

    <label>Campo a modificar:</label>

    <select name="campo">
        <option value="titulo">Título</option>
        <option value="descripcion">Descripción</option>
        <option value="id_categoria">Categoría</option>
        <option value="id_estado">Estado</option>
        <option value="prioridad">Prioridad</option>
    </select>

    <br><br>

    <label>Nuevo valor:</label>
    <input type="text" name="valor" required>

    <br><br>

    <button type="submit" name="actualizar">
        Actualizar
    </button>

</form>

<?php } ?>

</body>
</html>