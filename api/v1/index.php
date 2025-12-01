<?php
/**
 * API v1 Entry Point
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use CTFd\Models\Challenge;
use CTFd\Models\User;
use CTFd\Models\Team;
use CTFd\Database;

header('Content-Type: application/json');

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = str_replace('/api/v1', '', $path);
$path = rtrim($path, '/') ?: '/';

// Parse request body
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Route handling
$response = ['success' => false, 'message' => 'Endpoint not found'];

try {
    // Challenges endpoints
    if (preg_match('#^/challenges/?$#', $path) && $method === 'GET') {
        $challenges = Challenge::all();
        $response = [
            'success' => true,
            'data' => array_map(function($c) {
                return $c->toArray();
            }, $challenges)
        ];
    }
    elseif (preg_match('#^/challenges/(\d+)/?$#', $path, $matches) && $method === 'GET') {
        $challenge = Challenge::find($matches[1]);
        if ($challenge) {
            $response = ['success' => true, 'data' => $challenge->toArray()];
        } else {
            $response = ['success' => false, 'message' => 'Challenge not found'];
            http_response_code(404);
        }
    }
    // Scoreboard endpoint
    elseif ($path === '/scoreboard' && $method === 'GET') {
        $db = Database::getInstance();
        $sql = "SELECT u.id, u.name, 
                COALESCE(SUM(c.value), 0) as score,
                COUNT(DISTINCT s.challenge_id) as solves
                FROM users u
                LEFT JOIN solves s ON u.id = s.user_id
                LEFT JOIN challenges c ON s.challenge_id = c.id
                WHERE u.hidden = 0 AND u.banned = 0
                GROUP BY u.id, u.name
                ORDER BY score DESC, solves DESC
                LIMIT 100";
        $standings = $db->fetchAll($sql);
        $response = ['success' => true, 'data' => $standings];
    }
    // Teams endpoints
    elseif ($path === '/teams' && $method === 'GET') {
        $teams = Team::all();
        $response = [
            'success' => true,
            'data' => array_map(function($t) {
                return $t->toArray();
            }, $teams)
        ];
    }
    // Users endpoints
    elseif ($path === '/users' && $method === 'GET') {
        $users = User::all();
        $response = [
            'success' => true,
            'data' => array_map(function($u) {
                $data = $u->toArray();
                unset($data['password']); // Don't expose passwords
                return $data;
            }, $users)
        ];
    }
    // Submit flag
    elseif (preg_match('#^/challenges/(\d+)/submit/?$#', $path, $matches) && $method === 'POST') {
        $challengeId = $matches[1];
        $flag = $input['flag'] ?? $_POST['flag'] ?? '';
        
        if (empty($flag)) {
            $response = ['success' => false, 'message' => 'Flag required'];
            http_response_code(400);
        } else {
            $challenge = Challenge::find($challengeId);
            if (!$challenge) {
                $response = ['success' => false, 'message' => 'Challenge not found'];
                http_response_code(404);
            } else {
                $flags = $challenge->getFlags();
                $correct = false;
                foreach ($flags as $f) {
                    if ($f->content === $flag) {
                        $correct = true;
                        break;
                    }
                }
                
                if ($correct) {
                    // Check if already solved
                    $userId = \CTFd\Utils\Session::getUserId();
                    if ($userId && !$challenge->isSolvedByUser($userId)) {
                        // Check if this is first solve (before creating)
                        $isFirstBlood = \CTFd\Utils\FirstBlood::isFirstSolve($challengeId);
                        
                        // Create solve
                        $solve = new \CTFd\Models\Solve([
                            'challenge_id' => $challengeId,
                            'user_id' => $userId,
                            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                            'date' => date('Y-m-d H:i:s')
                        ]);
                        $solve->save();
                        
                        // Send First Blood notification if this is the first solve
                        if ($isFirstBlood) {
                            \CTFd\Utils\FirstBlood::sendNotification($userId, $challengeId);
                        }
                    }
                    $response = ['success' => true, 'message' => 'Correct!', 'data' => ['correct' => true]];
                } else {
                    $response = ['success' => false, 'message' => 'Incorrect flag', 'data' => ['correct' => false]];
                }
            }
        }
    }
    
} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
    http_response_code(500);
}

http_response_code($response['success'] ? 200 : (http_response_code() ?: 404));
echo json_encode($response, JSON_PRETTY_PRINT);

