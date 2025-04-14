<?php include '../verificar_permisos.php'; ?>
<?php include '../header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo personalizado para los botones específicos de pagos */
        .pagos-btn {
            background-color: #07e3bb !important; /* Color de fondo */
            border-color: #07e3bb !important; /* Color del borde */
            color: black !important; /* Color del texto */
        }
        .pagos-btn:hover {
            background-color: #06c6a8 !important; /* Color al pasar el cursor */
            border-color: #06c6a8 !important; /* Color del borde al pasar el cursor */
            color: white !important; /* Color del texto al pasar el cursor */
        }
        .pagos-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(7, 227, 187, 0.5) !important; /* Efecto de foco */
        }
        /* Cambiar el color de todas las letras dentro de la página de pagos a blanco */
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
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1 class="my-4">Pagos</h1>

        <!-- Botones centrados y responsivos -->
        <div class="d-flex flex-wrap justify-content-center mb-4">
            <div class="btn-group flex-wrap" role="group" aria-label="Acciones de pagos">
                <a href="pagar_cuota.php" class="btn pagos-btn mx-2 mb-2">Pagar Cuota</a>
                <a href="cuota_atrasada.php" class="btn pagos-btn mx-2 mb-2">Cuotas Atrasadas</a>
                <a href="historial_pagos.php" class="btn pagos-btn mx-2 mb-2">Historial de Pagos</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>