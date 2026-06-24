<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'track_id'      => $this->track_id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'display_order' => $this->display_order,
            'track'         => new TrackResource($this->whenLoaded('track')),
        ];
    }
}