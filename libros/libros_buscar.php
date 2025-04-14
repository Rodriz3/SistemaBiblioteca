<?php include '../db_connection.php'; ?>
<?php include 'header_libros.php'; ?>

<?php
// Consulta a la base de datos para obtener los datos de la columna ESTADO
$sql = "SELECT ESTADO, COUNT(*) as count FROM libros GROUP BY ESTADO";
$result = $conn->query($sql);

$estados = [];
$counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['ESTADO'] !== null && $row['ESTADO'] !== '') {
            $estados[] = $row['ESTADO'];
            $counts[] = $row['count'];
        }
    }
}

// Obtener lista de libros
$sql = "SELECT ID, TITULO, AUTOR FROM libros";
$result = $conn->query($sql);
$libros = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $libros[] = [
            'id' => $row['ID'],
            'titulo' => $row['TITULO'],
            'autor' => $row['AUTOR']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Libro y Gráfico</title>
    <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            max-width: 300px;
            max-height: 300px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 text-center">
                <h4 class="mb-4">Estados de los libros</h4>
                <div class="chart-container">
                    <canvas id="estadoChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="text-center">Buscar Libro</h2>
                <form id="libroForm" method="get" action="libro_editar.php">
                    <div class="form-group">
                        <label for="libro_titulo">Libro (Título o Autor):</label>
                        <input type="text" id="libro_titulo" name="libro_titulo" class="form-control" required>
                    </div>
                    <input type="hidden" id="libro_id" name="libro_id" required>
                    <div class="form-group">
                        <label for="accion">Acción:</label>
                        <select id="accion" name="accion" class="form-control" required>
                            <option value="editar">Editar</option>
                            <option value="eliminar">Eliminar</option>
                            <option value="duplicar">Duplicar</option>
                            <option value="modificar_estado">Modificar Estado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary buscar-btn buscar-btn">Buscar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery UI Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $(function() {
        var libros = <?php echo json_encode($libros); ?>;
        $("#libro_titulo").autocomplete({
            source: libros.map(function(libro) {
                return {
                    label: libro.titulo + " - " + libro.autor,
                    value: libro.id
                };
            }),
            select: function(event, ui) {
                $("#libro_titulo").val(ui.item.label);
                $("#libro_id").val(ui.item.value);
                return false;
            }
        });

        $("#libroForm").on("submit", function(event) {
            event.preventDefault();
            var accion = $("#accion").val();
            var formAction;
            switch (accion) {
                case "editar":
                    formAction = "libro_editar.php";
                    break;
                case "eliminar":
                    formAction = "libro_eliminar.php";
                    break;
                case "duplicar":
                    formAction = "libro_duplicar.php";
                    break;
                case "modificar_estado":
                    formAction = "libro_estado.php";
                    break;
            }
            $(this).attr("action", formAction + "?libro_id=" + $("#libro_id").val());
            this.submit();
        });

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