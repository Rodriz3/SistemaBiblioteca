<?php
session_start();
include '../db_connection.php';
include 'header_pagos.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario que ha iniciado sesión
$usuario_id_sesion = $_SESSION['usuario_id'];

// Consulta a la base de datos para obtener el apellido del usuario que ha iniciado sesión
$sql_sesion = "SELECT apellido FROM usuarios WHERE id = ?";
$stmt_sesion = $conn->prepare($sql_sesion);
if ($stmt_sesion === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt_sesion->bind_param("i", $usuario_id_sesion);
$stmt_sesion->execute();
$result_sesion = $stmt_sesion->get_result();
$user_data_sesion = $result_sesion->fetch_assoc();
$recibio_pago = $user_data_sesion['apellido'] ?? '';

// Verificar si se ha recibido el ID del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    // Procesar el formulario para agregar un nuevo pago
    if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && isset($_POST['metodo_pago'])) {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $metodo_pago = $_POST['metodo_pago'];
        $fecha = date('Y-m-d'); // Fecha actual

        // Insertar datos en la tabla pagos
        $sql = "INSERT INTO pagos (usuario_id, fecha, fecha_inicio, fecha_fin, metodo_pago, recibio_pago) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("isssss", $usuario_id, $fecha, $fecha_inicio, $fecha_fin, $metodo_pago, $recibio_pago);
        if ($stmt->execute() === TRUE) {
            echo "<p>El pago se registró exitosamente.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    }

    // Obtener los datos del usuario seleccionado
    $sql = "SELECT nombre, apellido, dni FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if ($user_data) {
        $nombre = $user_data['nombre'];
        $apellido = $user_data['apellido'];
        $dni = $user_data['dni'];

        echo "<h2>Datos del Usuario</h2>";
        echo "<p>Nombre: $nombre</p>";
        echo "<p>Apellido: $apellido</p>";
        echo "<p>DNI: $dni</p>";

        // Obtener el último pago del usuario seleccionado
        $sql = "SELECT * FROM pagos WHERE usuario_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Último Pago del Usuario</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>Fecha</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Método de Pago</th><th>Recibió Pago</th></thead>";
            echo "<tbody>";
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['fecha'] . "</td>";
            echo "<td>" . $row['fecha_inicio'] . "</td>";
            echo "<td>" . $row['fecha_fin'] . "</td>";
            echo "<td>" . $row['metodo_pago'] . "</td>";
            echo "<td>" . $row['recibio_pago'] . "</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay pagos registrados para este usuario.</p>";
        }

        // Formulario para agregar un nuevo pago
        echo "<h2>Agregar Nuevo Pago</h2>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='usuario_id' value='$usuario_id'>";
        echo "<label for='fecha_inicio'>Fecha de Inicio:</label>";
        echo "<input type='date' id='fecha_inicio' name='fecha_inicio' required><br>";
        echo "<label for='fecha_fin'>Fecha de Fin:</label>";
        echo "<input type='date' id='fecha_fin' name='fecha_fin' required><br>";
        echo "<label for='metodo_pago'>Seleccionar método de pago:</label>";
        echo "<select id='metodo_pago' name='metodo_pago' required>";
        echo "<option value='Efectivo'>Efectivo</option>";
        echo "<option value='Transferencia'>Transferencia</option>";
        echo "</select><br>";
        echo "<input type='submit' value='Agregar Pago'>";
        echo "</form>";
    } else {
        echo "<p>No se encontraron datos del usuario.</p>";
    }
} else {
    echo "<p>No se ha seleccionado ningún usuario.</p>";
}

$conn->close();
?>

<style>
    body {
        background-color: #272230 !important;
        color: white !important;
    }
    .form-control, input[type="date"], select {
        background-color: #272230 !important;
        color: white !important;
        border: 1px solid #444 !important;
    }
    .form-control::placeholder, input[type="date"]::placeholder, select::placeholder {
        color: #bbb !important;
    }
</style>