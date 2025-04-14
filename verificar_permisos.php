<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /biblioteca/login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el tipo de usuario
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT tipo_usuario FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$tipo_usuario = $usuario['tipo_usuario'];

$stmt->close();

// Verificar si el usuario es administrador
if ($tipo_usuario !== 'administrador') {
    echo "Acceso denegado. No tienes permiso para acceder a esta página.";
    exit();
}
?>