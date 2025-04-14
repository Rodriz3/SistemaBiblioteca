<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
$usuario_iniciado = isset($_SESSION['usuario_id']);
$es_administrador = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador';

if (!$usuario_iniciado) {
    header("Location: /biblioteca/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Oro Verde</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <!-- Incluir Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            background-color: #272230; /* Mismo color que el header */
            transition: right 0.3s ease-in-out;
            padding-top: 60px;
            box-shadow: -2px 0 5px rgba(0,0,0,0.5);
            z-index: 1000; /* Asegura que la barra lateral esté por encima de otros elementos */
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
            display: flex;
            align-items: center;
        }
        .sidebar-menu a i {
            margin-right: 10px;
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
        @media (max-width: 990px) {
            .top-menu {
                display: none;
            }
            .navbar-toggler {
                display: block;
            }
        }
        @media (min-width: 990px) {
            .sidebar {
                display: none;
            }
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
            background-color: #272230; /* Cambiar color de fondo */
            color: white; /* Cambiar color de texto */
        }
        body {
            padding-top: 70px; /* Ajusta este valor según la altura de tu header */
        }
        .header-small {
            padding: 10px 0;
            background-color: #272230; /* Cambiar color de fondo */
            height: 50px; /* Ajusta este valor según tus necesidades */
        }
        .header-large {
            padding: 20px 0;
            background-color: #272230; /* Cambiar color de fondo */
            height: 80px; /* Ajusta este valor según tus necesidades */
        }
        .header-small .h3 {
            font-size: 1.2rem;
        }
        .header-large .h3 {
            font-size: 2rem;
        }
        .header-small .btn {
            padding: 0px 10px;
            font-size: 0.9rem;
        }
        .header-large .btn {
            padding: 10px 20px;
            font-size: 1rem;
        }
        .header-small, .header-large, header {
            transition: all 0.3s ease-in-out;
        }
        .header-small .h3, .header-large .h3, header .h3 {
            transition: all 0.3s ease-in-out;
        }
        .header-small .btn, .header-large .btn, header .btn {
            transition: all 0.3s ease-in-out;
        }
        .btn-custom {
            background: none; /* Sin color de fondo */
            color: white; /* Color de texto blanco */
            border: none;
        }
        .btn-custom:hover {
            background: none !important; /* Sin color de fondo al pasar el mouse */
            color: #06c6a8 !important; /* Color de texto blanco al pasar el mouse */
        }
        .buscar-btn {
            background-color: #d000db !important; /* Color de fondo del botón */
            border-color: #d000db !important; /* Color del borde del botón */
        }
    </style>
</head>
<body class="lexend-custom">
<header class="text-white p-3 header-large" id="main-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Biblioteca</h1>
            <nav class="top-menu">
                    <a href="/biblioteca/inicio/inicio.php" class="btn btn-custom"><i class="fas fa-home"></i> Inicio</a>
                <?php if ($usuario_iniciado && !$es_administrador): ?>
                    <a href="/biblioteca/socio/libros_2.php" class="btn btn-custom"><i class="fas fa-book"></i> Libros</a>
                    <a href="/biblioteca/socio/datos_personales.php" class="btn btn-custom"><i class="fas fa-user"></i> Datos Personales</a>
                <?php endif; ?>
                <?php if ($es_administrador): ?>
                    <a href="/biblioteca/pagos/pagar_cuota.php" class="btn btn-custom"><i class="fas fa-money-bill"></i> Pagos</a>
                    <a href="/biblioteca/circulacion/circulacion.php" class="btn btn-custom"><i class="fas fa-exchange-alt"></i> Circulación</a>
                    <a href="/biblioteca/libros/libros_buscar.php" class="btn btn-custom"><i class="fas fa-book"></i> Libros</a>
                    <a href="/biblioteca/usuarios/permisos.php" class="btn btn-custom"><i class="fas fa-users"></i> Usuarios</a>
                <?php endif; ?>
                <a href="/biblioteca/logout.php" class="btn btn-custom"><i class="fas fa-sign-out-alt"></i></a>
            </nav>
            <button class="navbar-toggler" id="navbar-toggler">&#9776;</button>
        </div>
    </div>
</header>
<div class="sidebar" id="sidebar">
    <button class="close-btn" id="close-btn">&times;</button>
    <ul class="sidebar-menu">
        <?php if ($usuario_iniciado && !$es_administrador): ?>
            <li><a href="/biblioteca/socio/libros_2.php"><i class="fas fa-book"></i> Libros</a></li>
            <li><a href="/biblioteca/socio/datos_personales.php"><i class="fas fa-user"></i> Datos Personales</a></li>
        <?php endif; ?>
        <?php if ($es_administrador): ?>
            <li><a href="/biblioteca/inicio/inicio.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="/biblioteca/pagos/pagar_cuota.php"><i class="fas fa-money-bill"></i> Pagos</a></li>
            <li><a href="/biblioteca/circulacion/circulacion.php"><i class="fas fa-exchange-alt"></i> Circulación</a></li>
            <li><a href="/biblioteca/libros/libros_buscar.php"><i class="fas fa-book"></i> Libros</a></li>
            <li><a href="/biblioteca/usuarios/permisos.php"><i class="fas fa-users"></i> Usuarios</a></li>
            <li><a href="/biblioteca/inicio/admin_inicio.php"><i class="fas fa-cog"></i> Config Inicio</a></li>
        <?php endif; ?>
        <li><a href="/biblioteca/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
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
        const header = document.getElementById('main-header');

        navbarToggler.addEventListener('click', function() {
            if (sidebar.style.right === '0px') {
                sidebar.style.right = '-250px';
            } else {
                sidebar.style.right = '0';
            }
        });

        closeBtn.addEventListener('click', function() {
            sidebar.style.right = '-250px';
        });

        // Cerrar la barra lateral cuando se cambia el tamaño de la ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 769) {
                sidebar.style.right = '-250px';
            }
        });

        // Cambiar el tamaño del header al hacer scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('header-small');
                header.classList.remove('header-large');
            } else {
                header.classList.add('header-large');
                header.classList.remove('header-small');
            }
        });
    });
</script>
</body>
</html>