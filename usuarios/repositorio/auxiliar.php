<?php include 'header_usuarios.php'; ?>
<?php include '../db_connection.php'; ?>

<?php
// Obtener el ID del usuario que ha iniciado sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta a la base de datos para obtener el apellido del usuario
$sql = "SELECT apellido FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$recibio_pago = $user_data['apellido'] ?? '';

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['ajax'])) {
    $usuario_id = $_POST['usuario_id'];
    $fecha = date('Y-m-d'); // Fecha actual
    $vencimiento_cuota = $_POST['vencimiento_cuota'];
    $metodo_pago = $_POST['metodo_pago'];

    // Insertar datos en la tabla pagos
    $sql = "INSERT INTO pagos (usuario_id, fecha, vencimiento_cuota, metodo_pago, recibio_pago) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("issss", $usuario_id, $fecha, $vencimiento_cuota, $metodo_pago, $recibio_pago);
    if ($stmt->execute() === TRUE) {
        echo "<p>Pago registrado exitosamente</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}

// Manejar solicitud AJAX para obtener el vencimiento de cuota más reciente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax']) && $_POST['ajax'] == 'true') {
    $usuario_id = $_POST['usuario_id'];

    // Consulta a la base de datos para obtener el vencimiento de cuota más reciente del usuario
    $sql = "SELECT vencimiento_cuota FROM pagos WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($vencimiento_cuota);
    $stmt->fetch();

    // Asegurarse de que solo se devuelva la fecha
    header('Content-Type: text/plain');
    echo $vencimiento_cuota;
    exit();
}

// Obtener lista de usuarios
$sql = "SELECT id, nombre FROM usuarios";
$result = $conn->query($sql);
?>

<h2>Registrar Pago</h2>
<form action="" method="post">
    <label for="usuario_id">Usuario:</label>
    <select name="usuario_id" id="usuario_id" required onchange="fetchVencimientoCuota(this.value)">
        <option value="">Seleccione un usuario</option>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay usuarios disponibles</option>";
        }
        ?>
    </select><br>

    <label for="vencimiento_cuota">Indicar hasta qué mes se pagó la cuota:</label>
    <input type="date" id="vencimiento_cuota" name="vencimiento_cuota" required><br>

    <label for="metodo_pago">Método de Pago:</label>
    <select id="metodo_pago" name="metodo_pago" required>
        <option value="Efectivo">Efectivo</option>
        <option value="Transferencia">Transferencia</option>
    </select><br>

    <input type="submit" value="Registrar Pago">
</form>

<script>
function fetchVencimientoCuota(usuarioId) {
    console.log("Usuario seleccionado: " + usuarioId); // Agregar para depuración

    if (usuarioId === "") {
        document.getElementById("vencimiento_cuota").value = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Response: " + xhr.responseText); // Agregar para depuración
            // Verificar si la respuesta es una fecha válida
            if (xhr.responseText.match(/^\d{4}-\d{2}-\d{2}$/)) {
                document.getElementById("vencimiento_cuota").value = xhr.responseText;
            } else {
                console.error("Respuesta no válida: " + xhr.responseText);
            }
        } else if (xhr.readyState == 4) {
            console.error("Error: " + xhr.status); // Agregar para depuración
        }
    };
    xhr.send("ajax=true&usuario_id=" + usuarioId);
}
</script>

<?php $conn->close(); ?>