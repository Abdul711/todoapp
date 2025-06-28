<?php
class TaskService {
    private $taskRepo;

    public function __construct(TaskRepositoryInterface $taskRepo) {
        $this->taskRepo = $taskRepo;
    }

    public function getTasks() {
        return $this->taskRepo->getAll();
    }

    public function storeTask($data) {
        return $this->taskRepo->create($data);
    }

    public function updateTask($id, $data) {
        return $this->taskRepo->update($id, $data);
    }

    public function deleteTask($id) {
        return $this->taskRepo->delete($id);
    }

    public function getTaskById($id) {
        return $this->taskRepo->getById($id);
    }
}
