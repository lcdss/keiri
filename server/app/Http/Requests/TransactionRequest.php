<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => [
                'max:50',
                Rule::unique('transactions')->whereNull('parent_id'),
            ],
            'value' => 'required|numeric',
            'note' => 'max:250',
            'issued_at' => 'required|date',
            'expires_at' => 'required|date',
            'paid_at' => 'nullable|date',
            'is_paid' => 'boolean',
            'payment_method' => 'required|in:BB,CC,CD,TB,DN,CH,DA,NP',
            'user_id' => 'required|exists:users,id',
            'person_id' => 'required|exists:people,id',
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'parent_id' => 'exists:transactions,id',
            'tags.*' => 'required|exists:tags,id',
        ];

        if ($this->method() === 'PUT') {
            $rules['code'] = [
                'max:50',
                Rule::unique('transactions')->whereNull('parent_id')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
