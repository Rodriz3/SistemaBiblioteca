<?php include '../header.php'; ?>
<?php include '../db_connection.php'; ?>
<?php

// Obtener el ID del usuario que ha iniciado sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta a la base de datos para obtener los datos del usuario
$sql = "SELECT nombre, apellido, dni, correo, telefono, direccion, numero_de_socio FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Consulta a la base de datos para obtener los libros prestados del usuario
$sql_libros = "SELECT l.TITULO, p.vencimiento 
               FROM prestamos p
               JOIN libros l ON p.id_libro = l.ID
               WHERE p.id_usuario = ?";
$stmt_libros = $conn->prepare($sql_libros);
if ($stmt_libros === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt_libros->bind_param("i", $usuario_id);
$stmt_libros->execute();
$result_libros = $stmt_libros->get_result();
$libros_prestados = $result_libros->fetch_all(MYSQLI_ASSOC);

// Consulta a la base de datos para obtener la última fila de pagos del usuario
$sql_vencimiento = "SELECT * 
                    FROM pagos 
                    WHERE usuario_id = ? 
                    ORDER BY id DESC 
                    LIMIT 1";
$stmt_vencimiento = $conn->prepare($sql_vencimiento);
if ($stmt_vencimiento === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt_vencimiento->bind_param("i", $usuario_id);
$stmt_vencimiento->execute();
$result_vencimiento = $stmt_vencimiento->get_result();
$ultimo_pago = $result_vencimiento->fetch_assoc();

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Personales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Datos Personales</h1>
        <form action="actualizar_datos.php" method="POST">
            <table class="table table-striped">
                <tr>
                    <th>Nombre</th>
                    <td><?php echo htmlspecialchars($user_data['nombre']); ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?php echo htmlspecialchars($user_data['apellido']); ?></td>
                </tr>
                <tr>
                    <th>DNI</th>
                    <td><?php echo htmlspecialchars($user_data['dni']); ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($user_data['correo']); ?>" required></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($user_data['telefono']); ?>" required></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($user_data['direccion']); ?>" required></td>
                </tr>
                <tr>
                    <th>Número de Socio</th>
                    <td><?php echo htmlspecialchars($user_data['numero_de_socio']); ?></td>
                </tr>
                <tr>
                    <th>Vencimiento de Cuota</th>
                    <td><?php echo htmlspecialchars($ultimo_pago['fecha_fin']); ?></td>
                </tr>
                <tr>
                    <th>Libros Prestados</th>
                    <td>
                        <?php if (count($libros_prestados) > 0): ?>
                            <ul>
                                <?php foreach ($libros_prestados as $libro): ?>
                                    <li><?php echo htmlspecialchars($libro['TITULO']) . " - Vencimiento: " . htmlspecialchars($libro['vencimiento']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            No tiene libros prestados.
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn btn-primary buscar-btn">Guardar Cambios</button>
        </form>
        <!-- Botón para cambiar la contraseña -->
        <div class="mt-4">
            <a href="cambiar_contrasena.php" class="btn btn-primary buscar-btn">Cambiar Contraseña</a>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
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
    </style>
</body>
</html>