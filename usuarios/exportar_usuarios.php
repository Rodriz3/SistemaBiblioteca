<?php
include '../db_connection.php';

// Establecer el nombre del archivo CSV
$filename = "usuarios_" . date('Ymd') . ".csv";

// Establecer los encabezados para la descarga del archivo CSV
header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=$filename");

// Abrir la salida en modo escritura
$output = fopen('php://output', 'w');

// Escribir la fila de encabezado
fputcsv($output, array('Apellido', 'DNI', 'Categoria', 'Correo', 'Telefono', 'Numero de Socio', 'Tipo de Usuario', 'Nombre', 'Direccion', 'Genero'));

// Obtener los datos de la tabla usuarios
$sql = "SELECT apellido, dni, categoria, correo, telefono, numero_de_socio, tipo_usuario, nombre, direccion, genero FROM usuarios";
$result = $conn->query($sql);

// Escribir los datos en el archivo CSV
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Cerrar la salida
fclose($output);
?>