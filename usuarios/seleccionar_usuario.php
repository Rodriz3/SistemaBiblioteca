<?php include 'header_usuarios.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Seleccionar Usuario</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="seleccionar_usuario.php" class="form-inline mb-3">
            <input type="text" name="search_term" class="form-control mr-2" placeholder="nombre, apellido o DNI" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
            <button type="submit" class="btn btn-primary buscar-btn">Buscar</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Categoría</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Número de Socio</th>
                    <th>Tipo de Usuario</th>
                    <th>Dirección</th>
                    <th>Género</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <?php
                // Conexión a la base de datos
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "biblioteca";

                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtener el término de búsqueda si existe
                $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

                // Consulta a la base de datos
                $sql = "SELECT id, nombre, apellido, dni, categoria, correo, telefono, numero_de_socio, tipo_usuario, direccion, genero, password FROM usuarios";
                if (!empty($search_term)) {
                    $sql .= " WHERE nombre LIKE '%$search_term%' OR apellido LIKE '%$search_term%' OR dni LIKE '%$search_term%'";
                }
                $sql .= " ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>
                                <button class='btn btn-info' onclick='openEditModal(" . json_encode($row) . ")'>Editar</button>
                              </td>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['apellido'] . "</td>";
                        echo "<td>" . $row['dni'] . "</td>";
                        echo "<td>" . $row['categoria'] . "</td>";
                        echo "<td>" . $row['correo'] . "</td>";
                        echo "<td>" . $row['telefono'] . "</td>";
                        echo "<td>" . $row['numero_de_socio'] . "</td>";
                        echo "<td>" . $row['tipo_usuario'] . "</td>";
                        echo "<td>" . $row['direccion'] . "</td>";
                        echo "<td>" . $row['genero'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No hay usuarios disponibles</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="editar_usuario.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="edit_user_id" id="edit_user_id">
                        <div class="form-group">
                            <label for="edit_password">Contraseña</label>
                            <input type="text" class="form-control" id="edit_password" name="edit_password" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_nombre">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_apellido">Apellido</label>
                            <input type="text" class="form-control" id="edit_apellido" name="edit_apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_dni">DNI</label>
                            <input type="text" class="form-control" id="edit_dni" name="edit_dni" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_categoria">Categoría</label>
                            <input type="text" class="form-control" id="edit_categoria" name="edit_categoria" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_correo">Correo</label>
                            <input type="email" class="form-control" id="edit_correo" name="edit_correo" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_telefono">Teléfono</label>
                            <input type="text" class="form-control" id="edit_telefono" name="edit_telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_numero_de_socio">Número de Socio</label>
                            <input type="text" class="form-control" id="edit_numero_de_socio" name="edit_numero_de_socio" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tipo_usuario">Tipo de Usuario</label>
                            <input type="text" class="form-control" id="edit_tipo_usuario" name="edit_tipo_usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_direccion">Dirección</label>
                            <input type="text" class="form-control" id="edit_direccion" name="edit_direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_genero">Género</label>
                            <input type="text" class="form-control" id="edit_genero" name="edit_genero" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary buscar-btn">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openEditModal(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_password').value = user.password;
            document.getElementById('edit_nombre').value = user.nombre;
            document.getElementById('edit_apellido').value = user.apellido;
            document.getElementById('edit_dni').value = user.dni;
            document.getElementById('edit_categoria').value = user.categoria;
            document.getElementById('edit_correo').value = user.correo;
            document.getElementById('edit_telefono').value = user.telefono;
            document.getElementById('edit_numero_de_socio').value = user.numero_de_socio;
            document.getElementById('edit_tipo_usuario').value = user.tipo_usuario;
            document.getElementById('edit_direccion').value = user.direccion;
            document.getElementById('edit_genero').value = user.genero;
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>