<?php
include_once '../templates/header.php';
include_once '../src/Controller/StudentController.php';

$controller = new StudentController();

if ($_POST) {
    $controller->update($_POST['id'], $_POST['name'], $_POST['email'], $_POST['phone']);
    header("Location: index.php");
}

$id = $_GET['id'];
$stmt = $controller->read();
$row = null;
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($r['id'] == $id) {
        $row = $r;
        break;
    }
}
?>

<div class="container">
    <h1>Update Student</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['full_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Aka</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['aka']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include_once '../templates/footer.php'; ?>
