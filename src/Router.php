<?php

namespace CTFd;

/**
 * Router untuk menangani routing aplikasi CTFd
 */
class Router
{
    private $routes = [];
    
    public function __construct()
    {
        $this->initializeRoutes();
    }
    
    /**
     * Initialize routes
     */
    private function initializeRoutes()
    {
        // Home page
        $this->routes['/'] = 'home';
        
        // Auth routes
        $this->routes['/login'] = 'auth/login';
        $this->routes['/register'] = 'auth/register';
        $this->routes['/logout'] = 'auth/logout';
        $this->routes['/reset_password'] = 'auth/reset_password';
        
        // Challenge routes
        $this->routes['/challenges'] = 'challenges/list';
        $this->routes['/challenge'] = 'challenges/view';
        
        // Scoreboard
        $this->routes['/scoreboard'] = 'scoreboard/index';
        
        // Teams
        $this->routes['/teams'] = 'teams/list';
        $this->routes['/team'] = 'teams/view';
        
        // Users
        $this->routes['/users'] = 'users/list';
        $this->routes['/user'] = 'users/view';
        
        // Admin routes
        $this->routes['/admin'] = 'admin/dashboard';
        $this->routes['/admin/challenges'] = 'admin/challenges';
        $this->routes['/admin/users'] = 'admin/users';
        $this->routes['/admin/teams'] = 'admin/teams';
        $this->routes['/admin/scoreboard'] = 'admin/scoreboard';
        $this->routes['/admin/config'] = 'admin/config';
        
        // Setup route
        $this->routes['/setup'] = 'setup/index';
    }
    
    /**
     * Handle request
     */
    public function handle($path)
    {
        // Normalize path
        $path = rtrim($path, '/') ?: '/';
        
        // Check if route exists
        if (isset($this->routes[$path])) {
            $handler = $this->routes[$path];
            $this->dispatch($handler);
            return;
        }
        
        // Try to match dynamic routes
        $matched = $this->matchDynamicRoute($path);
        if ($matched) {
            $this->dispatch($matched['handler'], $matched['params']);
            return;
        }
        
        // 404 Not Found
        $this->notFound();
    }
    
    /**
     * Match dynamic routes
     */
    private function matchDynamicRoute($path)
    {
        // Match /challenge/{id}
        if (preg_match('#^/challenge/(\d+)$#', $path, $matches)) {
            return [
                'handler' => 'challenges/view',
                'params' => ['id' => $matches[1]]
            ];
        }
        
        // Match /team/{id}
        if (preg_match('#^/team/(\d+)$#', $path, $matches)) {
            return [
                'handler' => 'teams/view',
                'params' => ['id' => $matches[1]]
            ];
        }
        
        // Match /user/{id}
        if (preg_match('#^/user/(\d+)$#', $path, $matches)) {
            return [
                'handler' => 'users/view',
                'params' => ['id' => $matches[1]]
            ];
        }
        
        return null;
    }
    
    /**
     * Dispatch to handler
     */
    private function dispatch($handler, $params = [])
    {
        $parts = explode('/', $handler);
        $controller = $parts[0];
        $action = $parts[1] ?? 'index';
        
        // Map controller names
        $controllerMap = [
            'home' => 'Home',
            'auth' => 'Auth',
            'challenges' => 'Challenges',
            'scoreboard' => 'Scoreboard',
            'teams' => 'Teams',
            'users' => 'Users',
            'admin' => 'Admin',
            'setup' => 'Setup'
        ];
        
        $controllerName = $controllerMap[$controller] ?? ucfirst($controller);
        $controllerClass = "CTFd\\Controllers\\{$controllerName}Controller";
        $controllerFile = __DIR__ . '/Controllers/' . $controllerName . 'Controller.php';
        
        if (file_exists($controllerFile)) {
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action($params);
                    return;
                }
            }
        }
        
        // Fallback to view renderer
        $this->renderView($handler, $params);
    }
    
    /**
     * Render view
     */
    private function renderView($view, $params = [])
    {
        $viewFile = __DIR__ . '/Views/' . str_replace('/', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            extract($params);
            require $viewFile;
            return;
        }
        
        $this->notFound();
    }
    
    /**
     * 404 Not Found
     */
    private function notFound()
    {
        http_response_code(404);
        header('Content-Type: text/html');
        
        $errorFile = __DIR__ . '/Views/errors/404.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}

