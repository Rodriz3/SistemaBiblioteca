<?php
session_start();

// Definir las variables basadas en la sesión del usuario
$usuario_iniciado = isset($_SESSION['usuario_id']);
$es_administrador = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Oro Verde</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .navbar-toggler {
            display: none;
            font-size: 24px;
            color: #fff;
            background: none;
            border: none;
            cursor: pointer;
        }
        .sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            width: 250px;
            height: 100%;
            background-color: #333;
            transition: right 0.3s;
            padding-top: 60px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        .sidebar-menu li {
            padding: 10px 20px;
        }
        .sidebar-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #fff;
            background: none;
            border: none;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .top-menu {
                display: none;
            }
            .navbar-toggler {
                display: block;
            }
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
                <a href="/biblioteca/logout.php" class="btn btn-primary buscar-btn text-white">Cerrar Sesión</a>
            </nav>
            <button class="navbar-toggler" id="navbar-toggler">&#9776;</button>
        </div>
    </div>
</header>
<div class="sidebar" id="sidebar">
    <button class="close-btn" id="close-btn">&times;</button>
    <ul class="sidebar-menu">
        <?php if ($usuario_iniciado && !$es_administrador): ?>
            <li><a href="libros_2.php">Libros</a></li>
            <li><a href="datos_personales.php">Datos Personales</a></li>
        <?php endif; ?>
        <?php if ($es_administrador): ?>
            <li><a href="/biblioteca/pagos/pagar_cuota.php">Pagos</a></li>
            <li><a href="/biblioteca/circulacion/circulacion.php">Circulación</a></li>
            <li><a href="/biblioteca/libros/libros.php">Libros</a></li>
            <li><a href="/biblioteca/usuarios/usuarios.php">Usuarios</a></li>
            <li><a href="/biblioteca/comentarios_aprobar.php">Comentarios</a></li>
        <?php endif; ?>
        <li><a href="/biblioteca/logout.php">Cerrar Sesión</a></li>
    </ul>
</div>
<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        const navbarToggler = document.getElementById('navbar-toggler');
        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('close-btn');

        navbarToggler.addEventListener('click', function() {
            sidebar.style.right = '0';
        });

        closeBtn.addEventListener('click', function() {
            sidebar.style.right = '-250px';
        });
    });
</script>
</body>
</html>