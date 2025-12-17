<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Database;
use App\Controllers\RecordController;

// Initialize database connection
Database::getInstance();

// Initialize router
$router = new Router();

// Define routes
$router->get('/records', [RecordController::class, 'index']);
$router->get('/records/create', [RecordController::class, 'create']);
$router->post('/records', [RecordController::class, 'store']);
$router->get('/records/(\d+)/edit', [RecordController::class, 'edit']);
$router->post('/records/(\d+)', [RecordController::class, 'update']);
$router->post('/records/(\d+)/delete', [RecordController::class, 'destroy']);

// Handle root redirect
$router->get('/', function() {
    header('Location: /records');
    exit();
});

// Dispatch the request
$router->dispatch();