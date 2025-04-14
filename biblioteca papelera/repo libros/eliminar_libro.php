<?php include 'header_libros.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Libro</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <main class="container mt-5">
        <h1 class="mb-4">Eliminar Libro</h1>
        <form method="GET" action="eliminar_libro.php" class="search-form mb-4">
            <select name="search_option" class="form-control" required>
                <option value="">Seleccionar opción</option>
                <option value="autor" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'autor' ? 'selected' : ''; ?>>Buscar por autor</option>
                <option value="titulo" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'titulo' ? 'selected' : ''; ?>>Buscar por título</option>
            </select>
            <input type="text" name="search_term" class="form-control" placeholder="Ingresar término de búsqueda" value="<?php echo isset($_GET['search_term']) ? $_GET['search_term'] : ''; ?>" required>
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th>AUTOR</th>
                    <th>TITULO</th>
                    <th>EDITORIAL</th>
                    <th>ISBN</th>
                    <th>D</th>
                    <th>PERSONA</th>
                    <th>ESTANTE</th>
                    <th>CATEGORIA</th>
                    <th>CATEGORIA2</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                $servername = "localhost";
                $username = "root"; // Cambia esto por tu nombre de usuario correcto
                $password = ""; // Cambia esto por tu contraseña correcta
                $dbname = "biblioteca";

                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar la conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtener los términos de búsqueda
                $search_option = isset($_GET['search_option']) ? $_GET['search_option'] : '';
                $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

                // Consulta a la base de datos
                $sql = "SELECT `ID`, `FECHA`, `AUTOR`, `TITULO`, `EDITORIAL`, `ISBN`, `D`, `PERSONA`, `ESTANTE`, `categoria`, `categoria2` 
                        FROM libros";
                if (!empty($search_option) && !empty($search_term)) {
                    if ($search_option == 'autor') {
                        $sql .= " WHERE `AUTOR` LIKE '%$search_term%'";
                    } elseif ($search_option == 'titulo') {
                        $sql .= " WHERE `TITULO` LIKE '%$search_term%'";
                    }
                }
                $result = $conn->query($sql);

                if ($result === false) {
                    die("Error en la consulta: " . $conn->error);
                }

                if ($result->num_rows > 0) {
                    // Salida de datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["FECHA"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["EDITORIAL"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ISBN"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["D"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["PERSONA"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ESTANTE"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["categoria2"]) . "</td>";
                        echo "<td><a href='eliminar_libro.php?id=" . htmlspecialchars($row["ID"]) . "' class='btn btn-danger'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>No hay resultados</td></tr>";
                }

                // Eliminar libro
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $delete_sql = "DELETE FROM libros WHERE ID = $id";
                    if ($conn->query($delete_sql) === TRUE) {
                        echo "<div class='alert alert-success'>Libro eliminado exitosamente</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error al eliminar el libro: " . $conn->error . "</div>";
                    }
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </main>
    <!-- Bootstrap JS and dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>