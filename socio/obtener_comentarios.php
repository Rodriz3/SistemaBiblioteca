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

// Verificar si el parámetro libro_id está presente
if (!isset($_GET['libro_id'])) {
    die("Error: libro_id no especificado.");
}

$libro_id = $_GET['libro_id'];

// Obtener comentarios de la base de datos
$sql = "SELECT usuario, comentario, fecha FROM comentarios WHERE libro_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $libro_id);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($comentarios);
?>