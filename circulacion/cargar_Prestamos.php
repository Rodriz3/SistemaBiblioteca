<?php
include '../db_connection.php';

// Manejar la solicitud AJAX para cargar más libros
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['offset'])) {
    $offset = (int)$_POST['offset'];
    $search_option = $_POST['search_option'];
    $search_term = $_POST['search_term'];

    // Consulta a la base de datos
    $sql = "SELECT `ID`, `AUTOR`, `TITULO`, `EDITORIAL`, `ISBN`, `ESTADO`
            FROM libros";
    if (!empty($search_option) && !empty($search_term)) {
        if ($search_option == 'autor') {
            $sql .= " WHERE `AUTOR` LIKE ?";
        } elseif ($search_option == 'titulo') {
            $sql .= " WHERE `TITULO` LIKE ?";
        }
    }
    $sql .= " LIMIT 20 OFFSET ?";
    $stmt = $conn->prepare($sql);
    if (!empty($search_option) && !empty($search_term)) {
        $search_term = '%' . $search_term . '%';
        $stmt->bind_param("si", $search_term, $offset);
    } else {
        $stmt->bind_param("i", $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>";
            if ($row["ESTADO"] == "Disponible") {
                echo "<form method='POST' action='prestamo.php'>
                        <input type='hidden' name='libro_id' value='" . htmlspecialchars($row["ID"]) . "'>
                        <button type='submit' name='seleccionar' class='btn btn-primary buscar-btn'>Elegir</button>
                      </form>";
            } else {
                echo "<button class='btn btn-secondary' disabled>No Disponible</button>";
            }
            echo "</td>";
            echo "<td>" . htmlspecialchars($row["AUTOR"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["TITULO"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["EDITORIAL"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["ISBN"]) . "</td>";
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