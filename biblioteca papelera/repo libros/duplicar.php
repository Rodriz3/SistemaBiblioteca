<?php include 'header_libros.php'; ?>
    <div class="container mt-5">
        <h1>Buscar y Duplicar Libros</h1>
        <form method="GET" action="duplicar.php" class="mb-3">
            <div class="form-group">
                <label for="search">Buscar por Autor o Nombre:</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Ingrese autor o nombre del libro">
            </div>
            <button type="submit" class="btn btn-primary buscar-btn mt-2">Buscar</button>
        </form>

        <?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root"; // Cambia esto por tu nombre de usuario
        $password = ""; // Cambia esto por tu contraseña correcta
        $dbname = "biblioteca";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Procesar la búsqueda
        if (isset($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $sql = "SELECT * FROM libros WHERE autor LIKE '%$search%' OR titulo LIKE '%$search%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>AUTOR</th><th>TITULO</th><th>EDITORIAL</th><th>ISBN</th><th>FECHA</th><th>PERSONA</th><th>ACCIONES</th></tr></thead>';
                echo '<tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['AUTOR'] . '</td>';
                    echo '<td>' . $row['TITULO'] . '</td>';
                    echo '<td>' . $row['EDITORIAL'] . '</td>';
                    echo '<td>' . $row['ISBN'] . '</td>';
                    echo '<td>' . $row['FECHA'] . '</td>';
                    echo '<td>' . $row['PERSONA'] . '</td>';
                    echo '<td><form method="POST" action="duplicar.php"><input type="hidden" name="id" value="' . $row['ID'] . '"><button type="submit" name="duplicate" class="btn btn-secondary">Duplicar</button></form></td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning">No se encontraron libros.</div>';
            }
        }

        // Procesar la duplicación
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['duplicate'])) {
            $id = $_POST['id'];
            $sql = "INSERT INTO libros (autor, titulo, editorial, isbn, fecha, persona, estado, usuario, vencimiento)
                    SELECT autor, titulo, editorial, isbn, fecha, persona, estado, usuario, vencimiento FROM libros WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success mt-3">Libro duplicado exitosamente.</div>';
            } else {
                echo '<div class="alert alert-danger mt-3">Error al duplicar el libro: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>