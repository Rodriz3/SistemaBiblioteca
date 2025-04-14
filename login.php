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
    $password = trim($_POST['password']);

    // Consulta a la base de datos para verificar las credenciales
    $sql = "SELECT id, password, tipo_usuario FROM usuarios WHERE dni = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta ha tenido éxito
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Credenciales válidas, obtener los datos del usuario
        $stmt->bind_result($usuario_id, $stored_password, $tipo_usuario);
        $stmt->fetch();

        // Verificar la contraseña
        if ($password == $stored_password) {
            // Iniciar sesión
            session_regenerate_id(true); // Regenerar la sesión para evitar ataques de fijación de sesión
            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['tipo_usuario'] = $tipo_usuario;
            header("Location: inicio/inicio.php");
            exit();
        } else {
            $error = "DNI o contraseña incorrectos";
        }
    } else {
        $error = "DNI o contraseña incorrectos";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
        body {
            padding-top: 20px; /* Ajusta este valor según la altura de tu header */
            background-color: #272230 !important;
            color: white !important;
        }
        .user-image {
            display: block;
            margin: 50px auto 10px; /* Ajusta el margen superior para mover la imagen más abajo */
            width: 200px; /* Ajusta el tamaño de la imagen según tus necesidades */
            height: auto;
        }
        .form-control {
            background-color: #272230 !important;
            color: white !important;
            border: 1px solid #444 !important;
        }
        .form-control::placeholder {
            color: #bbb !important;
        }
        .btn-primary {
            background-color: #d000db !important; /* Color de fondo del botón */
            border-color: #d000db !important; /* Color del borde del botón */
        }
        .btn-primary:hover {
            background-color: #b000b8 !important; /* Color de fondo del botón al pasar el mouse */
            border-color: #b000b8 !important; /* Color del borde del botón al pasar el mouse */
        }
        .error {
            color: red;
        }
        .header-text {
            text-align: center;
            font-size: 1.5rem;
            margin-top: 20px;
        }
        .test-credentials {
            text-align: center;
            margin-top: 20px;
            color: yellow;
        }
    </style>
</head>
<body class="lexend-custom">

<!-- Agregar el texto "Biblioteca Popular Encuentros" en la parte superior -->
<div class="header-text">
    Biblioteca Popular Encuentros
</div>

<!-- Agregar la imagen después del header -->
<img src="png/logo.png" alt="User Image" class="user-image">

<!-- Agregar el cartel de credenciales de prueba -->
<div class="test-credentials">
    <p>Usuario: test & Contraseña: test</p>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <p class="error text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="login.php" method="post" class="text-center">
                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" id="dni" name="dni" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
            <p class="text-center"><a href="reset_password.php">Olvidé mi contraseña</a></p>
        </div>
    </div>
</div>

<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>