<?php
include_once '../src/Controller/StudentController.php';

$controller = new StudentController();
$id = $_GET['id'];
$controller->delete($id);

header("Location: index.php");
?>
