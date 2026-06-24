<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'username'   => $this->username,
            'email'      => $this->email,
            'bio'        => $this->bio,
            'avatar_url' => $this->avatar
                ? asset('storage/' . $this->avatar)
                : null,
            'role'       => $this->role,
            'is_banned'  => $this->is_banned,
            'created_at' => $this->created_at,
        ];
    }
}