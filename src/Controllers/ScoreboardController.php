<?php

namespace CTFd\Controllers;

use CTFd\Database;

/**
 * Scoreboard Controller
 */
class ScoreboardController extends BaseController
{
    public function index()
    {
        $db = Database::getInstance();
        
        // Get user standings with scores
        $sql = "SELECT u.id, u.name, 
                COALESCE(SUM(c.value), 0) as score,
                COUNT(DISTINCT s.challenge_id) as solves
                FROM users u
                LEFT JOIN solves s ON u.id = s.user_id
                LEFT JOIN challenges c ON s.challenge_id = c.id
                WHERE u.hidden = 0 AND u.banned = 0
                GROUP BY u.id, u.name
                ORDER BY score DESC, solves DESC";
        
        $standings = $db->fetchAll($sql);
        
        $data = [
            'title' => 'Scoreboard - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'standings' => $standings
        ];
        
        $this->render('scoreboard/index', $data);
    }
}

