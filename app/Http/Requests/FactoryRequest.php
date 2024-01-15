<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'factory' => [
                'required',
                Rule::unique('factories')->where('tool_id', $this->tool_id)->ignore($this->route('factory')),
            ],
            'fullname' => [
                'required',
                Rule::unique('factories')->where('tool_id', $this->tool_id)->ignore($this->route('factory')),
            ]
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "factory.required" => "请输入生产厂家",
            "factory.unique" => "生产厂家已存在",
            "fullname.required" => "请输入厂家全称",
            "fullname.unique" => "厂家全称已存在",
        ];
    }
}
