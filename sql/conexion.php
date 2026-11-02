<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
//En casa
/*$host = '127.0.0.1';
$port = 3307;
$user = 'root'; // En XAMPP, normalmente 'root'
$pass = 'root'; // En XAMPP, normalmente ''
$db = 'biblioteca_incidencias';*/
$host = 'localhost';
$user = 'root'; // En XAMPP, normalmente 'root'
$pass = 'root'; // En XAMPP, normalmente ''
$db = 'biblioteca_incidencias';
$cn = new mysqli($host,$user, $pass, $db);
$cn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
// En producción, registra el error y muestra un mensaje genérico.
exit('Error de conexión a la base de datos.');
}
