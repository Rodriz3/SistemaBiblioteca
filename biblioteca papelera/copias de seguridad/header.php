<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
$usuario_iniciado = isset($_SESSION['usuario_id']);
$es_administrador = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador';

if (!$usuario_iniciado) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sitio Web</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
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
<header class="bg-primary text-white p-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Biblioteca Oro Verde</h1>
            <nav class="top-menu">
                <?php if ($usuario_iniciado && !$es_administrador): ?>
                    <a href="libros_2.php" class="btn btn-primary buscar-btn text-white">Libros</a>
                    <a href="datos_personales.php" class="btn btn-primary buscar-btn text-white">Datos Personales</a>
                <?php endif; ?>
                <?php if ($es_administrador): ?>
                    <a href="/biblioteca/pagos/pagar_cuota.php" class="btn btn-primary buscar-btn text-white">Pagos</a>
                    <a href="/biblioteca/circulacion/circulacion.php" class="btn btn-primary buscar-btn text-white">Circulación</a>
                    <a href="/biblioteca/libros/libros.php" class="btn btn-primary buscar-btn text-white">Libros</a>
                    <a href="/biblioteca/usuarios/usuarios.php" class="btn btn-primary buscar-btn text-white">Usuarios</a>
                    <a href="/biblioteca/comentarios_aprobar.php" class="btn btn-primary buscar-btn text-white">Comentarios</a>
                <?php endif; ?>
                <a href="/biblioteca/logout.php" class="btn btn-primary buscar-btn text-white">X</a>
            </nav>
        </div>
    </div>
</header>
<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>