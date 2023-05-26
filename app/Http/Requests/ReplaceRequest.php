<?php

namespace App\Http\Requests;

use App\Models\Factory;
use App\Models\Number;
use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReplaceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'category_id' => 'required',
            'tool_id' => 'required',
            'department_id' => 'required',
            'verification_date' => 'required',
            'validity_date' => 'required',
            'money' => 'required|numeric',
            'standard_id' => 'required',
        ];
        if (isset($this->department_id)) {
            $department = Parameter::where('id', $this->department_id)->first()->name;
            switch ($department) {
                case '市计量院':
                    $regex = '/^(\d{8}|\d{8}-\d{3}|\d{8}-\d{3}-\d{3})$/';
                    break;
                case '省计量院':
                    $regex = '/^[A-Z]{1}\d{4}-\d{7}$/';
                    break;
                case '上海研究院':
                    $regex = '/^\d{4}[A-Z]{1}\d{2}-\d{2}-\d{10}|\d{4}[A-Z]{1}\d{2}-\d{2}-\d{10}-\d{2}$/';
                    break;
                case '钢研纳克':
                    $regex = '/^[A-Z]{2}\d{2}[A-Z]{2}\d{6}$/';
                    break;
                case '镇江计量院':
                    $regex = '/^\d{9}-\d{3}$/';
                    break;
                default:
                    $regex = '/^\d{12}$/';
            }
        } else {
            $regex = '/^\d{12}$/';
        }
        $rules['certificate_no'] = [
            'required',
            Rule::unique('certificates'),
            "regex:$regex",
        ];
        if (Factory::where('tool_id', $this->tool_id)->where('id', $this->factory_id)->exists() == false) {
            $rules['factory_id'] = [
                'required',
                Rule::unique('factories', 'factory')->where('tool_id', $this->tool_id),
            ];
        } else {
            $rules['factory_id'] = ['required'];
        }
        if (Number::where('factory_id', $this->factory_id)->where('id', $this->number_id)->exists() == false) {
            $rules['number_id'] = [
                'required',
                Rule::unique('numbers', 'number')->where('factory_id', $this->factory_id),
            ];
        } else {
            $rules['number_id'] = ['required'];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "category_id.required" => "请选择证书类型",
            "tool_id.required" => "请选择器具名称",
            "factory_id.required" => "请填写生产厂家",
            "factory_id.unique" => "生产厂家已存在",
            "number_id.required" => "请填写出厂编号",
            "number_id.unique" => "出厂编号已存在",
            "certificate_no.required" => "请输入统一编号",
            "certificate_no.unique" => "统一编号已存在",
            "certificate_no.regex" => "统一编号格式不正确",
            "department_id.required" => "请选择检定部门",
            "verification_date.required" => "请选择检定日期",
            "validity_date.required" => "有效期加载失败",
            "money.required" => "请输入检定费用",
            "money.numeric" => "检定费用格式不正确",
            "standard_id.required" => "请选择检定标准",
        ];
    }
}
