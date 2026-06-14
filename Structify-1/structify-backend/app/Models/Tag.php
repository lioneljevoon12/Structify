<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

public function forumPosts() {
    return $this->belongsToMany(ForumPost::class, 'forum_post_tags');
}
}
