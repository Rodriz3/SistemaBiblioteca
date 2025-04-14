<?php include 'header_circulacion.php'; ?>
<main class="container mt-5">
    <h1>Devolución de Libros</h1>

    <?php
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root"; // Cambia esto por tu nombre de usuario
    $password = ""; // Cambia esto por tu contraseña correcta
    $dbname = "biblioteca";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si se ha enviado el formulario de devolución
    if (isset($_POST['devolver'])) {
        $prestamo_id = $_POST['prestamo_id'];

        // Obtener información del préstamo antes de eliminarlo
        $info_sql = "SELECT id_libro, id_usuario, vencimiento FROM prestamos WHERE id = ?";
        $stmt = $conn->prepare($info_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $prestamo_id);
        $stmt->execute();
        $info_result = $stmt->get_result();
        $info = $info_result->fetch_assoc();

        // Eliminar la fila correspondiente de la tabla prestamos
        $devolver_sql = "DELETE FROM prestamos WHERE id = ?";
        $stmt = $conn->prepare($devolver_sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $prestamo_id);
        if ($stmt->execute()) {
            // Registrar la devolución en el historial de préstamos
            $historial_sql = "INSERT INTO historial_prestamos (libro_id, usuario_id, fecha, accion, vencimiento) VALUES (?, ?, NOW(), 'Devolución', ?)";
            $stmt = $conn->prepare($historial_sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("iis", $info['id_libro'], $info['id_usuario'], $info['vencimiento']);
            if ($stmt->execute()) {
                // Actualizar el estado del libro a "Disponible"
                $update_estado_sql = "UPDATE libros SET ESTADO = 'Disponible' WHERE ID = ?";
                $stmt = $conn->prepare($update_estado_sql);
                if ($stmt === false) {
                    die("Error en la preparación de la consulta: " . $conn->error);
                }
                $stmt->bind_param("i", $info['id_libro']);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Libro devuelto exitosamente y registrado en el historial de préstamos.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar el estado del libro: " . $stmt->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error al registrar la devolución en el historial: " . $stmt->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error al devolver el libro: " . $stmt->error . "</div>";
        }
    }

    // Verificar si se ha enviado el formulario de búsqueda
    $search_term = '';
    if (isset($_GET['search'])) {
        $search_term = $_GET['search'];
    }

    // Consulta SQL para obtener los libros prestados y los datos del usuario asociado
    $libros_sql = "SELECT prestamos.id AS prestamo_id, libros.titulo, libros.autor, prestamos.vencimiento, 
                          usuarios.nombre, usuarios.apellido, usuarios.dni 
                   FROM prestamos 
                   JOIN libros ON prestamos.id_libro = libros.id 
                   JOIN usuarios ON prestamos.id_usuario = usuarios.id
                   WHERE libros.titulo LIKE ? OR usuarios.nombre LIKE ? OR usuarios.apellido LIKE ? OR usuarios.dni LIKE ?";
    $stmt = $conn->prepare($libros_sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $search_term_like = '%' . $search_term . '%';
    $stmt->bind_param("ssss", $search_term_like, $search_term_like, $search_term_like, $search_term_like);
    $stmt->execute();
    $libros_result = $stmt->get_result();
    if ($libros_result === false) {
        die("Error en la consulta: " . $conn->error);
    }
    ?>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="devolucion.php" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Buscar por título, nombre, apellido o DNI" value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit" class="btn btn-primary buscar-btn buscar-btn">Buscar</button>
    </form>

    <!-- Mostrar la lista de libros prestados -->
    <?php if ($libros_result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Vencimiento</th>
                        <th>Nombre del Usuario</th>
                        <th>Apellido del Usuario</th>
                        <th>DNI del Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $libros_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <form method="POST" action="devolucion.php" class="d-inline">
                                    <input type="hidden" name="prestamo_id" value="<?php echo htmlspecialchars($row['prestamo_id']); ?>">
                                    <button type="submit" name="devolver" class="btn btn-primary buscar-btn buscar-btn">Devolver</button>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($row['autor']); ?></td>
                            <td><?php echo htmlspecialchars($row['vencimiento']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['dni']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron libros prestados.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</main>
<!-- Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>