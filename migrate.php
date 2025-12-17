<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Migration;
use App\Core\Database;

// Initialize database connection
Database::getInstance();

$migration = new Migration();

// Parse command line arguments
$command = $argv[1] ?? 'run';

try {
    switch ($command) {
        case 'run':
            $migration->run();
            break;
            
        case 'rollback':
            $migration->rollback();
            break;
            
        case 'seed':
            $migration->seed();
            break;
            
        case 'status':
            $migration->status();
            break;
            
        case 'fresh':
            // Rollback and re-run
            $migration->rollback();
            $migration->run();
            $migration->seed();
            break;
            
        default:
            echo "Unknown command: {$command}\n";
            echo "Available commands:\n";
            echo "  run      - Run all migrations\n";
            echo "  rollback - Rollback migrations\n";
            echo "  seed     - Seed sample data\n";
            echo "  status   - Show migration status\n";
            echo "  fresh    - Rollback, migrate, and seed\n";
            exit(1);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}