<?php
session_start();

include '../db_connection.php';

// Manejar la solicitud AJAX para obtener comentarios y cargar más libros
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'get_comments') {
        $libro_id = $_POST['libro_id'];

        $sql_comentarios = "SELECT comentario, id_usuarios FROM comentarios WHERE libro_id = ? AND aprobado = 1";
        $stmt_comentarios = $conn->prepare($sql_comentarios);
        if ($stmt_comentarios === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt_comentarios->bind_param("i", $libro_id);
        $stmt_comentarios->execute();
        $result_comentarios = $stmt_comentarios->get_result();

        if ($result_comentarios->num_rows > 0) {
            echo "<ul>";
            while($comentario = $result_comentarios->fetch_assoc()) {
                $id_usuarios = $comentario['id_usuarios'];
                $sql_usuario = "SELECT nombre FROM usuarios WHERE id = ?";
                $stmt_usuario = $conn->prepare($sql_usuario);
                if ($stmt_usuario === false) {
                    die("Error en la preparación de la consulta: " . $conn->error);
                }
                $stmt_usuario->bind_param("i", $id_usuarios);
                $stmt_usuario->execute();
                $result_usuario = $stmt_usuario->get_result();
                $usuario = $result_usuario->fetch_assoc();
                $nombre_usuario = $usuario['nombre'];

                echo "<li><strong>" . htmlspecialchars($nombre_usuario) . ":</strong> " . htmlspecialchars($comentario['comentario']) . "</li>";

                $stmt_usuario->close();
            }
            echo "</ul>";
        } else {
            echo "No hay comentarios.";
        }

        // Agregar el formulario "Add Comentario" dentro del modal de comentarios
        echo "<form method='POST' action='' class='mt-3'>
                <input type='hidden' name='libro_id' value='$libro_id'>
                <div class='form-group'>
                    <label for='comentario'>Agregar Comentario</label>
                    <textarea name='comentario' id='comentario' class='form-control' required></textarea>
                </div>
                <button type='submit' name='agregar_comentario' class='btn btn-primary buscar-btn'>Enviar Comentario</button>
              </form>";

        $stmt_comentarios->close();
        exit();
    } elseif ($_POST['action'] == 'load_more') {
        $offset = (int)$_POST['offset'];
        $search_option = $_POST['search_option'];
        $search_term = $_POST['search_term'];

        $sql = "SELECT `ID`, `AUTOR`, `TITULO` FROM libros";
        if (!empty($search_option) && !empty($search_term)) {
            if ($search_option == 'autor') {
                $sql .= " WHERE `AUTOR` LIKE '%$search_term%'";
            } elseif ($search_option == 'titulo') {
                $sql .= " WHERE `TITULO` LIKE '%$search_term%'";
            }
        }
        $sql .= " LIMIT 20 OFFSET $offset";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Error en la consulta: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
                echo "<td>";
                echo "<button class='btn btn-secondary' onclick='loadComments(" . htmlspecialchars($row["ID"]) . ")'>Comentarios</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron más libros</td></tr>";
        }
        exit();
    }
}

include '../header.php';

// Procesar el formulario de comentarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_comentario'])) {
    $libro_id = $_POST['libro_id'];
    $id_usuarios = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
    $comentario = $_POST['comentario'];

    $sql_comentario = "INSERT INTO comentarios (libro_id, id_usuarios, comentario, aprobado) VALUES (?, ?, ?, 0)";
    $stmt_comentario = $conn->prepare($sql_comentario);
    if ($stmt_comentario === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_comentario->bind_param("iis", $libro_id, $id_usuarios, $comentario);
    $stmt_comentario->execute();
    $stmt_comentario->close();
}

