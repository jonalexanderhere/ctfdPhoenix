<?php
/**
 * API Entry Point
 */

header('Content-Type: application/json');

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get request path
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = str_replace('/api', '', $path);
$path = rtrim($path, '/') ?: '/';

// Route to appropriate API handler
$apiFile = __DIR__ . $path . '.php';

if (file_exists($apiFile)) {
    require $apiFile;
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'API endpoint not found'
    ]);
}

