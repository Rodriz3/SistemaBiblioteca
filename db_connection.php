<?php
$servername = "localhost";
$username = "root"; // Cambia esto por tu nombre de usuario correcto
$password = ""; // Cambia esto por tu contraseña correcta
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>