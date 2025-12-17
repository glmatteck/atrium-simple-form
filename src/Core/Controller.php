<?php
namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$view}");
        }
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        require __DIR__ . '/../Views/layout.php';
    }
    
    protected function redirect(string $path, ?string $message = null)
    {
        if ($message) {
            $_SESSION['message'] = $message;
        }
        header("Location: {$path}");
        exit();
    }
    
    protected function json($data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}