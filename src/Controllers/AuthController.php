<?php

namespace CTFd\Controllers;

/**
 * Authentication Controller
 */
class AuthController extends BaseController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }
        
        $data = [
            'title' => 'Login - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('auth/login', $data);
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
            return;
        }
        
        $data = [
            'title' => 'Register - ' . ($this->config['ctf_name'] ?? 'CTFd')
        ];
        
        $this->render('auth/register', $data);
    }
    
    public function logout()
    {
        session_start();
        session_destroy();
        $this->redirect('/');
    }
    
    private function handleLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Email and password required'], 400);
            return;
        }
        
        $users = \CTFd\Models\User::where('email', '=', $email);
        if (empty($users)) {
            $this->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            return;
        }
        
        $user = $users[0];
        if (!$user->verifyPassword($password)) {
            $this->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            return;
        }
        
        // Set session
        \CTFd\Utils\Session::setUser($user->id);
        \CTFd\Utils\Session::set('is_admin', $user->type === 'admin');
        
        $this->redirect('/');
    }
    
    private function handleRegister()
    {
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($name) || empty($password)) {
            $this->json(['success' => false, 'message' => 'All fields required'], 400);
            return;
        }
        
        // Check if user exists
        $existing = \CTFd\Models\User::where('email', '=', $email);
        if (!empty($existing)) {
            $this->json(['success' => false, 'message' => 'Email already registered'], 400);
            return;
        }
        
        // Create new user
        $user = new \CTFd\Models\User([
            'name' => $name,
            'email' => $email,
            'type' => 'user'
        ]);
        $user->setPassword($password);
        $user->save();
        
        // Auto login
        \CTFd\Utils\Session::setUser($user->id);
        
        $this->redirect('/');
    }
}

