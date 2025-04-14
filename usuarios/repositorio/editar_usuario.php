<?php
include 'header_usuarios.php';
include '../db_connection.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['edit_user_id'];
    $password = $_POST['edit_password'];
    $nombre = $_POST['edit_nombre'];
    $apellido = $_POST['edit_apellido'];
    $dni = $_POST['edit_dni'];
    $categoria = $_POST['edit_categoria'];
    $correo = $_POST['edit_correo'];
    $telefono = $_POST['edit_telefono'];
    $numero_de_socio = $_POST['edit_numero_de_socio'];
    $tipo_usuario = $_POST['edit_tipo_usuario'];
    $direccion = $_POST['edit_direccion'];
    $genero = $_POST['edit_genero'];

    // Actualizar los datos del usuario en la base de datos
    $sql = "UPDATE usuarios SET password=?, nombre=?, apellido=?, dni=?, categoria=?, correo=?, telefono=?, numero_de_socio=?, tipo_usuario=?, direccion=?, genero=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("sssssssssssi", $password, $nombre, $apellido, $dni, $categoria, $correo, $telefono, $numero_de_socio, $tipo_usuario, $direccion, $genero, $id);
    if ($stmt->execute()) {
        // Redirigir de nuevo a la página de usuarios después de la actualización
        @header("Location: usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>