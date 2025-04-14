<?php
include '../db_connection.php';
include 'header_libros.php';

// Obtener el ID del libro desde la URL
$libro_id = isset($_GET['libro_id']) ? (int)$_GET['libro_id'] : 0;

if ($libro_id <= 0) {
    die("ID de libro inválido.");
}

// Verificar si el libro está en la tabla prestamos
$sql = "SELECT id FROM prestamos WHERE id_libro = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $libro_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("El estado del libro no puede modificarse porque está prestado.");
}

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['estado'])) {
    $nuevo_estado = $_POST['estado'];

    // Actualizar el estado del libro en la base de datos
    $sql = "UPDATE libros SET ESTADO = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("si", $nuevo_estado, $libro_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Estado del libro actualizado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el estado del libro: " . $stmt->error . "</div>";
    }
}

// Consultar la base de datos para obtener los detalles del libro
$sql = "SELECT TITULO, AUTOR, ESTADO FROM libros WHERE ID = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $libro_id);
$stmt->execute();
$result = $stmt->get_result();
$libro = $result->fetch_assoc();

if (!$libro) {
    die("Libro no encontrado.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Estado del Libro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <h2 class="text-center">Modificar Estado del Libro</h2>
        <form method="POST" action="libro_estado.php?libro_id=<?php echo $libro_id; ?>">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo htmlspecialchars($libro['TITULO']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="autor">Autor:</label>
                <input type="text" id="autor" name="autor" class="form-control" value="<?php echo htmlspecialchars($libro['AUTOR']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" class="form-control" required>
                    <option value="Disponible" <?php echo $libro['ESTADO'] == 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="Inhallable" <?php echo $libro['ESTADO'] == 'Inhallable' ? 'selected' : ''; ?>>Inhallable</option>
                    <option value="Dañado" <?php echo $libro['ESTADO'] == 'Dañado' ? 'selected' : ''; ?>>Dañado</option>
                    <option value="En reparación" <?php echo $libro['ESTADO'] == 'En reparación' ? 'selected' : ''; ?>>En reparación</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Estado</button>
        </form>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>