<?php
include_once '../templates/header.php';
include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Obtener preguntas
$questions_sql = "SELECT id, question FROM quiz_questions WHERE quiz_id = 1"; // Asumiendo que el quiz de Leonardo tiene quiz_id = 1
$questions_stmt = $conn->prepare($questions_sql);
$questions_stmt->execute();
$questions_result = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener respuestas
$answers_sql = "SELECT id, question_id, answer FROM quiz_answers";
$answers_stmt = $conn->prepare($answers_sql);
$answers_stmt->execute();
$answers_result = $answers_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leonardo Quizz</title>
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
        .btn-primary {
            background-color: #556B2F; /* Verde olivo oscuro */
            border: none;
        }
        .btn-primary:hover {
            background-color: #6B8E23; /* Verde olivo un poco más claro para el hover */
        }
        .question-label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .answer-label {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Leonardo Quizz</h1>
        <form method="POST" action="submit_quizz.php" class="mt-4">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" id="student_id" class="form-control" required>
            </div>
            <?php foreach ($questions_result as $question): ?>
                <div class="form-group">
                    <label class="question-label"><?= htmlspecialchars($question['question']) ?></label>
                    <?php foreach ($answers_result as $answer): ?>
                        <?php if ($answer['question_id'] == $question['id']): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="<?= $answer['id'] ?>" value="<?= $answer['id'] ?>" required>
                                <label class="form-check-label answer-label" for="<?= $answer['id'] ?>">
                                    <?= htmlspecialchars($answer['answer']) ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <!-- Agregar campo para respuesta abierta 
            <div class="form-group">
                <label for="open_answer">Respuesta Abierta:</label>
                <input type="text" id="open_answer" name="open_answer" class="form-control" required>
            </div> -->
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
