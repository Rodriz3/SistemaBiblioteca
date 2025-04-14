<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sitio Web</title>
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
        
    </style>
</head>
<body class="lexend-custom">
<header class="bg-primary text-white p-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Biblioteca Oro Verde</h1>
            
            <nav class="top-menu">
                <a href="libros_2.php" class="btn btn-primary buscar-btn text-white">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALRJREFUSEvt1k0KQVEYxvHfLVmDlFIWwJaEFSgDSzGwDetgYmYmyyDlinPvpXuuEwNn/D7P//3q7WQSvyyxvxDQxgIT9CPge8yxybUhYI1xhPGj5IBBFeCEDobYRYDON8098bCCQkBNSBQgF71i5Yn+JqBOl6Iq+AOeOvCVNf3PoHQGddpSFvv2FiUHxF7TQmLhNT2iixG2Tcu46kPACrMPGFfOoIUlpug1AFUCGniWS5P/Ki4oZCgZWZQthAAAAABJRU5ErkJggg==" alt="Icono" style="width: 16px; height: 16px; margin-right: 5px;">
                    Libros
                </a>
                <a href="administrador.php" class="btn btn-primary buscar-btn text-white">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAiJJREFUSEu11UuoT1EUBvDfFSIDSqEMzJQyFnmmMJOBMkEhbxIpj8g1kFck7/KYmKAMFIVEGImSgVeMpCgDDIXYq/a57U7/e8///nVXncnZ+3zfWt+31jpdBji6BhhffwgGYzXmYBIm4h1e4xEu4nc94XYJJuAWJvdR8Spc7oRgNF5iPL7hBO5jOo5kwN042Iq8qYJBeIhZSZZrWIfvWI+zvYAvzXd/xXkTwRJcxeP0zMUfrCik2IMDReYrcQmLcaMdgidJjhmYl2UZh48JdAjiLCqroiS+guXtEPzAiAz4NyMtzNlFV4UfWxNplXlF9jVJOKaJIABCx58YVjNwPm7m9w+yfHHlMHbku0Pj+yYPvmBs7vn3NZKZuJtAhyOq24hn+elJvokgOigGKz6uuqbkmYY72JbNPZQreJreT22SaDNOZrQwLIxrFaNy68acfMiyRRuf74sgjDue0TYkmc417KyYl2jLRXieOy+8azkHYVKUGlEH34KRqT3D2JAhJJ6d5mMvwpNPmILPVUJ1D/alg+58uCathwtF5ptwqo9KbmNZXic910qCGKgYnog6eJh4LJ+9zV0VnfMKb3Cv1aKrSxSmrMX19FGsiCpKyWKhxWJrO8oKQsPYORHVdtyeJvlofhfS7W8bOV+se7CzWLvlhO4qjO8XR6tBix4+U3RYzMPpfqEWl3ub5AVpDcSUvsg7p1P8xv9Bx8C9zcF/A9YB/gHj5GUZGP+85QAAAABJRU5ErkJggg==" alt="Icono" style="width: 16px; height: 16px; margin-right: 5px;">
                    Administrador
                </a>
                
                <a href="datos_personales.php" class="btn btn-primary buscar-btn text-white">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAZxJREFUSEu11L1vjWEYx/HPSecmmgpCajHUaOofIF5K01EbU/8CidCkpoYNIQzduyojUukLo9UmBgtB21RIGQnPldxtTh7nPveTU+da7/v6fa/3lj5bq8/6mgImq0Bu4kQK6D3m8bwUYBPAHG5nhGZxvxukBDiPF9hAgFaS2DncwRGcwXoOUgKsJoFpPK6JTGEJy7jYK+AbDlRlGMTPmsgwtvEZx3oFbOIQDuJrBhB/olQdrVSiqPlZRDmeZEoUPbrQK6C9yTEx0ZOBBL2Lw/ttcgR2DSEWwu32u+pLQB/uZ0x3fUdxHTGev/Ayzf+7/7FoJY2u792aHOMZUY/hVJqmdrEtvMHrlM2PTqQcIKZiMTWxSQaxCzNYq3/uBJjAs/TxKW4hjtv3mnNkGMcv3sMnLAKLsd2zOmAIb1PkcWtuNAkf91I5v+BkNXk7u351wFU8wCucbige30InljIO35Vq8xdygDhol0rLkwGPp8P3CJdzgA8YQZSqXvNSQkfxCR9xPAf4kx5KNyoH+8e/V6FSNtkpauzY9GPfM/gL5dBHGcZ57nQAAAAASUVORK5CYII=" alt="Icono" style="width: 16px; height: 16px; margin-right: 5px;">
                    Datos Personales
                </a>
                <a href="logout.php" class="btn btn-primary buscar-btn text-white">Cerrar Sesión</a>

            </nav>
        </div>
    </div>
</header>
<!-- Incluir Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>