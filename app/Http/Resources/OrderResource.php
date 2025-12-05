<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => str_pad($this->id, 8, '0', STR_PAD_LEFT),
            'status' => $this->status,
            'total' => (float) $this->total,
            'formatted_total' => 'S/ ' . number_format($this->total, 2),
            'shipping_address' => $this->shipping_address,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            'cancellation_reason' => $this->cancellation_reason,

            // Relaciones
            'user' => new UserResource($this->whenLoaded('user')),
            'products' => OrderProductResource::collection($this->whenLoaded('products')),

            // Contadores
            'items_count' => $this->whenLoaded('products', function () {
                return $this->products->sum('pivot.quantity');
            }),

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
