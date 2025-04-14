<?php include '../db_connection.php'; ?>
<?php include 'header_circulacion.php'; ?>

<?php
// Obtener lista de usuarios
$sql = "SELECT id, nombre, apellido, dni FROM usuarios";
$result = $conn->query($sql);
$usuarios = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'dni' => $row['dni']
        ];
    }
}
?>

<h2>Buscar Usuario</h2>
<form action="renovar_3.php" method="post">
    <label for="usuario_nombre">Usuario (Nombre, Apellido o DNI):</label>
    <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" required><br>
    <input type="hidden" id="usuario_id" name="usuario_id" required><br>
</form>

<!-- jQuery UI Autocomplete -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(function() {
    var usuarios = <?php echo json_encode($usuarios); ?>;
    $("#usuario_nombre").autocomplete({
        source: usuarios.map(function(usuario) {
            return {
                label: usuario.nombre + " " + usuario.apellido + " (DNI: " + usuario.dni + ")",
                value: usuario.id
            };
        }),
        select: function(event, ui) {
            $("#usuario_nombre").val(ui.item.label);
            $("#usuario_id").val(ui.item.value);
            // Enviar el formulario para redirigir a renovar_3.php
            $("form").submit();
            return false;
        }
    });
});
</script>

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
    .ui-autocomplete {
        background-color: #272230 !important; /* Fondo de la lista de autocompletado */
        color: white !important; /* Color del texto de la lista de autocompletado */
        border: 1px solid #444 !important;
    }
    .ui-menu-item-wrapper {
        color: white !important; /* Color del texto de los elementos de la lista */
    }
</style>

<?php $conn->close(); ?>