<?php

namespace CTFd\Models;

use CTFd\Utils\Crypto;

/**
 * User Model
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'type', 'secret',
        'website', 'affiliation', 'country', 'bracket_id',
        'hidden', 'banned', 'verified', 'language',
        'change_password', 'team_id', 'created'
    ];
    
    /**
     * Hash password before saving
     */
    public function setPassword($password)
    {
        $this->attributes['password'] = Crypto::hashPassword($password);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password)
    {
        return Crypto::verifyPassword($password, $this->attributes['password'] ?? '');
    }
    
    /**
     * Get user's solves
     */
    public function getSolves()
    {
        return Solve::where('user_id', '=', $this->attributes['id'] ?? 0);
    }
    
    /**
     * Get user's score
     */
    public function getScore()
    {
        $db = \CTFd\Database::getInstance();
        $sql = "SELECT SUM(c.value) as score 
                FROM solves s 
                JOIN challenges c ON s.challenge_id = c.id 
                WHERE s.user_id = :user_id";
        $result = $db->fetchOne($sql, ['user_id' => $this->attributes['id'] ?? 0]);
        return (int)($result['score'] ?? 0);
    }
}

