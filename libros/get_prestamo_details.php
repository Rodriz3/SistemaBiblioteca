<?php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['libro_id'])) {
    $libro_id = $_POST['libro_id'];

    // Consulta SQL para obtener los detalles del préstamo
    $sql = "SELECT prestamos.id AS prestamo_id, prestamos.vencimiento, 
                   usuarios.nombre, usuarios.apellido, usuarios.dni 
            FROM prestamos 
            JOIN usuarios ON prestamos.id_usuario = usuarios.id
            WHERE prestamos.id_libro = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $libro_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>Vencimiento</th><th>Nombre del Usuario</th><th>Apellido del Usuario</th><th>DNI del Usuario</th></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['vencimiento']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dni']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No se encontraron detalles del préstamo.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Solicitud no válida.";
}
?>