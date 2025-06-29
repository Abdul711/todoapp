<?php
require_once __DIR__ . '/../interfaces/TaskRepositoryInterface.php';
require_once __DIR__ . '/../app/Models/Task.php';

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    private $conn;
    private $taskModel;
    public function __construct($db)
    {
        $this->conn = $db;

        $this->taskModel = new Task($this->conn);
    }

    public function getAll()
    {

        $tasks = $this->taskModel->all();
        return $tasks;
    }

    public function getById($id)
    {
        return $this->taskModel->find($id);
    }

    public function create($data)
    {
      
    
        return $this->taskModel->fill([
            'title' => $data['title'],
            'completed' => 0
        ])->create();
    }

    public function update($id, $data)
    {

        return $this->taskModel->fill([
            'title' => $data['title']

        ])->update($id);
    }

    public function delete($id)
    {
        return $this->taskModel->delete($id);
    }
    public function getActive(){
      return  $openTasks = $this->taskModel
   
    ->get();
    }
}
