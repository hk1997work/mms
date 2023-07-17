<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StandardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules['name'] = [
            'required',
            Rule::unique('standards')->ignore($this->route('standard')),
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入标准",
            "name.unique" => "标准已存在",
        ];
    }
}
