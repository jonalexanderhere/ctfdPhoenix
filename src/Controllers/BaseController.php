<?php

namespace CTFd\Controllers;

/**
 * Base Controller
 */
abstract class BaseController
{
    protected $config = [];
    
    public function __construct()
    {
        $this->loadConfig();
    }
    
    /**
     * Load configuration
     */
    protected function loadConfig()
    {
        // Load from environment variables
        $this->config = [
            'database_url' => $_ENV['DATABASE_URL'] ?? '',
            'secret_key' => $_ENV['SECRET_KEY'] ?? '',
            'ctf_name' => $_ENV['CTF_NAME'] ?? 'CTFd',
            'ctf_theme' => $_ENV['CTF_THEME'] ?? 'core',
        ];
    }
    
    /**
     * Render view
     */
    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            http_response_code(500);
            echo "View not found: $view";
        }
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}

