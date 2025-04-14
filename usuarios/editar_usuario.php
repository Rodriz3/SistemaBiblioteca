<?php
include '../db_connection.php';
include 'header_usuarios.php';

// Obtener el ID del usuario desde el formulario
$id_usuario = $_POST['usuario_id'];

// Si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_usuario'])) {
    // Obtener los valores actualizados desde el formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    $categoria = $_POST['categoria'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $numero_de_socio = $_POST['numero_de_socio'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];

    // Actualizar los datos en la base de datos
    $sql = "UPDATE usuarios SET 
                nombre = '$nombre', 
                apellido = '$apellido', 
                dni = '$dni', 
                password = '$password', 
                categoria = '$categoria', 
                correo = '$correo', 
                telefono = '$telefono', 
                numero_de_socio = '$numero_de_socio', 
                direccion = '$direccion', 
                genero = '$genero'
            WHERE id = $id_usuario";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

    $conn->close();
    exit();
}

// Obtener los datos actuales del usuario para mostrarlos en el formulario
$sql = "SELECT * FROM usuarios WHERE id = $id_usuario";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Editar Usuario</h2>
        <form method="post" action="editar_usuario.php">
            <input type="hidden" name="usuario_id" value="<?php echo $id_usuario; ?>">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="<?php echo $usuario['apellido']; ?>" required>
            </div>
            <div class="form-group">
                <label>DNI:</label>
                <input type="text" name="dni" class="form-control" value="<?php echo $usuario['dni']; ?>" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $usuario['password']; ?>" required>
            </div>
            <div class="form-group">
                <label>Categoria:</label>
                <input type="text" name="categoria" class="form-control" value="<?php echo $usuario['categoria']; ?>" required>
            </div>
            <div class="form-group">
                <label>Correo:</label>
                <input type="email" name="correo" class="form-control" value="<?php echo $usuario['correo']; ?>" required>
            </div>
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo $usuario['telefono']; ?>" required>
            </div>
            <div class="form-group">
                <label>Número de Socio:</label>
                <input type="text" name="numero_de_socio" class="form-control" value="<?php echo $usuario['numero_de_socio']; ?>" required>
            </div>
            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo $usuario['direccion']; ?>" required>
            </div>
            <div class="form-group">
                <label>Género:</label>
                <input type="text" name="genero" class="form-control" value="<?php echo $usuario['genero']; ?>" required>
            </div>
            <button type="submit" name="editar_usuario" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>