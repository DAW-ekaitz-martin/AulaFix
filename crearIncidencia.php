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
    <form action="crearIncidencia.php" method="post">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo"><br><br>

        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion"><br><br>

        <label for="id_responsable">Responsable:</label>
        <input type="number" name="id_responsable" id="id_responsable"><br><br>

        <label for="id_categoria">Categoría:</label>
        <input type="number" name="id_categoria" id="id_categoria"><br><br>

        <label for="prioridad">Prioridad:</label>
        <input type="text" name="prioridad" id="prioridad"><br><br>

        <button type="submit">Enviar</button>
    </form> 
    <?php
        try {

            if(isset($_POST["titulo"]) && isset($_POST["descripcion"]) &&
            isset($_POST["id_responsable"]) && isset($_POST["id_categoria"]) && 
            isset($_POST["prioridad"])) {
                $stmt = $cn ->prepare("INSERT INTO incidencias (titulo,descripcion,id_responsable,id_categoria,prioridad) VALUES (?,?,?,?,?);");
                $stmt->bind_param('ssiis', $_POST["titulo"], $_POST["descripcion"], $_POST["id_responsable"], $_POST["id_categoria"], $_POST["prioridad"]);
                $resultado = $stmt->execute();
                if($resultado && $stmt->affected_rows > 0 ) {
    ?>

                    <p>Incidencia añadida con éxito</p>
    <?php
                }
    ?>
    <?php
            }
        } catch (Exception) {
    ?>
            <p>Inserción cancelada, revise los campos y pruebe otra vez</p>
    <?php
        }
    ?>
    
</body>
</html>
