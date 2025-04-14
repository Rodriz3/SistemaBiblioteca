<?php include 'header_usuarios.php'; ?>
<?php
include '../db_connection.php';

// Obtener el término de búsqueda si existe
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Consulta a la base de datos para obtener los usuarios
$sql = "SELECT password, nombre, apellido, dni, categoria, correo, telefono, numero_de_socio, tipo_usuario, direccion, genero FROM usuarios";
if (!empty($search_term)) {
    $sql .= " WHERE nombre LIKE '%$search_term%' OR apellido LIKE '%$search_term%' OR dni LIKE '%$search_term%'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .lexend-custom {
            font-family: 'Lexend', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Puedes cambiar el valor de 100 a 900 según tus necesidades */
            font-style: normal;
        }
        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <h1 class="mb-4">Lista de Usuarios</h1>
        <!-- Formulario de búsqueda -->
        <form method="GET" action="usuarios_lista.php" class="form-inline mb-3 search-form">
            <input type="text" name="search_term" class="form-control mr-2" placeholder="nombre, apellido o DNI" value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary buscar-btn">Buscar</button>
        </form>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Categoría</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Número de Socio</th>
                        <th>Tipo de Usuario</th>
                        <th>Dirección</th>
                        <th>Género</th>
                        <th>Contraseña</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Salida de datos de cada fila
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["apellido"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["dni"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["correo"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["telefono"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["numero_de_socio"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["tipo_usuario"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["direccion"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["genero"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>No hay usuarios</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>