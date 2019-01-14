<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id);
                }),
            ],
            'parent_id' => 'nullable|exists:categories,id'
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = [
                'required',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id ?? null);
                })->ignore($this->id),
            ];
        }

        return $rules;
    }
}
