<?php

namespace CTFd\Controllers;

/**
 * Admin Controller
 */
class AdminController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/dashboard', $data);
    }
    
    public function challenges()
    {
        $data = [
            'title' => 'Admin Challenges - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/challenges', $data);
    }
    
    public function users()
    {
        $data = [
            'title' => 'Admin Users - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/users', $data);
    }
    
    public function teams()
    {
        $data = [
            'title' => 'Admin Teams - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/teams', $data);
    }
    
    public function scoreboard()
    {
        $data = [
            'title' => 'Admin Scoreboard - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/scoreboard', $data);
    }
    
    public function config()
    {
        $data = [
            'title' => 'Admin Config - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('admin/config', $data);
    }
}

