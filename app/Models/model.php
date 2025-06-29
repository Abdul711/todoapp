<?php

namespace App\Models;

use PDO;

// abstract class Model
// {
//     protected $db;
//     protected $table;
//     protected $fillable = [];
//     protected $attributes = [];
//     protected bool $timestamps = true;
//     protected bool $softDeletes = true;
//     protected array $query = [];
//     protected array $orQuery = [];
//     protected ?string $orderBy = null;
//     protected int $limitValue = 0;


//    
//     public function save(): bool
//     {
//         return isset($this->attributes['id']) ? $this->update($this->attributes['id']) : $this->create();
//     }


//     public function fill(array $data)
//     {
//         foreach ($this->fillable as $field) {
//             if (isset($data[$field])) {
//                 $this->attributes[$field] = $data[$field];
//             }
//         }
//         return $this;
//     }

//     public function create()
//     {
//         $columns = implode(', ', array_keys($this->attributes));
//         $placeholders = ':' . implode(', :', array_keys($this->attributes));
//         $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

//         $stmt = $this->db->prepare($sql);
//         return $stmt->execute($this->attributes);
//     }

//     public function all()
//     {
//         $stmt = $this->db->query("SELECT * FROM {$this->table}");
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     public function find($id)
//     {
//         $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
//         $stmt->execute(['id' => $id]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }

//     public function update($id)
//     {
//         $fields = '';
//         foreach ($this->attributes as $key => $value) {
//             $fields .= "$key = :$key, ";
//         }
//         $fields = rtrim($fields, ', ');
//         $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";

//         $stmt = $this->db->prepare($sql);
//         $this->attributes['id'] = $id;
//         return $stmt->execute($this->attributes);
//     }

//     public function delete($id)
//     {
//         $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
//         return $stmt->execute(['id' => $id]);
//     }
//     public function findOrFail(int $id): array
//     {
//         $result = $this->find($id);
//         if (!$result) {
//             http_response_code(404);
//             exit("Record not found.");
//         }
//         return $result;
//     }
// }
abstract class Model
{
    protected $db;
    protected $table;
    protected $fillable = [];
    protected $attributes = [];

    protected bool $timestamps = true;
    protected bool $softDeletes = false;

    // Query builder parts
    protected array $query = [];
    protected array $orQuery = [];
    protected ?string $orderBy = null;
    protected int $limitValue = 0;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fill(array $data): static
    {
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            }
        }
        return $this;
    }

    public function save(): bool
    {
        return isset($this->attributes['id']) ? $this->update($this->attributes['id']) : $this->create();
    }

    public function create(): bool
    {
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $this->attributes['created_at'] = $now;
            $this->attributes['updated_at'] = $now;
        }

        $columns = implode(', ', array_keys($this->attributes));
        $placeholders = ':' . implode(', :', array_keys($this->attributes));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->attributes);
    }

    public function update(int $id): bool
    {
        if ($this->timestamps) {
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }

        $fields = '';
        foreach ($this->attributes as $key => $val) {
            $fields .= "$key = :$key, ";
        }

        $fields = rtrim($fields, ', ');
        $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $this->attributes['id'] = $id;

        return $stmt->execute($this->attributes);
    }

    public function delete(int $id): bool
    {
        if ($this->softDeletes) {
            $sql = "UPDATE {$this->table} SET deleted_at = :deleted_at WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['deleted_at' => date('Y-m-d H:i:s'), 'id' => $id]);
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pagination
    public function paginate(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?? (int) ($_GET['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $params = [];
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE deleted_at IS NULL";
        $sql .= $this->buildWhereClause($params);

        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }

        $sql .= " LIMIT :offset, :limit";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();
        $lastPage = (int) ceil($total / $perPage);

        return [
            'data' => $data,
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'from' => $offset + 1,
            'to' => $offset + count($data),
            'has_more_pages' => $page < $lastPage
        ];
    }

    // Query builder helpers
    public function where(string $column, string $operator, $value): static
    {
        $this->query[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function orWhere(string $column, string $operator, $value): static
    {
        $this->orQuery[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = "$column " . strtoupper($direction);
        return $this;
    }

    public function limit(int $number): static
    {
        $this->limitValue = $number;
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->query[] = ['type' => 'in', 'column' => $column, 'placeholders' => $placeholders, 'values' => $values];
        return $this;
    }

    public function whereNull(string $column): static
    {
        $this->query[] = ['type' => 'null', 'column' => $column];
        return $this;
    }

    public function first(): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL";
        $params = [];
        $sql .= $this->buildWhereClause($params);
        $sql .= " LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get(): array
    {
        $params = [];
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL";
        $sql .= $this->buildWhereClause($params);

        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }

        if ($this->limitValue > 0) {
            $sql .= " LIMIT {$this->limitValue}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";
        $params = [];
        $sql .= $this->buildWhereClause($params);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    private function buildWhereClause(array &$params): string
    {
        $sql = '';
        $counter = 0;

        foreach ($this->query as $q) {
            if (($q['type'] ?? '') === 'in') {
                $sql .= " AND {$q['column']} IN ({$q['placeholders']})";
                foreach ($q['values'] as $v) {
                    $params[] = $v;
                }
            } elseif (($q['type'] ?? '') === 'null') {
                $sql .= " AND {$q['column']} IS NULL";
            } else {
                $key = ":param_$counter";
                $sql .= " AND {$q['column']} {$q['operator']} $key";
                $params[$key] = $q['value'];
                $counter++;
            }
        }

        foreach ($this->orQuery as $q) {
            $key = ":or_param_$counter";
            $sql .= " OR {$q['column']} {$q['operator']} $key";
            $params[$key] = $q['value'];
            $counter++;
        }

        return $sql;
    }
}
