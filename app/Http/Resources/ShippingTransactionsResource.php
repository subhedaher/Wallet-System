<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'shippingPoint' => $this->shipping_point->full_name,
            'balance' => $this->balance,
            'employeeName' => $this->employee_name,
            'created_at' => $this->created_at
        ];
    }
}
