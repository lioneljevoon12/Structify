<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id'      => 'required|exists:topics,id',
            'question_text' => 'required|string',
            'option_a'      => 'required|string',
            'option_b'      => 'required|string',
            'option_c'      => 'required|string',
            'option_d'      => 'required|string',
            'correct_answer'=> 'required|in:A,B,C,D',
            'timer_seconds' => 'required|integer|min:10|max:300',
        ];
    }
}