<?php include '../db_connection.php'; ?>
<?php include 'header_circulacion.php'; ?>

<?php
// Verificar si se ha recibido el ID del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    // Manejar la extensión del vencimiento
    if (isset($_POST['extender']) && isset($_POST['prestamo_id']) && isset($_POST['dias'])) {
        $prestamo_id = $_POST['prestamo_id'];
        $dias = $_POST['dias'];

        // Calcular la nueva fecha de vencimiento
        $nueva_vencimiento_sql = "SELECT DATE_ADD(vencimiento, INTERVAL ? DAY) AS nueva_vencimiento FROM prestamos WHERE id = ?";
        $stmt = $conn->prepare($nueva_vencimiento_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("ii", $dias, $prestamo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $nueva_vencimiento = $result->fetch_assoc()['nueva_vencimiento'];

        // Actualizar la fecha de vencimiento del préstamo
        $actualizar_sql = "UPDATE prestamos SET vencimiento = ? WHERE id = ?";
        $stmt = $conn->prepare($actualizar_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("si", $nueva_vencimiento, $prestamo_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Fecha de vencimiento extendida exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al extender la fecha de vencimiento: " . $stmt->error . "</div>";
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

        // Obtener los libros prestados por el usuario
        $sql = "SELECT prestamos.id AS prestamo_id, libros.titulo, prestamos.vencimiento 
                FROM prestamos 
                JOIN libros ON prestamos.id_libro = libros.id 
                WHERE prestamos.id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Libros Prestados</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>Título</th><th>Vencimiento</th><th>Extender</th></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['titulo'] . "</td>";
                echo "<td>" . $row['vencimiento'] . "</td>";
                echo "<td>
                        <form method='POST' action='renovar_3.php' class='d-inline'>
                            <input type='hidden' name='usuario_id' value='$usuario_id'>
                            <input type='hidden' name='prestamo_id' value='" . $row['prestamo_id'] . "'>
                            <input type='hidden' name='dias' value='7'>
                            <button type='submit' name='extender' class='btn btn-primary buscar-btn'>7 días</button>
                        </form>
                        <form method='POST' action='renovar_3.php' class='d-inline'>
                            <input type='hidden' name='usuario_id' value='$usuario_id'>
                            <input type='hidden' name='prestamo_id' value='" . $row['prestamo_id'] . "'>
                            <input type='hidden' name='dias' value='15'>
                            <button type='submit' name='extender' class='btn btn-primary buscar-btn'>15 días</button>
                        </form>
                        <form method='POST' action='renovar_3.php' class='d-inline'>
                            <input type='hidden' name='usuario_id' value='$usuario_id'>
                            <input type='hidden' name='prestamo_id' value='" . $row['prestamo_id'] . "'>
                            <input type='hidden' name='dias' value='30'>
                            <button type='submit' name='extender' class='btn btn-primary buscar-btn'>30 días</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay libros prestados por este usuario.</p>";
        }
    } else {
        echo "<p>No se encontraron datos del usuario.</p>";
    }
} else {
    echo "<p>No se ha seleccionado ningún usuario.</p>";
}

$conn->close();
?>