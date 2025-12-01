<?php

namespace CTFd\Models;

/**
 * Challenge Model
 */
class Challenge extends Model
{
    protected $table = 'challenges';
    protected $fillable = [
        'name', 'description', 'attribution', 'connection_info',
        'next_id', 'max_attempts', 'value', 'category', 'type',
        'state', 'logic', 'initial', 'minimum', 'decay', 'function',
        'requirements'
    ];
    
    /**
     * Get challenge flags
     */
    public function getFlags()
    {
        return Flag::where('challenge_id', '=', $this->attributes['id'] ?? 0);
    }
    
    /**
     * Get challenge hints
     */
    public function getHints()
    {
        return Hint::where('challenge_id', '=', $this->attributes['id'] ?? 0);
    }
    
    /**
     * Get challenge tags
     */
    public function getTags()
    {
        return Tag::where('challenge_id', '=', $this->attributes['id'] ?? 0);
    }
    
    /**
     * Check if challenge is solved by user
     */
    public function isSolvedByUser($userId)
    {
        $solves = Solve::where('challenge_id', '=', $this->attributes['id'] ?? 0);
        foreach ($solves as $solve) {
            if ($solve->user_id == $userId) {
                return true;
            }
        }
        return false;
    }
}

