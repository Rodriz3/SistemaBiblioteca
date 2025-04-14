<?php include '../db_connection.php'; ?>
<?php include 'header_pagos.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios con Cuotas Atrasadas</title>
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
    <h1 class="text-center mb-4">Usuarios con Cuotas Atrasadas</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener la fecha actual
                $fecha_actual = date('Y-m-d');

                // Consulta para obtener los usuarios con cuotas atrasadas
                $sql = "SELECT usuarios.nombre, usuarios.apellido, usuarios.dni, pagos.fecha_inicio, pagos.fecha_fin 
                        FROM pagos 
                        JOIN usuarios ON pagos.usuario_id = usuarios.id 
                        WHERE pagos.id = (
                            SELECT MAX(p2.id) 
                            FROM pagos p2 
                            WHERE p2.usuario_id = pagos.usuario_id
                        ) AND pagos.fecha_fin < ?";
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
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dni']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_fin']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No hay usuarios con cuotas atrasadas</td></tr>";
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
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>