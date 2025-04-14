<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = trim($_POST['dni']);
    $apellido = trim($_POST['apellido']);
    $numero_de_socio = trim($_POST['numero_de_socio']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        // Consulta a la base de datos para verificar el DNI, el apellido y el número de socio
        $sql = "SELECT id FROM usuarios WHERE dni = ? AND apellido = ? AND numero_de_socio = ?";
        $stmt = $conn->prepare($sql);

        // Verificar si la preparación de la consulta ha tenido éxito
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("sss", $dni, $apellido, $numero_de_socio);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // DNI, apellido y número de socio válidos, actualizar la contraseña
            $stmt->bind_result($usuario_id);
            $stmt->fetch();

            $sql_update = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if ($stmt_update === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }

            $stmt_update->bind_param("si", $new_password, $usuario_id);
            $stmt_update->execute();
            $stmt_update->close();

            $success = "Contraseña actualizada con éxito. Ahora puede <a href='login.php'>iniciar sesión</a>.";
        } else {
            $error = "DNI, apellido o número de socio no encontrado.";
        }

        $stmt->close();
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
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
            background-color: #343a40;
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
        @media (max-width: 768px) {
            .top-menu {
                display: none;
            }
            .navbar-toggler {
                display: block;
            }
        }
        @media (min-width: 769px) {
            .sidebar {
                display: none;
            }
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        body {
            padding-top: 70px; /* Ajusta este valor según la altura de tu header */
        }
    </style>
</head>
<body class="lexend-custom">
<header class="bg-primary text-white p-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Biblioteca Oro Verde</h1>
            <nav class="top-menu">
                <!-- No mostrar enlaces de navegación en la página de restablecimiento de contraseña -->
            </nav>
            <button class="navbar-toggler" id="navbar-toggler">&#9776;</button>
        </div>
    </div>
</header>
<div class="sidebar" id="sidebar">
    <button class="close-btn" id="close-btn">&times;</button>
    <ul class="sidebar-menu">
        <!-- No mostrar enlaces de navegación en la página de restablecimiento de contraseña -->
    </ul>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Restablecer Contraseña</h2>
            <?php if (isset($error)): ?>
                <p class="error text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success text-center"><?php echo $success; ?></p>
            <?php else: ?>
                <form action="reset_password.php" method="post" class="text-center">
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="numero_de_socio">Número de Socio</label>
                        <input type="text" id="numero_de_socio" name="numero_de_socio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nueva Contraseña</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary buscar-btn">Restablecer Contraseña</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
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
    });
</script>
</body>
</html>