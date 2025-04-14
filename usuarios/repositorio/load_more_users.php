<?php
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

// Obtener el offset de la solicitud AJAX
$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
$limit = 20;

// Consulta a la base de datos
$sql = "SELECT id, nombre, apellido, dni, categoria, correo, telefono, numero_de_socio, vencimiento_cuota FROM usuarios LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Salida de datos de cada fila
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['apellido'] . "</td>";
        echo "<td>" . $row['dni'] . "</td>";
        echo "<td>" . $row['categoria'] . "</td>";
        echo "<td>" . $row['correo'] . "</td>";
        echo "<td>" . $row['telefono'] . "</td>";
        echo "<td>" . $row['numero_de_socio'] . "</td>";
        echo "<td>" . $row['vencimiento_cuota'] . "</td>";
        echo "<td>
                <button class='btn btn-info' onclick='openEditModal(" . json_encode($row) . ")'>Editar</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center'>No hay más usuarios disponibles</td></tr>";
}

$conn->close();
?>