<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'body'          => $this->body,
            'upvotes'       => $this->upvotes,
            'is_upvoted'    => $this->when(isset($this->is_upvoted), $this->is_upvoted),
            'replies_count' => $this->whenCounted('replies'),
            'user'          => new UserResource($this->whenLoaded('user')),
            'topic'         => new TopicResource($this->whenLoaded('topic')),
            'tags'          => TagResource::collection($this->whenLoaded('tags')),
            'replies'       => ForumPostResource::collection($this->whenLoaded('replies')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}