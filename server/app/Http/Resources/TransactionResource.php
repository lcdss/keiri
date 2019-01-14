<?php

namespace App\Http\Resources;

class TransactionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => abs($this->value),
            'operationType' => $this->value > 0 ? 'I' : 'O',
            'note' => $this->note,
            'code' => $this->code,
            'paymentMethod' => $this->payment_method,
            'issuedAt' => $this->issued_at,
            'expiresAt' => $this->expires_at,
            'paidAt' => $this->paid_at,
            'isPaid' => $this->is_paid,
            'userId' => $this->user_id,
            'personId' => $this->person_id,
            'companyId' => $this->company_id,
            'categoryId' => $this->category_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'person' => new PersonResource($this->whenLoaded('person')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'files' => MediaResource::collection($this->whenLoaded('media')),
            'parent' => new TransactionResource($this->whenLoaded('transaction')),
        ];
    }
}
