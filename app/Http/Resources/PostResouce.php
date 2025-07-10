<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResouce extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->user->name,
            'category' => $this->category->name,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'view' => $this->view,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}