<?php
include "Controller.php";
class TaskController extends Controller {
    private $service;

    public function __construct(TaskService $service) {
        $this->service = $service;
    }


    public function index() {
        $tasks = $this->service->getTasks();
     
        $this->view('index', ['tasks' => $tasks]);
    }

    public function create() {
     $this->view("create");
    }

    public function store($data) {
         $this->service->storeTask($data);
           $this->redirect("index");
    }

    public function edit($id) {
        $task = $this->service->getTaskById($id);
        print_r($task);
        // $this->view("edit");
    }

    public function update($id, $data) {
        $this->service->updateTask($id, $data);
       $this->redirect("index");
    }

    public function delete($id) {
  
         $this->service->deleteTask($id);
          $this->redirect("index");
    //    
    //    $this->redirect("index");
    }
}
