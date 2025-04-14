<?php include '../verificar_permisos.php'; ?>
<?php include '../header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo personalizado para los botones específicos de libros */
        .libros-btn {
            background-color: #07e3bb !important; /* Color de fondo */
            border-color: #07e3bb !important; /* Color del borde */
            color: black !important; /* Color del texto */
        }
        .libros-btn:hover {
            background-color: #06c6a8 !important; /* Color al pasar el cursor */
            border-color: #06c6a8 !important; /* Color del borde al pasar el cursor */
            color: white !important; /* Color del texto al pasar el cursor */
        }
        .libros-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(7, 227, 187, 0.5) !important; /* Efecto de foco */
        }
        /* Cambiar el color de todas las letras dentro de la página de libros a blanco */
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
        /* Estilo para el enlace "Libros" */
        .libros-link {
            color: white;
            text-decoration: none;
        }
        .libros-link:hover {
            color: #07e3bb !important;
            text-decoration: none; /* Eliminar el subrayado al pasar el cursor */
        }
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1 class="my-4"><a href="libros_buscar.php" class="libros-link">Libros</a></h1>

        <!-- Botones centrados y responsivos -->
        <div class="d-flex flex-wrap justify-content-center mb-4">
            <div class="btn-group flex-wrap" role="group" aria-label="Acciones de libros">
                <a href="agregar_libro.php" class="btn libros-btn mx-2 mb-2">Agregar</a>
                <a href="libros.php" class="btn libros-btn mx-2 mb-2">Lista</a>
                <a href="exportar_csv.php" class="btn libros-btn mx-2 mb-2">Exportar</a>
                <a href="importar_libros.php" class="btn libros-btn mx-2 mb-2">Importar</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>