<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category' => optional($this->category)->name,
            'brand' => optional($this->brand)->name,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'gender' => $this->gender,
            'discount_percent' => $this->discount_percent,
            'price_min' => optional($this->variants)->min('price'),
            'price_max' => optional($this->variants)->max('price'),
            'image' => optional($this->mainImage)->image_url,
            'stock' => optional($this->variants)->sum('stock'),
            'view' => $this->view,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}