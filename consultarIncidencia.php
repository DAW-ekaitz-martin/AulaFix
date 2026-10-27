<?php
    require 'sql/aulafix.sql';
    $stmt = $cn->prepare("SELECT * FROM incidencias");
    $stmt ->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button id="todo">Mostrar todo</button>
    <h2>Filtrar por</h2>
    <button id="id">Id</button>
    <button id="estado">Estado</button>
    <button id="prioridad">Prioridad</button>
</body>
<script>
    const btnTodo = document.getElementById("todo");
    const btnId = document.getElementById("id");
    const btnEstado = document.getElementById("estado");
    const btnPrioridad = document.getElementById("prioridad");

    btnTodo.addEventListener("onclick", () => {
        
    })
</script>
</html>