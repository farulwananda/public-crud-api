<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSingleResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => $this->image_url,
            'price' => number_format($this->price, 2, '.', '.'),
            'actual_price' => $this->price,
            'description' => $this->description,
            'created_at' => $this->created_at->format('d F Y'),
            'category' => new CategoryResource($this->category),
        ];
    }
}
