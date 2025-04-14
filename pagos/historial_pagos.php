<?php include 'header_pagos.php'; ?>
<?php
include '../db_connection.php';

// Obtener los términos de búsqueda
$search_option = isset($_GET['search_option']) ? $_GET['search_option'] : '';
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Consulta a la base de datos
$sql = "SELECT p.id, CONCAT(u.dni, ' - ', u.nombre, ' ', u.apellido) AS USUARIO, p.fecha, p.fecha_inicio, p.fecha_fin, p.metodo_pago, p.recibio_pago 
        FROM pagos p
        JOIN usuarios u ON p.usuario_id = u.id";
if (!empty($search_option) && !empty($search_term)) {
    if ($search_option == 'usuario') {
        $sql .= " WHERE u.dni LIKE '%$search_term%' OR u.nombre LIKE '%$search_term%' OR u.apellido LIKE '%$search_term%'";
    }
}
$sql .= " ORDER BY p.id DESC LIMIT 400"; // Ordenar por id de mayor a menor y limitar a 400 filas
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
    <title>Historial de Pagos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <h1 class="mb-4">Historial de Pagos</h1>
        <div class="d-flex justify-content-between mb-3">
            <form method="GET" action="historial_pagos.php" class="search-form">
                <select name="search_option" class="form-control" required>
                    <option value="">Seleccionar opción</option>
                    <option value="usuario" <?php echo isset($_GET['search_option']) && $_GET['search_option'] == 'usuario' ? 'selected' : ''; ?>>Buscar por usuario</option>
                </select>
                <input type="text" name="search_term" class="form-control" placeholder="Ingresar término de búsqueda" value="<?php echo isset($_GET['search_term']) ? $_GET['search_term'] : ''; ?>" required>
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario (DNI - Nombre Apellido)</th>
                        <th>Fecha</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Método de Pago</th>
                        <th>Recibió Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Salida de datos de cada fila
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["USUARIO"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["fecha"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["fecha_inicio"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["fecha_fin"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["metodo_pago"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["recibio_pago"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No hay resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>