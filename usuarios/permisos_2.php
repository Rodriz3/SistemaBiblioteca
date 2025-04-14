<?php
include '../db_connection.php';
include 'header_usuarios.php';

// Verificar si se ha enviado el formulario para cambiar el tipo de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id']) && isset($_POST['tipo_usuario'])) {
    $usuario_id = $_POST['usuario_id'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Actualizar el tipo de usuario en la base de datos
    $sql = "UPDATE usuarios SET tipo_usuario=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("si", $tipo_usuario, $usuario_id);
    if ($stmt->execute()) {
        echo "<p>El tipo de usuario se actualizó correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el tipo de usuario: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Obtener los datos del usuario seleccionado
if (isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    $sql = "SELECT nombre, apellido, tipo_usuario FROM usuarios WHERE id=?";
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
    <title>Cambiar Tipo de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Cambiar Tipo de Usuario</h1>

        <?php if (isset($user_data)): ?>
            <h2>Detalles del Usuario</h2>
            <p>Nombre: <?php echo htmlspecialchars($user_data['nombre']); ?></p>
            <p>Apellido: <?php echo htmlspecialchars($user_data['apellido']); ?></p>
            <p>Tipo de Usuario Actual: <?php echo htmlspecialchars($user_data['tipo_usuario']); ?></p>

            <!-- Formulario para cambiar el tipo de usuario -->
            <form method="POST" action="permisos_2.php">
                <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
                <div class="form-group">
                    <label for="tipo_usuario">Nuevo Tipo de Usuario:</label>
                    <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                        <option value="socio" <?php echo $user_data['tipo_usuario'] == 'socio' ? 'selected' : ''; ?>>Socio</option>
                        <option value="administrador" <?php echo $user_data['tipo_usuario'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary buscar-btn">Actualizar</button>
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