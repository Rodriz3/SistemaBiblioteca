<?php
include '../db_connection.php';
include 'header_libros.php';

// Verificar si se ha enviado el formulario para eliminar el libro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['libro_id']) && isset($_POST['confirmar'])) {
    $libro_id = $_POST['libro_id'];

    // Eliminar el libro de la base de datos
    $sql = "DELETE FROM libros WHERE ID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $libro_id);
    if ($stmt->execute()) {
        echo "<p>El libro se eliminó correctamente.</p>";
    } else {
        echo "<p>Error al eliminar el libro: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Obtener los datos del libro seleccionado
if (isset($_GET['libro_id'])) {
    $libro_id = $_GET['libro_id'];

    $sql = "SELECT TITULO, AUTOR FROM libros WHERE ID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $libro_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $libro_data = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Libro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Libro</h1>

        <?php if (isset($libro_data)): ?>
            <h2>Detalles del Libro</h2>
            <p>Título: <?php echo htmlspecialchars($libro_data['TITULO']); ?></p>
            <p>Autor: <?php echo htmlspecialchars($libro_data['AUTOR']); ?></p>

            <!-- Formulario para confirmar la eliminación del libro -->
            <form method="POST" action="libro_eliminar.php">
                <input type="hidden" name="libro_id" value="<?php echo htmlspecialchars($libro_id); ?>">
                <p>¿Está seguro de que desea eliminar este libro?</p>
                <button type="submit" name="confirmar" value="si" class="btn btn-danger">Sí, eliminar</button>
                <a href="libros_buscar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <p>No se encontraron datos del libro.</p>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>