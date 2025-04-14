<?php
include 'header_libros.php';
include '../db_connection.php';

// Consulta a la base de datos para obtener los datos de la columna ESTADO
$sql = "SELECT ESTADO, COUNT(*) as count FROM libros GROUP BY ESTADO";
$result = $conn->query($sql);

$estados = [];
$counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estados[] = $row['ESTADO'];
        $counts[] = $row['count'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos</title>
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Limitar el tamaño máximo del gráfico */
        .chart-container {
            max-width: 300px;
            max-height: 300px;
            margin: 0 auto; /* Centrar el gráfico */
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5 text-center">
        <h4 class="mb-4">Gráficos</h4>
        <div class="chart-container">
            <canvas id="estadoChart"></canvas>
        </div>
    </div>
    <!-- Script para generar el gráfico -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('estadoChart').getContext('2d');
            var estadoChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($estados); ?>,
                    datasets: [{
                        label: 'Estados',
                        data: <?php echo json_encode($counts); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
</html>
