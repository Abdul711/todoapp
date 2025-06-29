<?php
namespace App\Models;

use PDO;
abstract class Model 
{
    protected $db;
    protected $table;
    protected $fillable = [];
    protected $attributes = [];

 
        public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    

    public function fill(array $data)
    {
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            }
        }
        return $this;
    }

    public function create()
    {
        $columns = implode(', ', array_keys($this->attributes));
        $placeholders = ':' . implode(', :', array_keys($this->attributes));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->attributes);
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        $fields = '';
        foreach ($this->attributes as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ', ');
        $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $this->attributes['id'] = $id;
        return $stmt->execute($this->attributes);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
