<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private string $requestUri;
    private string $requestMethod;
    
    public function __construct()
    {
        $this->requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }
    
    public function get(string $path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post(string $path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute(string $method, string $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }
    
    public function dispatch()
    {
        $method = $this->requestMethod;
        
        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }
        
        foreach ($this->routes[$method] as $pattern => $handler) {
            if (preg_match('#^' . $pattern . '$#', $this->requestUri, $matches)) {
                array_shift($matches); // Remove full match
                
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                } elseif (is_array($handler)) {
                    [$controller, $method] = $handler;
                    $controllerInstance = new $controller();
                    call_user_func_array([$controllerInstance, $method], $matches);
                }
                return;
            }
        }
        
        $this->notFound();
    }
    
    private function notFound()
    {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit();
    }
}