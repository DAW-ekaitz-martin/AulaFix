<?php
    require 'sql/conexion.php';
    function obtenerUsuario($idUsuario, $cn){
        $stmt = $cn -> prepare("SELECT nombre FROM usuarios WHERE id_usuario = ?");
        $stmt -> bind_param("i", $idUsuario);
        $stmt -> execute();
        $res = $stmt -> get_result();
        $nombre = $res -> fetch_assoc();
        $stmt -> close();
        return $nombre;
    }
?>