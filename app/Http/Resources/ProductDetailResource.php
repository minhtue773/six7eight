<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'stock' => optional($this->variants)->sum('stock'),
            'image' => optional($this->mainImage)->image_url,
            'images' => $this->images->pluck('image_url'),
            'variants_by_color' => $this->variants
                ->groupBy('color_id')
                ->map(function ($items, $colorId) {
                    $color = $items->first()->color;

                    return [
                        'color_id' => $colorId,
                        'color_name' => optional($color)->name,
                        'hex_code' => optional($color)->hex_code,
                        'sizes' => $items->map(function ($variant) {
                            return [
                                'size_id' => $variant->size_id,
                                'size' => $variant->size->name,
                                'price' => $variant->price,
                                'stock' => $variant->stock,
                                'sku' => $variant->sku,
                            ];
                        })->values()
                    ];
                })->values(),
            'view' => $this->view,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            'reviews' => $this->reviews,
        ];
    }
}