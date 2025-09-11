<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'position_id' => 'required',
            'sn' => [
                'required',
                Rule::unique('certificates')->where('position_id', $this->position_id),
            ],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "position_id.required" => "请选择岗位",
            "sn.required" => "请输入序号",
            "sn.unique" => "序号已存在",
        ];
    }
}
