<?php

namespace App\Http\Requests;

class PersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:people',
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = 'required|unique:people,id,'.$this->id;
        }

        return $rules;
    }
}
