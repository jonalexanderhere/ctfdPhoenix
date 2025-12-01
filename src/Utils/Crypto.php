<?php

namespace CTFd\Utils;

/**
 * Cryptographic utilities
 */
class Crypto
{
    /**
     * Hash password (using bcrypt)
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate random string
     */
    public static function randomString($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * SHA256 hash
     */
    public static function sha256($data)
    {
        return hash('sha256', $data);
    }
}

