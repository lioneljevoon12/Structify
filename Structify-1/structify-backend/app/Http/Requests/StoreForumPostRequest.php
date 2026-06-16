<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForumPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id' => 'required|exists:topics,id',
            'title'    => 'required|string|max:255',
            'body'     => 'required|string|min:10',
            'tags'     => 'sometimes|array',
            'tags.*'   => 'string|max:50',
        ];
    }
}