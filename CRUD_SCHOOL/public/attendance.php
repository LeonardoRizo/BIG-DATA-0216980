<?php
include_once '../templates/header.php';
include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Obtener clases
$classes_sql = "SELECT id FROM classes";
$classes_stmt = $conn->prepare($classes_sql);
$classes_stmt->execute();
$classes_result = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Manejar la búsqueda
$attendance_result = [];
$class_id = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    $query = "SELECT a.id, a.student_id, s.full_name, a.status, a.answer_of_the_day
              FROM attendance a
              JOIN students s ON a.student_id = s.id
              WHERE a.class_id = :class_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $attendance_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Manejar la edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $attendance_id = $_POST['attendance_id'];
    $status = $_POST['status'];
    $answer_of_the_day = $_POST['answer_of_the_day'];
    $class_id = $_POST['class_id'];

    $update_sql = "UPDATE attendance SET status = :status, answer_of_the_day = :answer_of_the_day WHERE id = :id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $update_stmt->bindParam(':answer_of_the_day', $answer_of_the_day, PDO::PARAM_STR);
    $update_stmt->bindParam(':id', $attendance_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        // Refrescar la tabla
        $query = "SELECT a.id, a.student_id, s.full_name, a.status, a.answer_of_the_day
                  FROM attendance a
                  JOIN students s ON a.student_id = s.id
                  WHERE a.class_id = :class_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
        $stmt->execute();
        $attendance_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            padding-top: 30px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 900px;
        }
        h1 {
            color: #3b3a30;
        }
        .btn-green-olive {
            background-color: #556B2F; /* Verde olivo oscuro */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-green-olive:hover {
            background-color: #6B8E23; /* Verde olivo un poco más claro para el hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Attendance</h1>
        <form method="POST" action="attendance.php" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="class_id">Class:</label>
                    <select name="class_id" id="class_id" class="form-control">
                        <option value="">Select class:</option>
                        <?php foreach ($classes_result as $row): ?>
                            <option value="<?= $row['id'] ?>" <?= $row['id'] == $class_id ? 'selected' : '' ?>><?= $row['id'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-green-olive btn-block">SEARCH</button>
        </form>
        
        <?php if (!empty($attendance_result)): ?>
            <h2 class="text-center">Class <?= htmlspecialchars($class_id) ?></h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Answer of the day</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_result as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_id']) ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td><?= htmlspecialchars($row['answer_of_the_day']) ?></td>
                                <td><button class="btn btn-green-olive" data-toggle="modal" data-target="#editModal" data-id="<?= $row['id'] ?>" data-status="<?= $row['status'] ?>" data-answer="<?= $row['answer_of_the_day'] ?>" data-class="<?= $class_id ?>">Edit</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <h2 class="text-center">Class <?= htmlspecialchars($class_id) ?></h2>
            <p class="text-center">No found</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="attendance.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="attendance_id" name="attendance_id">
                        <input type="hidden" id="class_id" name="class_id" value="<?= $class_id ?>">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <input type="text" class="form-control" id="status" name="status">
                        </div>
                        <div class="form-group">
                            <label for="answer_of_the_day">Answer of the day:</label>
                            <input type="text" class="form-control" id="answer_of_the_day" name="answer_of_the_day">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit" class="btn btn-green-olive">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var status = button.data('status');
            var answer = button.data('answer');
            var class_id = button.data('class');

            var modal = $(this);
            modal.find('.modal-body #attendance_id').val(id);
            modal.find('.modal-body #status').val(status);
            modal.find('.modal-body #answer_of_the_day').val(answer);
            modal.find('.modal-body #class_id').val(class_id);
        });
    </script>
</body>
</html>