// Obtener los libros
$search_option = isset($_GET['search_option']) ? $_GET['search_option'] : '';
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Consulta a la base de datos
$sql = "SELECT `ID`, `AUTOR`, `TITULO` FROM libros";
if (!empty($search_option) && !empty($search_term)) {
    if ($search_option == 'autor') {
        $sql .= " WHERE `AUTOR` LIKE '%$search_term%'";
    } elseif ($search_option == 'titulo') {
        $sql .= " WHERE `TITULO` LIKE '%$search_term%'";
    }
}
$sql .= " LIMIT 20";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var offset = 20; // Inicialmente 20 porque ya cargamos los primeros 20 registros

        function setLibroId(libroId) {
            document.getElementById('libro_id').value = libroId;
        }

        function loadComments(libroId) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { action: 'get_comments', libro_id: libroId },
                success: function(response) {
                    $('#commentsModalBody').html(response);
                    $('#commentsModal').modal('show');
                }
            });
        }

        function loadMoreBooks() {
            var search_option = "<?php echo $search_option; ?>";
            var search_term = "<?php echo $search_term; ?>";
            $.ajax({
                url: '',
                type: 'POST',
                data: { action: 'load_more', offset: offset, search_option: search_option, search_term: search_term },
                success: function(response) {
                    $('#booksTableBody').append(response);
                    offset += 20; // Incrementar el offset para la próxima solicitud
                }
            });
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mb-4">Lista de Libros</h1>
                <form method="GET" action="libros_2.php" class="form-inline justify-content-center mb-3">
                    <div class="form-group mr-2">
                        <select name="search_option" class="form-control" required>
                            <option value="">Seleccionar opción</option>
                            <option value="autor" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'autor' ? 'selected' : ''; ?>>Buscar por autor</option>
                            <option value="titulo" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'titulo' ? 'selected' : ''; ?>>Buscar por título</option>
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <input type="text" name="search_term" class="form-control" placeholder="Ingresar término de búsqueda" value="<?php echo isset($_GET['search_term']) ? $_GET['search_term'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary buscar-btn buscar-btn">Buscar</button>
                </form>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Autor</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="booksTableBody">
                <?php
                if ($result->num_rows > 0) {
                    // Salida de datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-secondary' onclick='loadComments(" . htmlspecialchars($row["ID"]) . ")'>Comentarios</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron libros</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center">
            <button class="btn btn-primary buscar-btn" onclick="loadMoreBooks()">Cargar más</button>
        </div>
    </div>

    <!-- Modal para mostrar comentarios y agregar comentario -->
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">Comentarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="commentsModalBody">
                    <!-- Los comentarios y el formulario para agregar comentarios se cargarán aquí mediante AJAX -->
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilo personalizado para los botones específicos de inicio */
        .inicio-btn {
            background-color: #07e3bb !important; /* Color de fondo */
            border-color: #07e3bb !important; /* Color del borde */
            color: black !important; /* Color del texto */
        }
        .inicio-btn:hover {
            background-color: #06c6a8 !important; /* Color al pasar el cursor */
            border-color: #06c6a8 !important; /* Color del borde al pasar el cursor */
            color: white !important; /* Color del texto al pasar el cursor */
        }
        .inicio-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(7, 227, 187, 0.5) !important; /* Efecto de foco */
        }
        /* Cambiar el color de todas las letras dentro de la página de inicio a blanco */
        body, body * {
            color: white !important;
        }
        body {
            background-color: #272230 !important;
            color: white !important;
        }
        .form-control {
            background-color: #272230 !important;
            color: white !important;
            border: 1px solid #444 !important;
        }
        .form-control::placeholder {
            color: #bbb !important;
        }
        .ui-autocomplete {
            background-color: #272230 !important; /* Fondo de la lista de autocompletado */
            color: white !important; /* Color del texto de la lista de autocompletado */
            border: 1px solid #444 !important;
        }
        .ui-menu-item-wrapper {
            color: white !important; /* Color del texto de los elementos de la lista */
        }
        .user-image {
            width: 300px; /* Ancho de la imagen */
            height: auto; /* Altura automática para mantener la proporción */
        }
        /* Estilo personalizado para el modal */
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
</body>
</html>

<?php
$conn->close();
?>