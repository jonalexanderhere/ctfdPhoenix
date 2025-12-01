<?php

namespace CTFd\Models;

/**
 * Hint Model
 */
class Hint extends Model
{
    protected $table = 'hints';
    protected $fillable = [
        'title', 'type', 'challenge_id', 'content', 'cost', 'requirements'
    ];
}

