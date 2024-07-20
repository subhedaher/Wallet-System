<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'paymentProvider' => $this->payment_provider->name,
            'balance' => $this->balance,
            'details' => $this->details,
            'created_at' => $this->created_at
        ];
    }
}
