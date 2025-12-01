<?php

namespace CTFd\Models;

use CTFd\Utils\FirstBlood;

/**
 * Solve Model
 */
class Solve extends Model
{
    protected $table = 'solves';
    protected $fillable = [
        'challenge_id', 'user_id', 'team_id', 'ip', 'date'
    ];
    
    /**
     * Override save to trigger First Blood notification
     */
    public function save()
    {
        // Check if this is first solve before saving
        $isFirstBlood = false;
        if (isset($this->attributes['challenge_id'])) {
            $isFirstBlood = FirstBlood::isFirstSolve($this->attributes['challenge_id']);
        }
        
        // Save the solve
        parent::save();
        
        // Send notification if first blood
        if ($isFirstBlood && isset($this->attributes['user_id']) && isset($this->attributes['challenge_id'])) {
            FirstBlood::sendNotification(
                $this->attributes['user_id'],
                $this->attributes['challenge_id']
            );
        }
    }
}

