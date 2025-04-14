<?php include 'header_usuarios.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <main class="container mt-5">
        <h1 class="mb-4">Eliminar Usuario</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="usuario_eliminar.php" class="form-inline mb-3">
            <input type="text" name="search_term" class="form-control mr-2" placeholder="nombre, apellido o DNI" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
            <button type="submit" class="btn btn-primary buscar-btn">Buscar</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Acción</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Categoría</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Número de Socio</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
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

                // Manejar la eliminación del usuario
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $delete_sql = "DELETE FROM usuarios WHERE id = ?";
                    $stmt = $conn->prepare($delete_sql);
                    $stmt->bind_param("i", $delete_id);
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Usuario eliminado correctamente.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error al eliminar el usuario.</div>";
                    }
                    $stmt->close();
                }

                // Obtener el término de búsqueda si existe
                $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

                // Consulta a la base de datos
                $sql = "SELECT id, nombre, apellido, dni, categoria, correo, telefono, numero_de_socio FROM usuarios";
                if (!empty($search_term)) {
                    $sql .= " WHERE nombre LIKE '%$search_term%' OR apellido LIKE '%$search_term%' OR dni LIKE '%$search_term%'";
                }
                $sql .= " ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result === false) {
                    die("Error en la consulta: " . $conn->error);
                }

                if ($result->num_rows > 0) {
                    // Salida de datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><button class='btn btn-danger' data-id='" . $row["id"] . "' onclick='confirmDelete(this)'>Eliminar</button></td>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["nombre"] . "</td>";
                        echo "<td>" . $row["apellido"] . "</td>";
                        echo "<td>" . $row["dni"] . "</td>";
                        echo "<td>" . $row["categoria"] . "</td>";
                        echo "<td>" . $row["correo"] . "</td>";
                        echo "<td>" . $row["telefono"] . "</td>";
                        echo "<td>" . $row["numero_de_socio"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No hay resultados</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </main>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que quiere eliminar a este socio?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var userIdToDelete;

        function confirmDelete(button) {
            userIdToDelete = $(button).data('id');
            $('#confirmDeleteModal').modal('show');
        }

        $('#confirmDeleteButton').click(function() {
            window.location.href = 'usuario_eliminar.php?delete_id=' + userIdToDelete;
        });
    </script>
</body>
</html>