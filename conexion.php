<?php
$host = "localhost";
$user = "root";
$pass = ""; // Por defecto en XAMPP está vacío
$db   = "halcon";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
// Esto asegura que los acentos y la 'ñ' se vean bien
mysqli_set_charset($conexion, "utf8"); 
?>