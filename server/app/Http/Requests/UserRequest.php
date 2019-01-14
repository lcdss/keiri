<?php

namespace App\Http\Requests;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
        ];

        if ($this->method() === 'PUT') {
            $rules['email'] = 'required|unique:users,id,'.$this->id;
            $rules['password'] = 'min:8';
        }

        return $rules;
    }
}
