<?php
// Incluir el archivo de conexión a la base de datos
include '../db_connection.php';

// Manejar la búsqueda de usuarios para autocompletado
if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $sql = "SELECT id, CONCAT(nombre, ' ', apellido, ' (DNI: ', dni, ', Socio: ', numero_de_socio, ')') AS label FROM usuarios WHERE nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? OR numero_de_socio LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $term = '%' . $term . '%';
    $stmt->bind_param("ssss", $term, $term, $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = ['value' => $row['id'], 'label' => $row['label']];
    }

    echo json_encode($usuarios);
    exit();
}

include 'header_circulacion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$search_option = isset($_GET['search_option']) ? $_GET['search_option'] : '';
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
$libros_result = null;
$selected_libro = null;
$message = '';

// Consulta a la base de datos
$sql = "SELECT `ID`, `AUTOR`, `TITULO`, `EDITORIAL`, `ISBN`, `ESTADO` FROM libros";
if (!empty($search_option) && !empty($search_term)) {
    if ($search_option == 'autor') {
        $sql .= " WHERE `AUTOR` LIKE ?";
    } elseif ($search_option == 'titulo') {
        $sql .= " WHERE `TITULO` LIKE ?";
    }
}
$sql .= " LIMIT 40"; // Limitar la cantidad de resultados a 40
$stmt = $conn->prepare($sql);
if (!empty($search_option) && !empty($search_term)) {
    $search_term = '%' . $search_term . '%';
    $stmt->bind_param("s", $search_term);
}
$stmt->execute();
$libros_result = $stmt->get_result();

