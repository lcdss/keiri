<?php

namespace App\Http\Requests;

class CompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:companies',
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = 'required|unique:companies,id,'.$this->id;
        }

        return $rules;
    }
}
