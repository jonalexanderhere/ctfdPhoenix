<?php
/**
 * CTFd - Main Entry Point untuk Vercel
 * 
 * File ini menangani semua request yang masuk ke aplikasi
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Set timezone
date_default_timezone_set('UTC');

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Autoload Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    die('Please run: composer install');
}

// Get request path
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($path, PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

// Remove query string
$path = strtok($path, '?');

// API Routes
if (strpos($path, '/api/') === 0) {
    $apiPath = str_replace('/api/', '', $path);
    $apiFile = __DIR__ . '/api/' . $apiPath . '.php';
    
    if (file_exists($apiFile)) {
        require $apiFile;
        exit;
    }
    
    // Default API handler
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(['error' => 'API endpoint not found']);
    exit;
}

// Static files (CSS, JS, images, etc.)
$staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
$extension = pathinfo($path, PATHINFO_EXTENSION);
if (in_array(strtolower($extension), $staticExtensions)) {
    $staticFile = __DIR__ . '/CTFd/themes/core/static' . $path;
    if (file_exists($staticFile)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        readfile($staticFile);
        exit;
    }
}

// Start session with secure settings
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    
    session_start();
}

// Main application router
require_once __DIR__ . '/src/Router.php';

try {
    $router = new CTFd\Router();
    $router->handle($path);
} catch (Exception $e) {
    error_log("Router error: " . $e->getMessage());
    http_response_code(500);
    echo "Internal Server Error";
}

