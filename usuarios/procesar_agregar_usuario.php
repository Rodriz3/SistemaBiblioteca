<?php
include 'header_usuarios.php';
include '../db_connection.php';

// Verificar si se han enviado todos los campos requeridos
if (
    isset($_POST['nombre']) && isset($_POST['password']) && isset($_POST['apellido']) &&
    isset($_POST['dni']) && isset($_POST['categoria']) && isset($_POST['correo']) &&
    isset($_POST['telefono']) && isset($_POST['numero_de_socio']) && isset($_POST['tipo_usuario']) &&
    isset($_POST['direccion']) && isset($_POST['genero'])
) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $categoria = $_POST['categoria'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $numero_de_socio = $_POST['numero_de_socio'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO usuarios (nombre, password, apellido, dni, categoria, correo, telefono, numero_de_socio, tipo_usuario, direccion, genero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("sssssssssss", $nombre, $password, $apellido, $dni, $categoria, $correo, $telefono, $numero_de_socio, $tipo_usuario, $direccion, $genero);

    // Ejecutar la consulta
    if ($stmt->execute() === TRUE) {
        echo "Nuevo usuario agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "Todos los campos son requeridos.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>