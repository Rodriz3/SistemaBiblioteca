<?php include 'header_usuarios.php'; ?>
<?php
include '../db_connection.php';

// Aprobar o rechazar comentarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && isset($_POST['comentario_id'])) {
    $comentario_id = $_POST['comentario_id'];
    if ($_POST['accion'] == 'aprobar') {
        $sql_aprobar = "UPDATE comentarios SET aprobado = 1 WHERE id = ?";
    } elseif ($_POST['accion'] == 'rechazar') {
        $sql_aprobar = "DELETE FROM comentarios WHERE id = ?";
    }
    $stmt_aprobar = $conn->prepare($sql_aprobar);
    if ($stmt_aprobar === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_aprobar->bind_param("i", $comentario_id);
    $stmt_aprobar->execute();
    $stmt_aprobar->close();
}

// Obtener comentarios no aprobados
$sql_comentarios = "SELECT c.id, c.comentario, u.nombre FROM comentarios c JOIN usuarios u ON c.id_usuarios = u.id WHERE c.aprobado = 0";
$result_comentarios = $conn->query($sql_comentarios);

if ($result_comentarios === false) {
    die("Error en la consulta: " . $conn->error);
}

// Obtener todos los comentarios
$sql_todos_comentarios = "SELECT c.id, c.comentario, u.nombre FROM comentarios c JOIN usuarios u ON c.id_usuarios = u.id";
$result_todos_comentarios = $conn->query($sql_todos_comentarios);

if ($result_todos_comentarios === false) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Comentarios</title>
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
    </style>
</head>
<body class="lexend-custom">
    <div class="container mt-5">
        <h1 class="mb-4">Administrar Comentarios</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_comentarios->num_rows > 0) {
                        // Salida de datos de cada fila
                        while($row = $result_comentarios->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["comentario"]) . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='' style='display:inline-block;'>";
                            echo "<input type='hidden' name='comentario_id' value='" . htmlspecialchars($row["id"]) . "'>";
                            echo "<button type='submit' name='accion' value='aprobar' class='btn btn-success'>Aprobar</button>";
                            echo "</form>";
                            echo "<form method='POST' action='' style='display:inline-block;'>";
                            echo "<input type='hidden' name='comentario_id' value='" . htmlspecialchars($row["id"]) . "'>";
                            echo "<button type='submit' name='accion' value='rechazar' class='btn btn-danger'>Rechazar</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay comentarios pendientes de aprobación</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-5">Todos los Comentarios</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_todos_comentarios->num_rows > 0) {
                        // Salida de datos de cada fila
                        while($row = $result_todos_comentarios->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["comentario"]) . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='' style='display:inline-block;'>";
                            echo "<input type='hidden' name='comentario_id' value='" . htmlspecialchars($row["id"]) . "'>";
                            echo "<button type='submit' name='accion' value='rechazar' class='btn btn-danger'>Eliminar</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay comentarios registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>