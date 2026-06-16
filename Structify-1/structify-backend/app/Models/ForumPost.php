<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'topic_id', 'parent_id', 'title', 'body', 'upvotes',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function topic() {
        return $this->belongsTo(Topic::class);
    }
    public function parent() {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }
    public function replies() {
        return $this->hasMany(ForumPost::class, 'parent_id')
                    ->with(['user:id,name,username,avatar', 'nestedReplies'])
                    ->orderBy('created_at');
    }
    public function nestedReplies() {
        return $this->hasMany(ForumPost::class, 'parent_id')
                    ->with(['user:id,name,username,avatar', 'nestedReplies'])
                    ->orderBy('created_at');
    }
    public function tags() {
        return $this->belongsToMany(Tag::class, 'forum_post_tags');
    }

    public function upvoters()
    {
        return $this->belongsToMany(User::class, 'forum_post_upvotes')
                    ->withTimestamps();
    }
}