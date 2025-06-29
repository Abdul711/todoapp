<?php
require_once __DIR__ . '/../interfaces/TaskRepositoryInterface.php';
require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Config/validator.php';
use App\Models\Task;
use App\Config\Validator;
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
         $validator = new Validator();

   $valid = $validator->validate($data, [
        'title' => 'required|min:3|max:100',
        'description' => 'required|min:5',
    ]);
       print_r($valid);
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
    // ->where('completed', '=', 0)
    // ->orderBy('created_at', 'DESC')
    // ->limit(1)
    ->get();
    }
}
