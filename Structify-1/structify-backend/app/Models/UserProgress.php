<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id', 'topic_id', 'is_completed',
    'completion_percentage', 'last_visited_at',
];

protected $casts = [
    'last_visited_at' => 'datetime',
    'is_completed' => 'boolean',
];

public function user() {
    return $this->belongsTo(User::class);
}
public function topic() {
    return $this->belongsTo(Topic::class);
}
    
}
