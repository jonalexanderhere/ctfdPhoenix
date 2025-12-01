<?php

namespace CTFd\Controllers;

use CTFd\Database;

/**
 * Teams Controller
 */
class TeamsController extends BaseController
{
    public function list()
    {
        $db = Database::getInstance();
        
        // TODO: Fetch teams from database
        $teams = [];
        
        $data = [
            'title' => 'Teams - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'teams' => $teams
        ];
        
        $this->render('teams/list', $data);
    }
    
    public function view($params = [])
    {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/teams');
            return;
        }
        
        $db = Database::getInstance();
        
        // TODO: Fetch team from database
        $team = null;
        
        $data = [
            'title' => 'Team - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'team' => $team
        ];
        
        $this->render('teams/view', $data);
    }
}

