<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'content' => $this->content,
            'status'  => $this->status,
            'author'  => [
                'id'   => $this->author->id ?? null,
                'name' => $this->author->name ?? null,
            ],
            'categories' => $this->categories->pluck('name'),
            'tags'       => $this->tags->pluck('name'),
            'featured_image' => $this->featured_image
                ? url('storage/' . $this->featured_image)
                : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
