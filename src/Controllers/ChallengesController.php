<?php

namespace CTFd\Controllers;

use CTFd\Database;

/**
 * Challenges Controller
 */
class ChallengesController extends BaseController
{
    public function list()
    {
        $challenges = \CTFd\Models\Challenge::all();
        
        $data = [
            'title' => 'Challenges - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'challenges' => $challenges
        ];
        
        $this->render('challenges/list', $data);
    }
    
    public function view($params = [])
    {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/challenges');
            return;
        }
        
        $challenge = \CTFd\Models\Challenge::find($id);
        
        if (!$challenge) {
            http_response_code(404);
            echo "Challenge not found";
            return;
        }
        
        $data = [
            'title' => $challenge->name . ' - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'challenge' => $challenge
        ];
        
        $this->render('challenges/view', $data);
    }
}

