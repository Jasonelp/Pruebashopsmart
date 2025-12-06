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
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'formatted_price' => $this->formatted_price,
            'stock' => $this->stock,
            'in_stock' => $this->isInStock(),
            'is_available' => $this->isAvailable(),
            'image' => $this->image_url,
            'thumbnail' => $this->thumbnail_url,
            'specifications' => $this->specifications,

            // Relaciones
            'category' => new CategoryResource($this->whenLoaded('category')),
            'vendor' => new UserResource($this->whenLoaded('user')),

            // Estadísticas
            'reviews_count' => $this->whenCounted('reviews'),
            'average_rating' => $this->when(
                isset($this->reviews_avg_rating),
                fn() => round($this->reviews_avg_rating, 1)
            ),
            'total_sold' => $this->when(
                isset($this->total_sold),
                fn() => $this->total_sold
            ),

            // Reviews (si están cargadas)
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
