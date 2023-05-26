<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() === "PUT") {
            $rules['name'] = [
                'required',
                Rule::unique('positions')->where('pid', $this->pid)->ignore($this->route('position')),
            ];
            if (isset($this->code)) {
                $rules['code'] = [
                    Rule::unique('positions')->ignore($this->route('position')),
                ];
            }
        } else {
            $rules['name'] = [
                'required',
                Rule::unique('positions')->where('pid', $this->pid),
            ];
            if (isset($this->code)) {
                $rules['code'] = [
                    Rule::unique('positions'),
                ];
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入岗位名称",
            "name.unique" => "岗位已存在",
            "code.unique" => "编号已存在",
        ];
    }
}
