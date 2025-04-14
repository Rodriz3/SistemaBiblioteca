<?php include 'header_circulacion.php'; ?>

<?php
// Incluir el archivo de conexión a la base de datos
include '../db_connection.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los libros y los datos del usuario asociado
$libros_sql = "SELECT prestamos.id AS prestamo_id, libros.id AS libro_id, libros.titulo, libros.autor, prestamos.vencimiento, 
                      usuarios.nombre, usuarios.apellido, usuarios.dni 
               FROM prestamos 
               LEFT JOIN libros ON prestamos.id_libro = libros.id 
               LEFT JOIN usuarios ON prestamos.id_usuario = usuarios.id 
               WHERE prestamos.vencimiento IS NOT NULL";
$libros_result = $conn->query($libros_sql);
if ($libros_result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Manejar la extensión del préstamo
if (isset($_POST['extender'])) {
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
        // Registrar la renovación en el historial de préstamos
        $accion = 'Renovación';
        $historial_sql = "INSERT INTO historial_prestamos (libro_id, usuario_id, fecha, accion, vencimiento) 
                          SELECT id_libro, id_usuario, NOW(), ?, ? FROM prestamos WHERE id = ?";
        $stmt = $conn->prepare($historial_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta (historial_sql): " . $conn->error);
        }
        $stmt->bind_param("ssi", $accion, $nueva_vencimiento, $prestamo_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Fecha de vencimiento extendida exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al registrar la renovación en el historial: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error al extender la fecha de vencimiento: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renovar Préstamo de Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Renovar Préstamo de Libros</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ID</th>
                    <th>Vencimiento</th>
                    <th>Usuario</th>
                    <th>Extender</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $libros_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['titulo']; ?></td>
                        <td><?php echo $row['autor']; ?></td>
                        <td><?php echo $row['libro_id']; ?></td>
                        <td><?php echo $row['vencimiento']; ?></td>
                        <td><?php echo $row['nombre'] . " " . $row['apellido'] . " (DNI: " . $row['dni'] . ")"; ?></td>
                        <td>
                            <form method="POST" action="renovar.php" class="d-inline">
                                <input type="hidden" name="prestamo_id" value="<?php echo $row['prestamo_id']; ?>">
                                <input type="hidden" name="dias" value="7">
                                <button type="submit" name="extender" class="btn btn-primary buscar-btn">7 días</button>
                            </form>
                            <form method="POST" action="renovar.php" class="d-inline">
                                <input type="hidden" name="prestamo_id" value="<?php echo $row['prestamo_id']; ?>">
                                <input type="hidden" name="dias" value="15">
                                <button type="submit" name="extender" class="btn btn-primary buscar-btn">15 días</button>
                            </form>
                            <form method="POST" action="renovar.php" class="d-inline">
                                <input type="hidden" name="prestamo_id" value="<?php echo $row['prestamo_id']; ?>">
                                <input type="hidden" name="dias" value="30">
                                <button type="submit" name="extender" class="btn btn-primary buscar-btn">30 días</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>