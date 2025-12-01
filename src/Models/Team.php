<?php

namespace CTFd\Models;

/**
 * Team Model
 */
class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = [
        'name', 'email', 'password', 'secret',
        'website', 'affiliation', 'country', 'bracket_id',
        'hidden', 'banned', 'captain_id', 'created'
    ];
    
    /**
     * Get team members
     */
    public function getMembers()
    {
        return User::where('team_id', '=', $this->attributes['id'] ?? 0);
    }
    
    /**
     * Get team's solves
     */
    public function getSolves()
    {
        $memberIds = array_map(function($member) {
            return $member->id;
        }, $this->getMembers());
        
        if (empty($memberIds)) {
            return [];
        }
        
        $db = \CTFd\Database::getInstance();
        $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
        $sql = "SELECT * FROM solves WHERE user_id IN ({$placeholders})";
        return $db->fetchAll($sql, $memberIds);
    }
    
    /**
     * Get team's score
     */
    public function getScore()
    {
        $members = $this->getMembers();
        $score = 0;
        foreach ($members as $member) {
            $user = User::find($member->id);
            if ($user) {
                $score += $user->getScore();
            }
        }
        return $score;
    }
}

