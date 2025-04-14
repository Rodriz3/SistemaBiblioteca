<?php include 'header_libros.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }
        .modal-content {
            background-color: #272230 !important; /* Fondo del modal */
            color: white !important; /* Color del texto del modal */
        }
        .modal-header, .modal-body, .modal-footer {
            border-color: #444 !important; /* Color del borde del modal */
        }
        .close {
            color: white !important; /* Color del botón de cerrar */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        var offset = 20; // Inicialmente 20 porque ya cargamos los primeros 20 registros

        function loadMoreBooks() {
            var search_option = "<?php echo isset($_GET['search_option']) ? $_GET['search_option'] : ''; ?>";
            var search_term = "<?php echo isset($_GET['search_term']) ? $_GET['search_term'] : ''; ?>";
            $.ajax({
                url: 'load_more_books.php',
                type: 'POST',
                data: { offset: offset, search_option: search_option, search_term: search_term },
                success: function(response) {
                    $('#booksTableBody').append(response);
                    offset += 20; // Incrementar el offset para la próxima solicitud
                }
            });
        }

        function showPrestamoDetails(libroId) {
            $.ajax({
                url: 'get_prestamo_details.php',
                type: 'POST',
                data: { libro_id: libroId },
                success: function(response) {
                    $('#prestamoDetailsModalBody').html(response);
                    $('#prestamoDetailsModal').modal('show');
                }
            });
        }
    </script>
</head>
<body class="lexend-custom">
<main class="container mt-5 text-center">
    <h1 class="mb-4">Lista de Libros</h1>
    <form method="GET" action="libros.php" class="search-form mb-4">
        <div class="form-group">
            <select name="search_option" class="form-control" required>
                <option value="">Seleccionar opción</option>
                <option value="autor" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'autor' ? 'selected' : ''; ?>>Buscar por autor</option>
                <option value="titulo" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'titulo' ? 'selected' : ''; ?>>Buscar por título</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="search_term" class="form-control" placeholder="Ingresar término de búsqueda" value="<?php echo isset($_GET['search_term']) ? $_GET['search_term'] : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>AUTOR</th>
                    <th>TITULO</th>
                    <th>EDITORIAL</th>
                    <th>ESTANTE</th>
                    <th>CATEGORIA</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody id="booksTableBody">
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
                $limit = 20;

                // Consulta a la base de datos
                $sql = "SELECT `ID`, `AUTOR`, `TITULO`, `EDITORIAL`, `ESTANTE`, `categoria`, `ESTADO` 
                        FROM libros";
                if (!empty($search_option) && !empty($search_term)) {
                    if ($search_option == 'autor') {
                        $sql .= " WHERE `AUTOR` LIKE '%$search_term%'";
                    } elseif ($search_option == 'titulo') {
                        $sql .= " WHERE `TITULO` LIKE '%$search_term%'";
                    }
                }
                $sql .= " LIMIT $limit";
                $result = $conn->query($sql);

                if ($result === false) {
                    die("Error en la consulta: " . $conn->error);
                }

                if ($result->num_rows > 0) {
                    // Salida de datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["EDITORIAL"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ESTANTE"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
                        if ($row["ESTADO"] == "Prestado") {
                            echo "<td><a href='#' onclick='showPrestamoDetails(" . htmlspecialchars($row["ID"]) . ")'>" . htmlspecialchars($row["ESTADO"]) . "</a></td>";
                        } else {
                            echo "<td>" . htmlspecialchars($row["ESTADO"]) . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay resultados</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-primary buscar-btn" onclick="loadMoreBooks()">Cargar más</button>
</main>

<!-- Modal para mostrar detalles del préstamo -->
<div class="modal fade" id="prestamoDetailsModal" tabindex="-1" aria-labelledby="prestamoDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prestamoDetailsModalLabel">Detalles del Préstamo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="prestamoDetailsModalBody">
                <!-- Los detalles del préstamo se cargarán aquí mediante AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencias -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>