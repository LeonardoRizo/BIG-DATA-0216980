<?php
include_once '../templates/header.php';
include_once '../src/Controller/StudentController.php';

if ($_POST) {
    $controller = new StudentController();
    $controller->create($_POST['name'], $_POST['email'], $_POST['phone']);
    header("Location: index.php");
}
?>

<div class="container">
    <h1>Add Student</h1>
    <form action="create.php" method="post">
        <div class="form-group">
            <label for="name">ID:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Full Name:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Aka:</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <button type="submit" class="btn btn-primary">Insert New Student</button>
    </form>
</div>

<?php include_once '../templates/footer.php'; ?>
