<?php

namespace CTFd\Controllers;

/**
 * Home Controller
 */
class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => $this->config['ctf_name'] ?? 'CTFd',
            'theme' => $this->config['ctf_theme'] ?? 'core'
        ];
        
        $this->render('home/index', $data);
    }
}

