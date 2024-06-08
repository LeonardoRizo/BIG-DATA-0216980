<?php
include_once '../config/database.php';
include_once '../src/Model/Student.php';

class StudentController {
    private $db;
    private $student;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->student = new Student($this->db);
    }

    public function read() {
        return $this->student->read();
    }

    public function create($name, $email, $phone) {
        $this->student->name = $name;
        $this->student->email = $email;
        $this->student->phone = $phone;
        return $this->student->create();
    }

    public function update($id, $name, $email, $phone) {
        $this->student->id = $id;
        $this->student->name = $name;
        $this->student->email = $email;
        $this->student->phone = $phone;
        return $this->student->update();
    }

    public function delete($id) {
        $this->student->id = $id;
        return $this->student->delete();
    }
}
?>
