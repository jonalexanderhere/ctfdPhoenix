<?php
/**
 * Test Webhook Endpoint
 * Untuk testing WhatsApp webhook
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use CTFd\Services\WhatsAppWebhook;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$userName = $input['user_name'] ?? 'Test User';
$affiliation = $input['affiliation'] ?? 'Test School';
$challengeName = $input['challenge_name'] ?? 'Test Challenge';
$category = $input['category'] ?? 'Crypto';
$isPractice = $input['is_practice'] ?? false;

$webhook = new WhatsAppWebhook();

if (!$webhook->isEnabled()) {
    echo json_encode([
        'success' => false,
        'message' => 'WhatsApp webhook is not enabled. Set WHATSAPP_ENABLED=true in .env'
    ]);
    exit;
}

$result = $webhook->sendFirstBlood(
    $userName,
    $affiliation,
    $challengeName,
    $category,
    $isPractice
);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Webhook sent successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send webhook. Check logs.'
    ]);
    http_response_code(500);
}

