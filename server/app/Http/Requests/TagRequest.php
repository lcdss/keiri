<?php

namespace App\Http\Requests;

class TagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:tags',
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = 'required|unique:tags,id,'.$this->id;
        }

        return $rules;
    }
}
