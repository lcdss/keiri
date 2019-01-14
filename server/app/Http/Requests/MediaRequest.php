<?php

namespace App\Http\Requests;

class MediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:pdf,png,jpeg,bmp',
            'tag_id' => 'exists:tags,id'
        ];
    }
}
