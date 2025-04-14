<?php include 'header_usuarios.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Agregar Usuario</h1>
        <form action="procesar_agregar_usuario.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="numero_de_socio">Número de Socio</label>
                <input type="number" class="form-control" id="numero_de_socio" name="numero_de_socio" required>
            </div>
            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuario</label>
                <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                    <option value="socio">Socio</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="genero">Género</label>
                <input type="text" class="form-control" id="genero" name="genero" required>
            </div>
            <button type="submit" class="btn btn-primary buscar-btn">Agregar Usuario</button>
        </form>
    </div>
</body>
</html>