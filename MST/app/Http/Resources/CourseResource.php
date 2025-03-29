<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'duration' => $this->duration,
            'difficulty' => $this->difficulty_level,
            'status' => $this->status,
            'category' => $this->category->name,
            'teacher' => $this->teacher->name,
            'tags' => $this->tags->pluck('name'),
        ];
    }
}
