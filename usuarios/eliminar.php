<?php
session_start();
include '../db_connection.php';
include 'header_usuarios.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

// Verificar si se ha enviado el formulario para eliminar el usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id']) && isset($_POST['confirmar'])) {
    $usuario_id = $_POST['usuario_id'];

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    if ($stmt->execute()) {
        echo "<p>El usuario se eliminó correctamente.</p>";
    } else {
        echo "<p>Error al eliminar el usuario: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Obtener los datos del usuario seleccionado
if (isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    $sql = "SELECT nombre, apellido FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Usuario</h1>

        <?php if (isset($user_data)): ?>
            <h2>Detalles del Usuario</h2>
            <p>Nombre: <?php echo htmlspecialchars($user_data['nombre']); ?></p>
            <p>Apellido: <?php echo htmlspecialchars($user_data['apellido']); ?></p>

            <!-- Formulario para confirmar la eliminación del usuario -->
            <form method="POST" action="eliminar.php">
                <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
                <p>¿Está seguro de que desea eliminar este usuario?</p>
                <button type="submit" name="confirmar" value="si" class="btn btn-danger">Sí, eliminar</button>
                <a href="permisos.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <p>No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>