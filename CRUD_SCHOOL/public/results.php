<?php
include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$quiz_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // Obtener las respuestas del estudiante desde la base de datos
    $query = "SELECT qr.question_id, qq.question, qa.answer, qa.correct
              FROM quiz_responses qr
              INNER JOIN quiz_questions qq ON qr.question_id = qq.id
              INNER JOIN quiz_answers qa ON qr.answer_id = qa.id
              WHERE qr.student_id = :student_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
    $stmt->execute();
    $quiz_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos CSS */
        .btn-primary {
            background-color: #556B2F; /* Verde olivo oscuro */
            border: none;
        }
        .btn-primary:hover {
            background-color: #6B8E23; /* Verde olivo un poco más claro para el hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Results</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="student_id">Enter ID:</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Check</button>
                </form>
            </div>
        </div>
        <?php if (!empty($quiz_results)): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Questions and Answers:</h3>
                    <ul>
                        <?php foreach ($quiz_results as $result): ?>
                            <li>
                                <strong>Question:</strong> <?= htmlspecialchars($result['question']) ?><br>
                                <strong>User response:</strong> <?= htmlspecialchars($result['answer']) ?><br>
                                <?php if ($result['correct'] == 1): ?>
                                    <span class="text-success">¡Correct!</span>
                                <?php else: ?>
                                    <span class="text-danger">Incorrect</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
