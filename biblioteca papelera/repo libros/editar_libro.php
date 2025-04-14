<?php include 'header_libros.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Libro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Seleccionar Libro</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Autor</th>
                    <th>Título</th>
                    <th>Editorial</th>
                    <th>ISBN</th>
                    <th>D</th>
                    <th>Persona</th>
                    <th>ID</th>
                    <th>Estante</th>
                    <th>Categoría</th>
                    <th>Categoría 2</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="booksTableBody">
                <?php
                // Conexión a la base de datos
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "biblioteca";

                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Consulta a la base de datos
                $sql = "SELECT AUTOR, TITULO, EDITORIAL, ISBN, D, PERSONA, ID, ESTANTE, categoria, categoria2 FROM libros ORDER BY ID DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['AUTOR'] . "</td>";
                        echo "<td>" . $row['TITULO'] . "</td>";
                        echo "<td>" . $row['EDITORIAL'] . "</td>";
                        echo "<td>" . $row['ISBN'] . "</td>";
                        echo "<td>" . $row['D'] . "</td>";
                        echo "<td>" . $row['PERSONA'] . "</td>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['ESTANTE'] . "</td>";
                        echo "<td>" . $row['categoria'] . "</td>";
                        echo "<td>" . $row['categoria2'] . "</td>";
                        echo "<td>
                                <button class='btn btn-info' onclick='openEditModal(" . json_encode($row) . ")'>Editar</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay libros disponibles</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para editar libro -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="editar_libro.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="edit_book_id" id="edit_book_id">
                        <div class="form-group">
                            <label for="edit_autor">Autor</label>
                            <input type="text" class="form-control" id="edit_autor" name="edit_autor" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_titulo">Título</label>
                            <input type="text" class="form-control" id="edit_titulo" name="edit_titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_editorial">Editorial</label>
                            <input type="text" class="form-control" id="edit_editorial" name="edit_editorial" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_isbn">ISBN</label>
                            <input type="text" class="form-control" id="edit_isbn" name="edit_isbn" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_d">D</label>
                            <input type="text" class="form-control" id="edit_d" name="edit_d" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_persona">Persona</label>
                            <input type="text" class="form-control" id="edit_persona" name="edit_persona" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_estante">Estante</label>
                            <input type="text" class="form-control" id="edit_estante" name="edit_estante">
                        </div>
                        <div class="form-group">
                            <label for="edit_categoria">Categoría</label>
                            <input type="text" class="form-control" id="edit_categoria" name="edit_categoria">
                        </div>
                        <div class="form-group">
                            <label for="edit_categoria2">Categoría 2</label>
                            <input type="text" class="form-control" id="edit_categoria2" name="edit_categoria2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary buscar-btn">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openEditModal(book) {
            document.getElementById('edit_book_id').value = book.ID;
            document.getElementById('edit_autor').value = book.AUTOR;
            document.getElementById('edit_titulo').value = book.TITULO;
            document.getElementById('edit_editorial').value = book.EDITORIAL;
            document.getElementById('edit_isbn').value = book.ISBN;
            document.getElementById('edit_d').value = book.D;
            document.getElementById('edit_persona').value = book.PERSONA;
            document.getElementById('edit_estante').value = book.ESTANTE;
            document.getElementById('edit_categoria').value = book.categoria;
            document.getElementById('edit_categoria2').value = book.categoria2;
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>