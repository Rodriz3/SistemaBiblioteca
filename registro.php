<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Registro de Usuario</h1>
    </header>
    <main>
        <form action="registro.php" method="post">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Registrarse</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "biblioteca";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $user = $conn->real_escape_string($_POST["username"]);
            $pass = password_hash($conn->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (username, password) VALUES ('$user', '$pass')";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Registro exitoso. <a href='login.php'>Iniciar sesión</a></p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }

            $conn->close();
        }
        ?>
    </main>
    <footer>
        <p>&copy; 2024 Biblioteca</p>
    </footer>
</body>
</html>