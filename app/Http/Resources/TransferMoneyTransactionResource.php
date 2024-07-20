<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferMoneyTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
            'send_user_id' => $this->send_user_id ==  auth()->user()->id ? 'me' :  $this->send_user_id,
            'receive_user_id ' => $this->receive_user_id ==  auth()->user()->id ? 'me' :  $this->receive_user_id,
            'details ' => $this->details,
            'created_at' => $this->created_at
        ];
    }
}