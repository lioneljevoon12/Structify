<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'topic_id'       => $this->topic_id,
            'question_text'  => $this->question_text,
            'option_a'       => $this->option_a,
            'option_b'       => $this->option_b,
            'option_c'       => $this->option_c,
            'option_d'       => $this->option_d,
            'timer_seconds'  => $this->timer_seconds,
            // correct_answer sengaja tidak dimasukkan — hanya untuk admin
        ];
    }
}