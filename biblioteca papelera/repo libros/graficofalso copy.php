<?php
// Incluir conexión a la base de datos
include '../db_connection.php';

// Consultar la tabla 'librosfalsos' para obtener los autores y sus cantidades
$sql_falsos = "SELECT autor, COUNT(*) as cantidad FROM librosfalsos GROUP BY autor ORDER BY cantidad DESC";
$result_falsos = $conn->query($sql_falsos);

// Inicializar variables para almacenar datos de librosfalsos
$autores = [];
$cantidades_falsos = [];

if ($result_falsos->num_rows > 0) {
    // Extraer datos
    while($row = $result_falsos->fetch_assoc()) {
        $autores[] = $row['autor'];
        $cantidades_falsos[] = $row['cantidad'];
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
    <title>Libros por Autor</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Librería Chart.js -->
    <style>
        .chart-container {
            width: 80%; /* Ajustar el ancho del contenedor */
            height: 400px; /* Ajustar la altura del contenedor */
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Libros por Autor</h1>
    <div class="chart-container">
        <canvas id="barChartFalsos"></canvas> <!-- Lienzo del gráfico de barras para librosfalsos -->
    </div>

    <script>
        // Pasar los datos de PHP a JavaScript
        var autores = <?php echo json_encode($autores); ?>;
        var cantidades_falsos = <?php echo json_encode($cantidades_falsos); ?>;

        // Crear gráfico de barras horizontal con Chart.js para librosfalsos
        var ctxBarFalsos = document.getElementById('barChartFalsos').getContext('2d');
        var barChartFalsos = new Chart(ctxBarFalsos, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: autores, // Etiquetas para el gráfico (autores)
                datasets: [{
                    label: '# de libros por autor',
                    data: cantidades_falsos, // Datos (cantidad de libros falsos por autor)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                    borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                    borderWidth: 1 // Grosor del borde
                }]
            },
            options: {
                indexAxis: 'y', // Cambiar el eje de las barras a horizontal
                scales: {
                    x: {
                        beginAtZero: true // Comenzar el eje X en 0
                    }
                },
                plugins: {
                    legend: {
                        display: false // Ocultar la leyenda
                    },
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
    </script>
</body>
</html>