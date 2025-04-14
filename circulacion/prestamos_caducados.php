<?php include '../db_connection.php'; ?>
<?php include 'header_circulacion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Caducados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .fecha-roja {
            color: red; /* Color rojo para la fecha de vencimiento */
        }
        .table {
            background-color: #272230;
            color: white;
        }
        .table-striped tbody tr {
            background-color: #272230 !important; /* Color de fondo para todas las filas */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .table thead th {
            color: white;
        }
        .table td, .table th {
            border-color: #272230; /* Color del borde de la tabla */
        }
    </style>
</head>
<body class="lexend-custom">
<div class="container mt-5">
    <h1 class="text-center mb-4">Préstamos Caducados</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título del Libro</th>
                    <th>Autor del Libro</th>
                    <th>Apellido del Usuario</th>
                    <th>DNI del Usuario</th>
                    <th>Teléfono del Usuario</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener la fecha actual
                $fecha_actual = date('Y-m-d');

                // Consulta para obtener los préstamos caducados
                $sql = "SELECT libros.titulo, libros.autor, usuarios.apellido, usuarios.dni, usuarios.telefono, prestamos.vencimiento 
                        FROM prestamos 
                        JOIN libros ON prestamos.id_libro = libros.id 
                        JOIN usuarios ON prestamos.id_usuario = usuarios.id 
                        WHERE prestamos.vencimiento < ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die("Error en la preparación de la consulta: " . $conn->error);
                }
                $stmt->bind_param("s", $fecha_actual);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dni']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                        echo "<td class='fecha-roja'>" . htmlspecialchars($row['vencimiento']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay préstamos caducados</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>