<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
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
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'quantity' => $this->pivot->quantity,
            'price' => (float) $this->pivot->price,
            'subtotal' => (float) ($this->pivot->price * $this->pivot->quantity),
            'formatted_price' => 'S/ ' . number_format($this->pivot->price, 2),
            'formatted_subtotal' => 'S/ ' . number_format($this->pivot->price * $this->pivot->quantity, 2),
        ];
    }
}
