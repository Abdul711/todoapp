<?php
require_once __DIR__ . '/env.php';
use App\Config\Env;
class Database {
    private $host;
    private $db;
    private $user;
    private $pass;
    public $conn;
    private $port;
    public function __construct() {
        // Load .env once
        Env::load();
      $this->host=  Env::get('DB_HOST', 'localhost');
    $this->db   = Env::get('DB_DATABASE', 'todo_app');
        $this->user = Env::get('DB_USERNAME', 'root');
        $this->pass = Env::get('DB_PASSWORD', '');
        $this->port = Env::get('DB_PORT', '3306');
    }

     public function connect() {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }
}
