<?php
include '../db_connection.php';
// include 'verificar_permisos.php';

// Asegúrate de que $usuario_id esté definido. Puedes obtenerlo de la sesión, por ejemplo:
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "Usuario no autenticado.";
    exit();
}
$usuario_id = $_SESSION['usuario_id'];

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar que la nueva contraseña y la confirmación coincidan
    if ($new_password !== $confirm_password) {
        echo "Las nuevas contraseñas no coinciden.";
        exit();
    }

    // Obtener la contraseña actual del usuario desde la base de datos
    $sql = "SELECT password FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Verificar que la contraseña actual sea correcta
    if ($current_password !== $stored_password) {
        echo "La contraseña actual es incorrecta.";
        exit();
    }

    // Actualizar la contraseña en la base de datos
    $sql = "UPDATE usuarios SET password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("si", $new_password, $usuario_id);
    if ($stmt->execute()) {
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Error al actualizar la contraseña: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Cambiar Contraseña</h1>
        <div class="card bg-dark text-white">
            <div class="card-header">
                Cambiar Contraseña
            </div>
            <div class="card-body">
                <form action="cambiar_contrasena.php" method="POST">
                    <div class="form-group">
                        <label for="current_password">Contraseña Actual</label>
                        <input type="password" class="form-control bg-dark text-white" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nueva Contraseña</label>
                        <input type="password" class="form-control bg-dark text-white" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control bg-dark text-white" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary buscar-btn">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .alert-custom {
            background-color: black;
            color: white;
        }
        .chart-container {
            max-width: 300px;
            max-height: 300px;
            margin: 0 auto;
        }
    </style>
</body>
</html>