<?php
// Incluir conexión a la base de datos
include '../db_connection.php';
include 'header_dashboard.php';

// Consultar la tabla 'libros'
$sql = "SELECT categoria, COUNT(*) as cantidad FROM libros GROUP BY categoria";
$result = $conn->query($sql);

// Inicializar variables para almacenar datos
$categorias = [];
$cantidades = [];

if ($result->num_rows > 0) {
    // Extraer datos
    while($row = $result->fetch_assoc()) {
        $categorias[] = $row['categoria'];
        $cantidades[] = $row['cantidad'];
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Libros</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Librería Chart.js -->
    <style>
        .chart-container {
            width: 300px;
            height: 150px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Dashboard de Libros</h1>
    <div class="chart-container">
        <canvas id="barChart"></canvas> <!-- Lienzo del gráfico de barras -->
    </div>
    <div class="chart-container">
        <canvas id="pieChart"></canvas> <!-- Lienzo del gráfico de pastel -->
    </div>

    <script>
        // Pasar los datos de PHP a JavaScript
        var categorias = <?php echo json_encode($categorias); ?>;
        var cantidades = <?php echo json_encode($cantidades); ?>;

        // Crear gráfico de barras con Chart.js
        var ctxBar = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctxBar, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: categorias, // Etiquetas para el gráfico (categorías)
                datasets: [{
                    label: '# de libros por categoría',
                    data: cantidades, // Datos (cantidad de libros por categoría)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                    borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                    borderWidth: 1 // Grosor del borde
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Comenzar el eje Y en 0
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });

        // Crear gráfico de pastel con Chart.js
        var ctxPie = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie', // Tipo de gráfico
            data: {
                labels: categorias, // Etiquetas para el gráfico (categorías)
                datasets: [{
                    label: '# de libros por categoría',
                    data: cantidades, // Datos (cantidad de libros por categoría)
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
                    borderWidth: 1 // Grosor del borde
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>