<?php

namespace CTFd\Controllers;

use CTFd\Database;

/**
 * Users Controller
 */
class UsersController extends BaseController
{
    public function list()
    {
        $db = Database::getInstance();
        
        // TODO: Fetch users from database
        $users = [];
        
        $data = [
            'title' => 'Users - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'users' => $users
        ];
        
        $this->render('users/list', $data);
    }
    
    public function view($params = [])
    {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/users');
            return;
        }
        
        $db = Database::getInstance();
        
        // TODO: Fetch user from database
        $user = null;
        
        $data = [
            'title' => 'User - ' . ($this->config['ctf_name'] ?? 'CTFd'),
            'user' => $user
        ];
        
        $this->render('users/view', $data);
    }
}

