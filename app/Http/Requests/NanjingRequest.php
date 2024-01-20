<?php

namespace App\Http\Requests;

use App\Models\Factory;
use App\Models\Number;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NanjingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'group.*.tool_id' => 'required',
            'group.*.factory_id' => 'required',
            'group.*.number_id' => 'required',
            'group.*.verification_date' => 'required',
            'group.*.standard_id' => 'required',
            'group.*.certificate_no' => 'unique:certificates,certificate_no',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "group.*.tool_id.required" => "请选择器具名称",
            "group.*.factory_id.required" => "请选择生产厂家",
            "group.*.number_id.required" => "请选择出厂编号",
            "group.*.verification_date.required" => "请选择检定日期",
            "group.*.standard_id.required" => "请选择检定标准",
            "group.*.certificate_no.unique" => "证书已录入",
        ];
    }
}
