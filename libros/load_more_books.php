<?php
include '../db_connection.php';

// Manejar la solicitud AJAX para cargar más libros
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['offset'])) {
    $offset = (int)$_POST['offset'];
    $search_option = $_POST['search_option'];
    $search_term = $_POST['search_term'];

    // Consulta a la base de datos
    $sql = "SELECT `AUTOR`, `TITULO`, `EDITORIAL`, `ESTANTE`, `categoria`, `ESTADO`
            FROM libros";
    if (!empty($search_option) && !empty($search_term)) {
        if ($search_option == 'autor') {
            $sql .= " WHERE `AUTOR` LIKE '%$search_term%'";
        } elseif ($search_option == 'titulo') {
            $sql .= " WHERE `TITULO` LIKE '%$search_term%'";
        }
    }
    $sql .= " LIMIT 20 OFFSET $offset";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["EDITORIAL"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["ESTANTE"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["ESTADO"]) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No se encontraron más libros</td></tr>";
    }

    $conn->close();
    exit();
}
?>