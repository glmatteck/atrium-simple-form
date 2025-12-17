<?php
namespace App\Core;

use PDO;
use PDOException;

class Migration
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }
    
    /**
     * Run all migrations
     */
    public function run(): void
    {
        echo "Running migrations...\n";
        
        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();
        
        // Run individual migrations
        $this->createTestTable();
        
        echo "Migrations completed successfully!\n";
    }
    
    /**
     * Create migrations tracking table
     */
    private function createMigrationsTable(): void
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                unique_migration unique
            )";
            
            $this->pdo->exec($sql);
            echo "✓ Migrations table ready\n";
        } catch (PDOException $e) {
            die("Error creating migrations table: " . $e->getMessage() . "\n");
        }
    }
    
    /**
     * Create test table for records
     */
    private function createTestTable(): void
    {
        $migrationName = 'create_test_table';
        
        // Check if migration already ran
        if ($this->hasRun($migrationName)) {
            echo "✓ Test table already exists (skipping)\n";
            return;
        }
        
        try {
            $sql = "CREATE TABLE IF NOT EXISTS test (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name VARCHAR(30) NOT NULL,
                middle_initial CHAR(1) DEFAULT NULL,
                last_name VARCHAR(30) NOT NULL,
                loan DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                value DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                ltv VARCHAR(10) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $this->pdo->exec($sql);
            
            // Record migration
            $this->recordMigration($migrationName);
            
            echo "✓ Test table created successfully\n";
        } catch (PDOException $e) {
            die("Error creating test table: " . $e->getMessage() . "\n");
        }
    }
    
    /**
     * Check if a migration has already been run
     */
    private function hasRun(string $migrationName): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
            $stmt->execute([$migrationName]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Record that a migration has been run
     */
    private function recordMigration(string $migrationName): void
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
            $stmt->execute([$migrationName]);
        } catch (PDOException $e) {
            echo "Warning: Could not record migration: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Rollback - drop test table
     */
    public function rollback(): void
    {
        echo "Rolling back migrations...\n";
        
        try {
            $this->pdo->exec("DROP TABLE IF EXISTS test");
            $this->pdo->exec("DELETE FROM migrations WHERE migration = 'create_test_table'");
            echo "✓ Test table dropped\n";
        } catch (PDOException $e) {
            die("Error during rollback: " . $e->getMessage() . "\n");
        }
        
        echo "Rollback completed!\n";
    }
    
    /**
     * Seed sample data
     */
    public function seed(): void
    {
        echo "Seeding database...\n";
        
        $sampleData = [
            ['John', 'A', 'Doe', 250000.00, 300000.00],
            ['Jane', 'B', 'Smith', 180000.00, 250000.00],
            ['Bob', '', 'Johnson', 320000.00, 400000.00],
            ['Alice', 'C', 'Williams', 150000.00, 200000.00],
            ['Charlie', 'D', 'Brown', 275000.00, 350000.00]
        ];
        
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO test (first_name, middle_initial, last_name, loan, value, ltv) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            
            foreach ($sampleData as $data) {
                $ltv = number_format(($data[3] / $data[4]) * 100, 2) . '%';
                $data[] = $ltv;
                $stmt->execute($data);
            }
            
            echo "✓ Sample data inserted\n";
        } catch (PDOException $e) {
            echo "Warning: Could not seed data: " . $e->getMessage() . "\n";
        }
        
        echo "Seeding completed!\n";
    }
    
    /**
     * Get migration status
     */
    public function status(): void
    {
        echo "Migration Status:\n";
        echo "================\n";
        
        try {
            $stmt = $this->pdo->query("SELECT migration, executed_at FROM migrations ORDER BY id");
            $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($migrations)) {
                echo "No migrations have been run yet.\n";
            } else {
                foreach ($migrations as $migration) {
                    echo "✓ {$migration['migration']} - executed at {$migration['executed_at']}\n";
                }
            }
        } catch (PDOException $e) {
            echo "Could not retrieve migration status: " . $e->getMessage() . "\n";
        }
    }
}