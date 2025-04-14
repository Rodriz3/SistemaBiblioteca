<?php
include '../db_connection.php';

// Manejar la descarga del archivo CSV de ejemplo
if (isset($_GET['action']) && $_GET['action'] == 'download_example') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="ejemplo_libros.csv"');

    $output = fopen('php://output', 'w');

    // Escribir la primera fila con los encabezados
    fputcsv($output, ['AUTOR', 'TITULO', 'EDITORIAL', 'ISBN', 'ESTANTE', 'CATEGORIA', 'CDU', 'NUMERO']);

    // Escribir algunas filas de ejemplo
    fputcsv($output, ['Autor Ejemplo', 'Titulo Ejemplo', 'Editorial Ejemplo', '1234567890', 'Estante 1', 'Categoria 1', 'CDU Ejemplo', '1']);
    fputcsv($output, ['Otro Autor', 'Otro Titulo', 'Otra Editorial', '0987654321', 'Estante 2', 'Categoria 2', 'CDU Otro', '2']);

    fclose($output);
    exit();
}

include 'header_libros.php';

if (isset($_POST['submit'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $autor = $data[0];
            $titulo = $data[1];
            $editorial = $data[2];
            $isbn = $data[3];
            $estante = !empty($data[4]) ? $data[4] : NULL;
            $categoria = !empty($data[5]) ? $data[5] : NULL;
            $cdu = !empty($data[6]) ? $data[6] : NULL;
            $numero = !empty($data[7]) ? $data[7] : NULL;

            $sql = "INSERT INTO libros (AUTOR, TITULO, EDITORIAL, ISBN, ESTANTE, CATEGORIA, CDU, NUMERO) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ssssssss", $autor, $titulo, $editorial, $isbn, $estante, $categoria, $cdu, $numero);
            $stmt->execute();
        }
        fclose($handle);
        echo "<div class='alert alert-success'>Archivo CSV importado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al abrir el archivo CSV.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Importar Libros desde CSV</h1>
        <p>El archivo CSV debe contener los datos de los libros en el siguiente orden, sin necesidad de incluir encabezados:</p>
        <ul>
            <li><strong>AUTOR</strong>: Nombre del autor del libro.</li>
            <li><strong>TITULO</strong>: Título del libro.</li>
            <li><strong>EDITORIAL</strong>: Editorial del libro.</li>
            <li><strong>ISBN</strong>: Número ISBN del libro.</li>
            <li><strong>ESTANTE</strong>: Ubicación del estante donde se encuentra el libro (opcional, puede estar vacío).</li>
            <li><strong>CATEGORIA</strong>: Categoría principal del libro (opcional, puede estar vacío).</li>
            <li><strong>CDU</strong>: Clasificación Decimal Universal del libro (opcional, puede estar vacío).</li>
            <li><strong>NUMERO</strong>: Número del libro (opcional, puede estar vacío).</li>
        </ul>
        <a href="importar_libros.php?action=download_example" class="btn btn-info mb-4">Descargar Ejemplo CSV</a>
        <form action="importar_libros.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file">Seleccionar archivo CSV:</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary buscar-btn">Importar</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>