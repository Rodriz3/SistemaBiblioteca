<?php
include '../db_connection.php';
session_start();

// Obtener el ID del usuario que ha iniciado sesión
$usuario_id = $_SESSION['usuario_id'];

// Obtener los datos del formulario
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

// Actualizar los datos del usuario en la base de datos
$sql = "UPDATE usuarios SET correo = ?, telefono = ?, direccion = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("sssi", $correo, $telefono, $direccion, $usuario_id);
if ($stmt->execute() === false) {
    die("Error en la ejecución de la consulta: " . $stmt->error);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Redirigir de vuelta a la página de datos personales
header("Location: datos_personales.php");
exit();
?>