<?php
include '../db_connection.php';
include 'header_libros.php';

// Verificar si se ha enviado el formulario para actualizar el libro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['libro_id'])) {
    $libro_id = $_POST['libro_id'];
    $autor = $_POST['autor'];
    $titulo = $_POST['titulo'];
    $editorial = $_POST['editorial'];
    $isbn = $_POST['isbn'];
    $estante = $_POST['estante'];
    $categoria = $_POST['categoria'];
    $cdu = $_POST['cdu'];
    $numero = $_POST['numero'];

    // Actualizar los datos del libro en la base de datos
    $sql = "UPDATE libros SET AUTOR=?, TITULO=?, EDITORIAL=?, ISBN=?, ESTANTE=?, CATEGORIA=?, CDU=?, NUMERO=? WHERE ID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("ssssssssi", $autor, $titulo, $editorial, $isbn, $estante, $categoria, $cdu, $numero, $libro_id);
    if ($stmt->execute()) {
        echo "<p>El libro se actualizó correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el libro: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Obtener los datos del libro seleccionado
if (isset($_GET['libro_id'])) {
    $libro_id = $_GET['libro_id'];

    $sql = "SELECT AUTOR, TITULO, EDITORIAL, ISBN, ESTANTE, CATEGORIA, CDU, NUMERO FROM libros WHERE ID=?";
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
    <title>Editar Libro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Libro</h1>

        <?php if (isset($libro_data)): ?>
            <!-- Formulario para editar el libro -->
            <form method="POST" action="libro_editar.php">
                <input type="hidden" name="libro_id" value="<?php echo htmlspecialchars($libro_id); ?>">
                <div class="form-group">
                    <label for="autor">Autor:</label>
                    <input type="text" name="autor" id="autor" class="form-control" value="<?php echo htmlspecialchars($libro_data['AUTOR']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo htmlspecialchars($libro_data['TITULO']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="editorial">Editorial:</label>
                    <input type="text" name="editorial" id="editorial" class="form-control" value="<?php echo htmlspecialchars($libro_data['EDITORIAL']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN:</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo htmlspecialchars($libro_data['ISBN']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="estante">Estante:</label>
                    <input type="text" name="estante" id="estante" class="form-control" value="<?php echo htmlspecialchars($libro_data['ESTANTE']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <input type="text" name="categoria" id="categoria" class="form-control" value="<?php echo htmlspecialchars($libro_data['CATEGORIA']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cdu">CDU:</label>
                    <input type="text" name="cdu" id="cdu" class="form-control" value="<?php echo htmlspecialchars($libro_data['CDU']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="number" name="numero" id="numero" class="form-control" value="<?php echo htmlspecialchars($libro_data['NUMERO']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary buscar-btn">Actualizar</button>
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