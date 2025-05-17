<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum_Thread extends Model
{
    /** @use HasFactory<\Database\Factories\ForumThreadFactory> */
    use HasFactory;

    protected $fillable = [

        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function forum_posts()
    {
        return $this->hasMany(Forum_Post::class, 'forum_post_id');
    }

    public function forum_tags()
    {
        return $this->belongsToMany(Forum_Tag::class, 'forum_tag_id');
    }
}
