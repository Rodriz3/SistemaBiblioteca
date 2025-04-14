<?php include '../header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Inicio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo personalizado para los botones específicos de administración de inicio */
        .admin-inicio-btn {
            background-color: #07e3bb !important; /* Color de fondo */
            border-color: #07e3bb !important; /* Color del borde */
            color: black !important; /* Color del texto */
        }
        .admin-inicio-btn:hover {
            background-color: #06c6a8 !important; /* Color al pasar el cursor */
            border-color: #06c6a8 !important; /* Color del borde al pasar el cursor */
            color: white !important; /* Color del texto al pasar el cursor */
        }
        .admin-inicio-btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(7, 227, 187, 0.5) !important; /* Efecto de foco */
        }
        /* Cambiar el color de todas las letras dentro de la página de administración de inicio a blanco */
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
    <div class="container mt-5">
        <h1 class="mb-4">Administrar Información del Inicio</h1>
        <form action="update_inicio.php" method="post">
            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="Calle Falsa 123, Ciudad" required>
            </div>
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" name="numero" id="numero" class="form-control" value="+123 456 7890" required>
            </div>
            <div class="form-group">
                <label for="instagram">Instagram:</label>
                <input type="text" name="instagram" id="instagram" class="form-control" value="@biblioteca" required>
            </div>
            <div class="form-group">
                <label for="facebook">Facebook:</label>
                <input type="text" name="facebook" id="facebook" class="form-control" value="Biblioteca en Facebook" required>
            </div>
            <div class="form-group">
                <label for="mail">Mail:</label>
                <input type="email" name="mail" id="mail" class="form-control" value="contacto@biblioteca.com" required>
            </div>
            <div class="form-group">
                <label for="horarios">Horarios:</label>
                <textarea name="horarios" id="horarios" class="form-control" rows="5" required>Lunes a Viernes de 9:00 a 18:00</textarea>
            </div>
            <div class="form-group">
                <label for="libro1">Libro Recomendado 1:</label>
                <input type="text" name="libro1" id="libro1" class="form-control" value="" required>
            </div>
            <div class="form-group">
                <label for="libro2">Libro Recomendado 2:</label>
                <input type="text" name="libro2" id="libro2" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="libro3">Libro Recomendado 3:</label>
                <input type="text" name="libro3" id="libro3" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="libro4">Libro Recomendado 4:</label>
                <input type="text" name="libro4" id="libro4" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="libro5">Libro Recomendado 5:</label>
                <input type="text" name="libro5" id="libro5" class="form-control" value="">
            </div>
            <button type="submit" class="btn admin-inicio-btn">Actualizar</button>
        </form>
    </div>
    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>