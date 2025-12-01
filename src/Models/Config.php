<?php

namespace CTFd\Models;

/**
 * Config Model
 */
class Config extends Model
{
    protected $table = 'config';
    protected $fillable = ['key', 'value'];
    
    /**
     * Get config value
     */
    public static function get($key, $default = null)
    {
        $config = self::where('key', '=', $key);
        if (!empty($config)) {
            return $config[0]->value;
        }
        return $default;
    }
    
    /**
     * Set config value
     */
    public static function set($key, $value)
    {
        $configs = self::where('key', '=', $key);
        if (!empty($configs)) {
            $config = $configs[0];
            $config->value = $value;
            $config->save();
        } else {
            $config = new self(['key' => $key, 'value' => $value]);
            $config->save();
        }
    }
}

