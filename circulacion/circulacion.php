<?php include 'header_circulacion.php'; ?>
<?php include '../db_connection.php'; ?>

<main class="container mt-5">
    <?php
    // Consulta a la base de datos para obtener los datos de préstamos vigentes y vencidos
    $sql = "SELECT 
                SUM(CASE WHEN vencimiento >= CURDATE() THEN 1 ELSE 0 END) AS vigentes,
                SUM(CASE WHEN vencimiento < CURDATE() THEN 1 ELSE 0 END) AS vencidos
            FROM prestamos";
    $result = $conn->query($sql);

    $vigentes = 0;
    $vencidos = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $vigentes = $row['vigentes'];
        $vencidos = $row['vencidos'];
    }
    ?>

    <div class="container mt-5 text-center">
        <h4 class="mb-4">Préstamos</h4>
        <div class="chart-container" style="width: 400px; height: 400px; margin: 0 auto;">
            <canvas id="prestamosChart"></canvas>
        </div>
    </div>

    <h1 class="mt-5">Libros en Circulación</h1>

    <?php
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
                echo "<div class='alert alert-success'>Libro devuelto exitosamente y registrado en el historial de préstamos.</div>";
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
    <form method="GET" action="circulacion.php" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Buscar por título, nombre, apellido o DNI" value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit" class="btn btn-primary buscar-btn buscar-btn">Buscar</button>
    </form>

    <!-- Mostrar la lista de libros prestados -->
    <?php if ($libros_result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
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

<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('prestamosChart').getContext('2d');
        var prestamosChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Vigentes', 'Vencidos'],
                datasets: [{
                    label: 'Préstamos',
                    data: [<?php echo $vigentes; ?>, <?php echo $vencidos; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
</body>
<style>
        .chart-container {
            max-width: 300px;
            max-height: 300px;
            margin: 0 auto;
        }
    </style>
</html>