if ($libros_result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Manejar la asignación del préstamo
if (isset($_POST['prestar'])) {
    $libro_id = $_POST['libro_id'];
    $usuario_id = $_POST['usuario_id'];
    $dias_vencimiento = $_POST['dias_vencimiento'];

    // Verificar si ya existe un préstamo con el mismo id_libro e id_usuario
    $check_sql = "SELECT id FROM prestamos WHERE id_libro = ? AND id_usuario = ? AND vencimiento >= NOW()";
    $stmt_check = $conn->prepare($check_sql);
    if ($stmt_check === false) {
        die("Error en la preparación de la consulta (verificación de duplicados): " . $conn->error);
    }
    $stmt_check->bind_param("ii", $libro_id, $usuario_id);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();

    if ($check_result->num_rows > 0) {
        $message = "<div class='alert alert-warning'>Este libro ya ha sido prestado a este usuario y aún no ha vencido.</div>";
    } else {
        // Calcular la fecha de vencimiento
        $fecha_vencimiento = date('Y-m-d', strtotime("+$dias_vencimiento days"));

        // Insertar el préstamo en la tabla prestamos
        $prestar_sql = "INSERT INTO prestamos (id_libro, id_usuario, vencimiento) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($prestar_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("iis", $libro_id, $usuario_id, $fecha_vencimiento);
        if ($stmt->execute()) {
            // Registrar el préstamo en el historial de préstamos
            $accion = 'Préstamo';
            $historial_sql = "INSERT INTO historial_prestamos (libro_id, usuario_id, fecha, accion, vencimiento) VALUES (?, ?, NOW(), ?, ?)";
            $stmt_historial = $conn->prepare($historial_sql);
            if ($stmt_historial === false) {
                die("Error en la preparación de la consulta (historial_sql): " . $conn->error);
            }
            $stmt_historial->bind_param("iiss", $libro_id, $usuario_id, $accion, $fecha_vencimiento);
            if ($stmt_historial->execute()) {
                // Actualizar el estado del libro a "Prestado"
                $update_estado_sql = "UPDATE libros SET ESTADO = 'Prestado' WHERE ID = ?";
                $stmt = $conn->prepare($update_estado_sql);
                if ($stmt === false) {
                    die("Error en la preparación de la consulta (update_estado_sql): " . $conn->error);
                }
                $stmt->bind_param("i", $libro_id);
                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Libro prestado exitosamente.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Error al actualizar el estado del libro: " . $stmt->error . "</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Error al registrar el préstamo en el historial: " . $stmt->error . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Error al prestar el libro: " . $stmt->error . "</div>";
        }
    }

    // Redirigir para evitar la repetición de la acción al recargar la página
    if (!headers_sent()) {
        header("Location: prestamo.php?message=" . urlencode($message));
        exit();
    } else {
        echo "<script type='text/javascript'>window.location.href='prestamo.php?message=" . urlencode($message) . "';</script>";
        exit();
    }
}

// Manejar la selección de un libro para préstamo
if (isset($_POST['seleccionar'])) {
    $libro_id = $_POST['libro_id'];

    $selected_libro_sql = "SELECT libros.id, libros.titulo, libros.autor 
                           FROM libros 
                           WHERE libros.id = ?";
    $stmt = $conn->prepare($selected_libro_sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $libro_id);
    $stmt->execute();
    $selected_libro_result = $stmt->get_result();
    if ($selected_libro_result->num_rows > 0) {
        $selected_libro = $selected_libro_result->fetch_assoc();
    }
}

// Mostrar mensaje si existe
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamo de Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .alert-custom {
            background-color: black;
            color: white;
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <h1>Préstamo de Libros</h1>

        <?php if ($message): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <!-- Formulario para buscar un libro -->
        <form method="GET" action="prestamo.php" class="form-inline mb-3">
            <select name="search_option" class="form-control mr-2" required>
                <option value="">Seleccionar opción</option>
                <option value="autor" <?php echo $search_option == 'autor' ? 'selected' : ''; ?>>Buscar por autor</option>
                <option value="titulo" <?php echo $search_option == 'titulo' ? 'selected' : ''; ?>>Buscar por título</option>
            </select>
            <input type="text" name="search_term" class="form-control mr-2" placeholder="Ingresar término de búsqueda" value="<?php echo $search_term; ?>" required>
            <button type="submit" class="btn btn-primary buscar-btn">Buscar</button>
        </form>

        <?php if ($selected_libro): ?>
            <div class="alert alert-info alert-custom">
                <h4>Libro seleccionado para préstamo:</h4>
                <p><strong>Título:</strong> <?php echo $selected_libro['titulo']; ?></p>
                <p><strong>Autor:</strong> <?php echo $selected_libro['autor']; ?></p>
                <form method="POST" action="prestamo.php">
                    <input type="hidden" name="libro_id" value="<?php echo $selected_libro['id']; ?>">
                    <input type="text" id="usuario_search" class="form-control mr-2" placeholder="Buscar usuario por DNI, apellido o número de socio" required>
                    <input type="hidden" name="usuario_id" id="usuario_id">
                    <select name="dias_vencimiento" class="form-control mr-2" required>
                        <option value="">Seleccionar días de vencimiento</option>
                        <option value="7">7 días</option>
                        <option value="15">15 días</option>
                        <option value="30">30 días</option>
                    </select>
                    <button type="submit" name="prestar" class="btn btn-primary buscar-btn">Prestar</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Autor</th>
                        <th>Título</th>
                        <th>Editorial</th>
                        <th>ISBN</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="prestamos-table-body">
                    <?php
                    if ($libros_result->num_rows > 0) {
                        // Salida de datos de cada fila
                        while($row = $libros_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>";
                            if ($row["ESTADO"] == "Disponible") {
                                echo "<form method='POST' action='prestamo.php'>
                                        <input type='hidden' name='libro_id' value='" . htmlspecialchars($row["ID"]) . "'>
                                        <button type='submit' name='seleccionar' class='btn btn-primary buscar-btn'>Elegir</button>
                                      </form>";
                            } else {
                                echo "<button class='btn btn-secondary' disabled>No Disponible</button>";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["EDITORIAL"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["ISBN"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["ESTADO"]) . "</td>";
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
        <button id="load-more" class="btn btn-secondary">Cargar más</button>
    </div>

    <!-- jQuery y jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#usuario_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "prestamo.php",
                        type: "GET",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#usuario_search").val(ui.item.label);
                    $("#usuario_id").val(ui.item.value);
                    return false;
                }
            });

            var offset = 20;
            $("#load-more").click(function() {
                $.ajax({
                    url: "cargar_prestamos.php",
                    type: "POST",
                    data: {
                        offset: offset,
                        search_option: "<?php echo $search_option; ?>",
                        search_term: "<?php echo $search_term; ?>"
                    },
                    success: function(data) {
                        $("#prestamos-table-body").append(data);
                        offset += 20;
                    }
                });
            });
        });
    </script>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>