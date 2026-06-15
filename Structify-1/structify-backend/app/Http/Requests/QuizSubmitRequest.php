<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id'                  => 'required|exists:topics,id',
            'answers'                   => 'required|array|min:1',
            'answers.*.question_id'     => 'required|exists:questions,id',
            'answers.*.selected_answer' => 'required|in:A,B,C,D',
        ];
    }
}