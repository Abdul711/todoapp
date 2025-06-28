<?php
require_once __DIR__ . '/../interfaces/TaskRepositoryInterface.php';

class TaskRepository implements TaskRepositoryInterface {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM tasks");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO tasks (title) VALUES (?)");
        return $stmt->execute([$data['title']]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE tasks SET title = ?, completed = ? WHERE id = ?");
        return $stmt->execute([$data['title'], $data['completed'], $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
