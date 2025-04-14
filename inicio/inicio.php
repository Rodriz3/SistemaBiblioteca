<?php
session_start();
include '../header.php';
include '../db_connection.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario que ha iniciado sesión
$usuario_id_sesion = $_SESSION['usuario_id'];

// Consulta a la base de datos para obtener el nombre del usuario que ha iniciado sesión
$sql_sesion = "SELECT nombre FROM usuarios WHERE id = ?";
$stmt_sesion = $conn->prepare($sql_sesion);
if ($stmt_sesion === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt_sesion->bind_param("i", $usuario_id_sesion);
$stmt_sesion->execute();
$result_sesion = $stmt_sesion->get_result();
$user_data_sesion = $result_sesion->fetch_assoc();
$nombre_usuario = $user_data_sesion['nombre'] ?? '';

$stmt_sesion->close();

// Consulta a la base de datos para obtener los datos de la columna ESTADO
$sql = "SELECT ESTADO, COUNT(*) as count FROM libros GROUP BY ESTADO";
$result = $conn->query($sql);

$disponibles = 0;
$otros = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['ESTADO'] === 'Disponible') {
            $disponibles = $row['count'];
        } else {
            $otros += $row['count'];
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Incluir Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Estilo personalizado para los botones específicos de inicio */
        .inicio-btn {
            background-color: #07e3bb !important; /* Color de fondo */
            border-color: #07e3bb !important; /* Color del borde */
            color: black !important; /* Color del texto */
        }
        .inicio-btn:hover {
            background-color: #06c6a8 !important; /* Color al pasar el cursor */
            border-color: #06c6a8 !important; /* Color del borde al pasar el cursor */
            color: white !important; /* Color del texto al pasar el cursor */
        }
        .inicio-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(7, 227, 187, 0.5) !important; /* Efecto de foco */
        }
        /* Cambiar el color de todas las letras dentro de la página de inicio a blanco */
        body, body * {
            color: white !important;
        }
        body {
            background-color: #272230 !important;
            color: white !important;
        }
        .form-control {
            background-color: #272230 !important;
            color: white !important;
            border: 1px solid #444 !important;
        }
        .form-control::placeholder {
            color: #bbb !important;
        }
        .ui-autocomplete {
            background-color: #272230 !important; /* Fondo de la lista de autocompletado */
            color: white !important; /* Color del texto de la lista de autocompletado */
            border: 1px solid #444 !important;
        }
        .ui-menu-item-wrapper {
            color: white !important; /* Color del texto de los elementos de la lista */
        }
        .user-image {
            width: 300px; /* Ancho de la imagen */
            height: auto; /* Altura automática para mantener la proporción */
        }
        .chart-container {
            max-width: 300px;
            max-height: 300px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1 class="mb-4">BIBLIOTECA POPULAR ENCUENTROS</h1>
        <h2 class="mb-4">Hola <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
        <!-- Agregar la imagen después del header -->
        <img src="../png/logo.png" alt="User Image" class="user-image">
        <?php
        // Leer los datos del archivo JSON
        $data = json_decode(file_get_contents('inicio_data.json'), true);
        ?>
        <p><strong><i class="fas fa-map-marker-alt"></i> Ubicación:</strong> <?php echo htmlspecialchars($data['ubicacion']); ?></p>
        <p><strong><i class="fas fa-phone"></i> Número:</strong> <?php echo htmlspecialchars($data['numero']); ?></p>
        <p><strong><i class="fab fa-instagram"></i> Instagram:</strong> <a href="https://instagram.com/<?php echo htmlspecialchars($data['instagram']); ?>" target="_blank"><?php echo htmlspecialchars($data['instagram']); ?></a></p>
        <p><strong><i class="fab fa-facebook"></i> Facebook:</strong> <a href="https://facebook.com/<?php echo htmlspecialchars($data['facebook']); ?>" target="_blank"><?php echo htmlspecialchars($data['facebook']); ?></a></p>
        <p><strong><i class="fas fa-envelope"></i> Mail:</strong> <?php echo htmlspecialchars($data['mail']); ?></p>
        <p><strong><i class="fas fa-clock"></i> Horarios:</strong> <?php echo htmlspecialchars($data['horarios']); ?></p>

        <div class="row">
            <div class="col-md-6">
                <!-- Sección de Libros Recomendados -->
                <h3 class="mt-5">LIBROS RECOMENDADOS</h3>
                <ul class="list-unstyled">
                    <?php foreach ($data['libros_recomendados'] as $libro): ?>
                        <?php if (!empty($libro)): ?>
                            <li><i class="fas fa-book"></i> <?php echo htmlspecialchars($libro); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-6">
                <!-- Gráfico de Estados de los Libros -->
                <h4 class="mb-4" style="margin-top: 50px;">!<?php echo $disponibles; ?> LIBROS DISPONIBLES¡</h4>
                <div class="chart-container">
                    <canvas id="estadoChart"></canvas>
                </div>
            </div>
        </div>

        <?php if ($es_administrador): ?>
            <a href="admin_inicio.php" class="btn inicio-btn mt-3"><i class="fas fa-cog"></i> Configurar Inicio</a>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('estadoChart').getContext('2d');
        var estadoChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Otros'],
                datasets: [{
                    label: 'Estados',
                    data: [<?php echo $disponibles; ?>, <?php echo $otros; ?>],
                    backgroundColor: [
                        'rgba(84, 242, 242, 0.5)', // #54F2F2 con opacidad
                        'rgba(186, 61, 167, 0.5)'  // #BA3DA7 con opacidad
                    ],
                    borderColor: [
                        'rgba(84, 242, 242, 1)', // #54F2F2 sin opacidad
                        'rgba(186, 61, 167, 1)'  // #BA3DA7 sin opacidad
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