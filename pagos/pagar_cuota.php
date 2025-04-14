<?php include '../db_connection.php'; ?>
<?php include 'header_pagos.php'; ?>

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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Buscar Usuario</h2>
            <form action="pagar_cuota_2.php" method="post">
                <div class="form-group">
                    <label for="usuario_nombre">Usuario (Nombre, Apellido o DNI):</label>
                    <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" required>
                </div>
                <input type="hidden" id="usuario_id" name="usuario_id" required>
            </form>
        </div>
    </div>
</div>

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
            // Enviar el formulario para redirigir a pagar_cuota_2.php
            $("form").submit();
            return false;
        }
    });
});
</script>

<?php $conn->close(); ?>