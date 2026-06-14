<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
    'quiz_attempt_id', 'question_id', 'selected_answer', 'is_correct',
];

    public function quizAttempt() {
        return $this->belongsTo(QuizAttempt::class);
    }
    public function question() {
        return $this->belongsTo(Question::class);
    }
    
}
