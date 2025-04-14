<?php
include 'db_connection.php';

if (isset($_GET['libro_id'])) {
    $libro_id = $_GET['libro_id'];

    $sql_comentarios = "SELECT comentario, fecha FROM comentarios WHERE libro_id = ?";
    $stmt_comentarios = $conn->prepare($sql_comentarios);
    if ($stmt_comentarios === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_comentarios->bind_param("i", $libro_id);
    $stmt_comentarios->execute();
    $result_comentarios = $stmt_comentarios->get_result();

    if ($result_comentarios->num_rows > 0) {
        echo "<ul>";
        while($comentario = $result_comentarios->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($comentario['comentario']) . " - " . htmlspecialchars($comentario['fecha']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No hay comentarios.";
    }

    $stmt_comentarios->close();
}

$conn->close();
?>