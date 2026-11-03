<?php
    if(isset($_COOKIE["tema"]) && isset($_GET["tema"])) {
        setcookie("tema", $_GET["tema"],time() + 604800, "/");
    }
    header("Location:index.php");
?>