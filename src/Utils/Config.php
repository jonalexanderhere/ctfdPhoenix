<?php

namespace CTFd\Utils;

use CTFd\Models\Config as ConfigModel;

/**
 * Configuration utilities
 */
class Config
{
    /**
     * Get config value
     */
    public static function get($key, $default = null)
    {
        // Try environment variable first
        $envKey = strtoupper($key);
        if (isset($_ENV[$envKey])) {
            return $_ENV[$envKey];
        }
        
        // Try database
        return ConfigModel::get($key, $default);
    }
    
    /**
     * Set config value
     */
    public static function set($key, $value)
    {
        ConfigModel::set($key, $value);
    }
    
    /**
     * Check if CTF is set up
     */
    public static function isSetup()
    {
        return self::get('ctf_name') !== null;
    }
    
    /**
     * Get CTF name
     */
    public static function getCTFName()
    {
        return self::get('ctf_name', 'CTFd');
    }
    
    /**
     * Get CTF theme
     */
    public static function getCTFTheme()
    {
        return self::get('ctf_theme', 'core');
    }
}

