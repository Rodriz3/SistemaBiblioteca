<?php
include '../db_connection.php';

// Verificar si se han enviado todos los campos requeridos
if (
    isset($_POST['ubicacion']) && isset($_POST['numero']) && isset($_POST['instagram']) &&
    isset($_POST['facebook']) && isset($_POST['mail']) && isset($_POST['horarios']) &&
    isset($_POST['libro1'])
) {
    $ubicacion = $_POST['ubicacion'];
    $numero = $_POST['numero'];
    $instagram = $_POST['instagram'];
    $facebook = $_POST['facebook'];
    $mail = $_POST['mail'];
    $horarios = $_POST['horarios'];
    $libro1 = $_POST['libro1'];
    $libro2 = $_POST['libro2'] ?? '';
    $libro3 = $_POST['libro3'] ?? '';
    $libro4 = $_POST['libro4'] ?? '';
    $libro5 = $_POST['libro5'] ?? '';

    // Preparar los datos para guardarlos en un archivo JSON
    $data = [
        'ubicacion' => $ubicacion,
        'numero' => $numero,
        'instagram' => $instagram,
        'facebook' => $facebook,
        'mail' => $mail,
        'horarios' => $horarios,
        'libros_recomendados' => [
            $libro1,
            $libro2,
            $libro3,
            $libro4,
            $libro5
        ]
    ];

    // Guardar los datos en un archivo JSON
    file_put_contents('inicio_data.json', json_encode($data));

    echo "Información actualizada correctamente.";
} else {
    echo "Todos los campos obligatorios deben ser completados.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>