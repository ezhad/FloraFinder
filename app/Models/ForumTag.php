<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum_Tag extends Model
{
    /** @use HasFactory<\Database\Factories\ForumTagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function forum_threads()
    {
        return $this->belongsToMany(Forum_Thread::class, 'forum_thread_id');
    }
}
