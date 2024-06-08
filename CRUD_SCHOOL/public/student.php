<?php
include_once '../templates/header.php';
include_once '../src/Controller/StudentController.php';

$controller = new StudentController();
$stmt = $controller->read();
?>

<div class="container">
    <h1>Student List</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Aka</th>
                <th>Options</th>
                
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['aka']; ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="create.php" class="btn btn-primary">Insert New Student</a>
</div>

<?php include_once '../templates/footer.php'; ?>
