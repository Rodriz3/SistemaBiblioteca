<?php
// Incluir conexión a la base de datos
include '../db_connection.php';

// Consultar la tabla 'librosfalsos' para obtener los géneros y sus cantidades
$sql_falsos = "SELECT genero, COUNT(*) as cantidad FROM librosfalsos GROUP BY genero ORDER BY cantidad DESC";
$result_falsos = $conn->query($sql_falsos);

// Inicializar variables para almacenar datos de librosfalsos
$generos = [];
$cantidades_falsos = [];
$total_cantidad = 0;

if ($result_falsos->num_rows > 0) {
    // Extraer datos
    while($row = $result_falsos->fetch_assoc()) {
        $generos[] = $row['genero'];
        $cantidades_falsos[] = $row['cantidad'];
        $total_cantidad += $row['cantidad'];
    }
} else {
    echo "0 resultados";
}

// Consultar la tabla 'librosfalsos' para obtener los estados
$sql_estados = "SELECT estado, COUNT(*) as cantidad FROM librosfalsos GROUP BY estado";
$result_estados = $conn->query($sql_estados);

// Inicializar variables para almacenar datos de estados
$estados = [];
$cantidades_estados = [];
$total_cantidad_estados = 0;

if ($result_estados->num_rows > 0) {
    // Extraer datos
    while($row = $result_estados->fetch_assoc()) {
        $estados[] = $row['estado'];
        $cantidades_estados[] = $row['cantidad'];
        $total_cantidad_estados += $row['cantidad'];
    }
} else {
    echo "0 resultados";
}

// Consultar la tabla 'librosfalsos' para obtener los autores y sus cantidades
$sql_autores = "SELECT autor, COUNT(*) as cantidad FROM librosfalsos GROUP BY autor ORDER BY cantidad DESC";
$result_autores = $conn->query($sql_autores);

// Inicializar variables para almacenar datos de librosfalsos por autor
$autores = [];
$cantidades_autores = [];

if ($result_autores->num_rows > 0) {
    // Extraer datos
    while($row = $result_autores->fetch_assoc()) {
        $autores[] = $row['autor'];
        $cantidades_autores[] = $row['cantidad'];
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
    <title>Dashboard de Libros Falsos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Librería Chart.js -->
    <style>
        .chart-container {
            width: 30%; /* Ajustar el ancho del contenedor */
            height: 400px; /* Ajustar la altura del contenedor */
            display: inline-block; /* Mostrar los contenedores en línea */
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <canvas id="pieChartFalsos"></canvas> <!-- Lienzo del gráfico de pastel para librosfalsos -->
    </div>
    <div class="chart-container">
        <canvas id="doughnutChartEstados"></canvas> <!-- Lienzo del gráfico de rosca para estados -->
    </div>
    <div class="chart-container">
        <canvas id="barChartFalsos"></canvas> <!-- Lienzo del gráfico de barras para librosfalsos -->
    </div>

    <script>
        // Pasar los datos de PHP a JavaScript
        var generos = <?php echo json_encode($generos); ?>;
        var cantidades_falsos = <?php echo json_encode($cantidades_falsos); ?>;
        var total_cantidad = <?php echo $total_cantidad; ?>;

        // Crear gráfico de pastel con Chart.js para librosfalsos
        var ctxPieFalsos = document.getElementById('pieChartFalsos').getContext('2d');
        var pieChartFalsos = new Chart(ctxPieFalsos, {
            type: 'pie', // Tipo de gráfico
            data: {
                labels: generos, // Etiquetas para el gráfico (géneros)
                datasets: [{
                    label: '# de libros falsos por género',
                    data: cantidades_falsos, // Datos (cantidad de libros falsos por género)
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
                    title: {
                        display: true,
                        text: 'Libros por Categoría'
                    },
                    legend: {
                        position: 'right', // Posicionar la leyenda a la derecha
                        labels: {
                            boxWidth: 20, // Ancho de la caja de color en la leyenda
                            font: {
                                size: 14 // Tamaño de la fuente de la leyenda
                            },
                            padding: 20 // Espaciado entre las etiquetas de la leyenda
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw;
                                var percentage = ((value / total_cantidad) * 100).toFixed(2) + '%';
                                return label + ': ' + percentage;
                            }
                        }
                    }
                }
            }
        });

        // Pasar los datos de PHP a JavaScript para estados
        var estados = <?php echo json_encode($estados); ?>;
        var cantidades_estados = <?php echo json_encode($cantidades_estados); ?>;
        var total_cantidad_estados = <?php echo $total_cantidad_estados; ?>;

        // Crear gráfico de rosca con Chart.js para estados
        var ctxDoughnutEstados = document.getElementById('doughnutChartEstados').getContext('2d');
        var doughnutChartEstados = new Chart(ctxDoughnutEstados, {
            type: 'doughnut', // Tipo de gráfico
            data: {
                labels: estados, // Etiquetas para el gráfico (estados)
                datasets: [{
                    label: '# de libros por estado',
                    data: cantidades_estados, // Datos (cantidad de libros por estado)
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
                    title: {
                        display: true,
                        text: 'Libros por Estado'
                    },
                    legend: {
                        position: 'right', // Posicionar la leyenda a la derecha
                        labels: {
                            boxWidth: 20, // Ancho de la caja de color en la leyenda
                            font: {
                                size: 14 // Tamaño de la fuente de la leyenda
                            },
                            padding: 20 // Espaciado entre las etiquetas de la leyenda
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw;
                                var percentage = ((value / total_cantidad_estados) * 100).toFixed(2) + '%';
                                return label + ': ' + percentage;
                            }
                        }
                    }
                }
            }
        });

        // Pasar los datos de PHP a JavaScript para autores
        var autores = <?php echo json_encode($autores); ?>;
        var cantidades_autores = <?php echo json_encode($cantidades_autores); ?>;

        // Crear gráfico de barras horizontal con Chart.js para librosfalsos por autor
        var ctxBarFalsos = document.getElementById('barChartFalsos').getContext('2d');
        var barChartFalsos = new Chart(ctxBarFalsos, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: autores, // Etiquetas para el gráfico (autores)
                datasets: [{
                    label: '# de libros por autor',
                    data: cantidades_autores, // Datos (cantidad de libros falsos por autor)
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
                    title: {
                        display: true,
                        text: 'Autores Más Pedidos'
                    },
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