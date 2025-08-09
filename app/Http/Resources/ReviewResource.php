<?php

namespace App\Http\Resources;

use App\Models\Attribute\ColorAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Color;

class ReviewResource extends JsonResource
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
            'company' => $this->company,
            'rating' => $this->rating,
            'review' => $this->review,
            'featured' => $this->featured,
            'user_id' => $this->user_id,
        ];
    }
}
