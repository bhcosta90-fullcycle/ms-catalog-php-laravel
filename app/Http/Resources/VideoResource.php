<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'year_launched' => $this->year_launched,
            'duration' => $this->duration,
            'opened' => $this->opened,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'video_file' => $this->video_file ?? null,
            'trailer_file' => $this->trailer_file ?? null,
            'banner_file' => $this->banner_file ?? null,
            'thumb_file' => $this->thumb_file ?? null,
            'thumb_half' => $this->thumb_half ?? null,
            'categories' => $this->categories ?? [],
            'genres' => $this->genres ?? [],
            'cast_members' => $this->cast_members ?? $this->castMembers ?? [],
        ];
    }
}
