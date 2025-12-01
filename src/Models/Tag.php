<?php

namespace CTFd\Models;

/**
 * Tag Model
 */
class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = [
        'challenge_id', 'value'
    ];
}

