<?php
include_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$student_id = $_POST['student_id'];
$answers = $_POST['answers'];

$response_messages = [];

foreach ($answers as $question_id => $answer_id) {
    $query = "INSERT INTO quiz_responses (question_id, student_id, answer_id) VALUES (:question_id, :student_id, :answer_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':question_id', $question_id, PDO::PARAM_STR);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR); // Ajustamos el tipo a STR para permitir cualquier ID
    $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $response_messages[] = "Response for question $question_id inserted successfully.";
    } catch (PDOException $e) {
        $response_messages[] = "Error inserting response for question $question_id: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizz Submission</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Quiz Submission</h1>
        <div class="alert alert-info" role="alert">
            <?php foreach ($response_messages as $message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </div>
        <a href="results.php" class="btn btn-primary btn-block">Results</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
