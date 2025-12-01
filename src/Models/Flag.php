<?php

namespace CTFd\Models;

/**
 * Flag Model
 */
class Flag extends Model
{
    protected $table = 'flags';
    protected $fillable = [
        'challenge_id', 'type', 'content', 'data'
    ];
}

