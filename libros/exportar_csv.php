<?php
// Conexión a la base de datos
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

// Consulta a la base de datos
$sql = "SELECT `AUTOR`, `TITULO`, `EDITORIAL`, `ISBN`, `ID`, `ESTANTE`, `CATEGORIA`, `CDU`, `NUMERO` FROM libros";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Crear el archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=libros.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('AUTOR', 'TITULO', 'EDITORIAL', 'ISBN', 'ID', 'ESTANTE', 'CATEGORIA', 'CDU', 'NUMERO'));

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
?>