<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id'                  => 'required|integer|exists:topics,id',
            'answers'                   => 'required|array|min:1',
            // Soal tidak boleh dikirim dua kali dan harus milik topik yang sama,
            // supaya skor tidak bisa digelembungkan.
            'answers.*.question_id'     => [
                'required',
                'integer',
                'distinct',
                Rule::exists('questions', 'id')->where('topic_id', (int) $this->input('topic_id')),
            ],
            'answers.*.selected_answer' => 'required|in:A,B,C,D',
        ];
    }
}