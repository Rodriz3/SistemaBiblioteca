<?php
include 'header_libros.php';
include '../db_connection.php';

// Verificar si se han enviado todos los campos requeridos
if (
    isset($_POST['autor']) && isset($_POST['titulo']) && isset($_POST['editorial']) &&
    isset($_POST['numero'])
) {
    $autor = $_POST['autor'];
    $titulo = $_POST['titulo'];
    $editorial = $_POST['editorial'];
    $isbn = !empty($_POST['isbn']) ? $_POST['isbn'] : NULL;
    $estante = !empty($_POST['estante']) ? $_POST['estante'] : NULL;
    $categoria = !empty($_POST['categoria']) ? $_POST['categoria'] : NULL;
    $cdu = !empty($_POST['cdu']) ? $_POST['cdu'] : NULL;
    $numero = $_POST['numero'];
    $estado = "Disponible"; // Asignar el valor "Disponible" automáticamente

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO libros (AUTOR, TITULO, EDITORIAL, ISBN, ESTANTE, CATEGORIA, CDU, NUMERO, ESTADO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("sssssssss", $autor, $titulo, $editorial, $isbn, $estante, $categoria, $cdu, $numero, $estado);

    // Ejecutar la consulta
    if ($stmt->execute() === TRUE) {
        echo "Nuevo libro agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "Todos los campos obligatorios deben ser completados.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>