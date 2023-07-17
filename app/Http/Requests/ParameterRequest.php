<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParameterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules['name'] = [
            'required',
            Rule::unique('parameters')->ignore($this->route('parameter')),
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入参数名称",
            "name.unique" => "参数已存在",
        ];
    }
}
