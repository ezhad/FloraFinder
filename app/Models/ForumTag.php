<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTag extends Model
{
    /** @use HasFactory<\Database\Factories\ForumTagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function forumThreads()
    {
        return $this->belongsToMany(ForumThread::class, 'forum_thread_id');
    }
}
