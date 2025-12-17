<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Record
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }
    
    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM test ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM test WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function create(array $data): bool
    {
        $sql = "INSERT INTO test (first_name, middle_initial, last_name, loan, value, ltv) 
                VALUES (:first_name, :middle_initial, :last_name, :loan, :value, :ltv)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_initial' => $data['middle_initial'],
            ':last_name' => $data['last_name'],
            ':loan' => $data['loan'],
            ':value' => $data['value'],
            ':ltv' => $data['ltv']
        ]);
    }
    
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE test SET 
                first_name = :first_name,
                middle_initial = :middle_initial,
                last_name = :last_name,
                loan = :loan,
                value = :value,
                ltv = :ltv
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':middle_initial' => $data['middle_initial'],
            ':last_name' => $data['last_name'],
            ':loan' => $data['loan'],
            ':value' => $data['value'],
            ':ltv' => $data['ltv']
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM test WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function calculateLTV(float $loan, float $value): string
    {
        if ($value <= 0) {
            return '0.00%';
        }
        return number_format(($loan / $value) * 100, 2) . '%';
    }
}