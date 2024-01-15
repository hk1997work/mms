<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NumberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'state_id' => 'required',
            'number' => [
                'required',
                Rule::unique('numbers')->where('factory_id', $this->factory_id)->ignore($this->route('number')),
            ],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "number.required" => "请输入出厂编号",
            "number.unique" => "出厂编号已存在",
            "state_id.required" => "请选择管理状态",
        ];
    }
}
