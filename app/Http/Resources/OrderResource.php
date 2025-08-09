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
            'code' => $this->code,
            'quantity' => $this->quantity,
            'shipping' => $this->shipping,
            'status' => $this->status->getLabel(),
            'status_color' => $this->status->getColor(),
            'sub_total' => $this->sub_total,
            'referral_discount' => $this->referral_discount,
            'discount' => $this->discount,
            'tax_rate' => $this->tax_rate,
            'tax_value' => $this->tax_value,
            'products' => OrderProductResource::collection($this->whenLoaded('order_products')),
            'refunds' => $this->refunds->map(function ($refund) {
                return [
                    'id' => $refund->id,
                    'product_id' => $refund->order_product_id,
                    'amount' => $refund->amount,
                    'status' => $refund->status,
                    'created_at' => $refund->created_at->toDateTimeString(),
                ];
            }),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'total' => $this->total,
            'user' => $this->data->user,
            'created_at' => $this->created_at,
            'createdAtRelative' => $this->created_at->locale('en_EN')->diffForHumans(['parts' => 1])
        ];
    }
}
