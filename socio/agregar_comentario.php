<?php
session_start(); // Iniciar la sesión

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

// Obtener datos del formulario
$libro_id = $_POST['libro_id'];
$usuario = $_POST['usuario'];
$comentario = $_POST['comentario'];

// Insertar comentario en la base de datos
$sql = "INSERT INTO comentarios (libro_id, usuario, comentario) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $libro_id, $usuario, $comentario);

if ($stmt->execute()) {
    echo "Comentario agregado exitosamente.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirigir de vuelta a la página de libros
header("Location: libros_2.php");
exit();
?>