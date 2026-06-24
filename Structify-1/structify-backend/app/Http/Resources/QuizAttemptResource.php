<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'score'           => $this->score,
            'total_questions' => $this->total_questions,
            'correct_answers' => $this->correct_answers,
            'completed_at'    => $this->completed_at,
            'topic'           => new TopicResource($this->whenLoaded('topic')),
            'answers'         => $this->whenLoaded('answers'),
        ];
    }
}