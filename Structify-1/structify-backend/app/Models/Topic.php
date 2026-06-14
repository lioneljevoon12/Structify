<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = ['track_id', 'title', 'slug', 'description', 'display_order'];

    public function track() {
        return $this->belongsTo(Track::class);
    }
    public function questions() {
        return $this->hasMany(Question::class);
    }
    public function forumPosts() {
        return $this->hasMany(ForumPost::class);
    }
    public function userProgress() {
        return $this->hasMany(UserProgress::class);
    }

}
