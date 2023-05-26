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
            'tool_id.*' => 'required',
            'verification_date.*' => 'required',
            'standard_id.*' => 'required',
            'certificate_no.*'=>'unique:certificates,certificate_no',
        ];
        if (Factory::where('tool_id', $this->tool_id)->where('id', $this->factory_id)->exists() == false) {
            $rules['factory_id.*'] = [
                'required',
                Rule::unique('factories', 'factory')->where('tool_id', $this->tool_id),
            ];
        }
        if (Number::where('factory_id', $this->factory_id)->where('id', $this->number_id)->exists() == false) {
            $rules['number_id.*'] = [
                'required',
                Rule::unique('numbers', 'number')->where('factory_id', $this->factory_id),
            ];
        } else {
            $rules['factory_id.*'] = [
                'required',
            ];
            $rules['number_id.*'] = [
                'required',
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "tool_id.*.required" => "请选择器具名称",
            "factory_id.*.required" => "请填写生产厂家",
            "factory_id.*.unique" => "生产厂家已存在",
            "number_id.*.required" => "请填写出厂编号",
            "number_id.*.unique" => "出厂编号已存在",
            "verification_date.*.required" => "请选择检定日期",
            "standard_id.*.required" => "请选择检定标准",
            "certificate_no.*.unique" => "该证书已录入",
        ];
    }
}
