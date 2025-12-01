<?php

namespace CTFd\Utils;

/**
 * Session utilities
 */
class Session
{
    /**
     * Start session if not started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Remove session value
     */
    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }
    
    /**
     * Clear all session
     */
    public static function clear()
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId()
    {
        return self::get('user_id');
    }
    
    /**
     * Set current user
     */
    public static function setUser($userId)
    {
        self::set('user_id', $userId);
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return self::getUserId() !== null;
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return self::get('is_admin', false);
    }
}

