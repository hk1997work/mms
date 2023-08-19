<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrintRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'row' => ['required', 'regex:/^(10|[1-9])$/'],
            'column' => 'required|regex:/^[1-5]{1}$/',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "row.required" => "请输入起始行",
            "row.regex" => "起始行格式错误",
            "column.required" => "请输入起始列",
            "column.regex" => "起始列格式错误",
        ];
    }
}
