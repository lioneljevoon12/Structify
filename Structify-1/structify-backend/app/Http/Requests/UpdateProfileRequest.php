<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'sometimes|string|max:100',
            'username' => [
                'sometimes', 'string', 'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'bio'      => 'sometimes|nullable|string|max:500',
            'avatar'   => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash, dan underscore.',
            'username.unique'     => 'Username sudah dipakai.',
            'avatar.image'        => 'File harus berupa gambar.',
            'avatar.max'          => 'Ukuran foto maksimal 2MB.',
        ];
    }
}