<?php

namespace App\Http\Requests;

use App\Models\Factory;
use App\Models\Number;
use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->type != 'spare') {
            if (isset($this->department_id)) {
                $department = Parameter::where('id', $this->department_id)->first()->name;
                switch ($department) {
                    case '市计量院':
                        $regex = '/^(\d{8}|\d{8}-\d{3}|\d{8}-\d{3}-\d{3})|\d{8}A\d{3}$/';
                        break;
                    case '省计量院':
                        $regex = '/^[A-Z]{1}\d{4}-\d{7}|R[A-Z]{1}\d{4}-\d{7}$/';
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
                    case '中国研究院':
                        $regex = '/^\d{12}$/';
                        break;
                    default:
                        $regex = '/^\d{12}$/';
                }
            } else {
                $regex = '/^\d{12}$/';
            }
            $rules = [
                'position_id' => 'required',
                'sn' => [
                    'required',
                    Rule::unique('certificates')->where('valid', 1)->where('position_id', $this->position_id)->ignore($this->route('certificate')),
                ],
                'tool_id' => 'required',
                'factory_id' => 'required',
                'number_id' => 'required',
                'department_id' => 'required',
                'category_id' => 'required',
                'verification_date' => 'required',
                'validity_date' => 'required',
                'certificate_no' => [
                    'required',
                    Rule::unique('certificates')->ignore($this->route('certificate')),
                    "regex:$regex",
                ],
                'standard_id' => 'required',
                'start' => 'required|numeric',
                'times' => 'required|numeric',
            ];
            return $rules;
        }
        return [];
    }

    public function messages()
    {
        return [
            "position_id.required" => "请选择岗位",
            "sn.required" => "请输入序号",
            "sn.unique" => "序号已存在",
            "tool_id.required" => "请选择器具名称",
            "factory_id.required" => "请填写生产厂家",
            "number_id.required" => "请填写出厂编号",
            "department_id.required" => "请选择检定部门",
            "category_id.required" => "请选择证书类型",
            "verification_date.required" => "请选择检定日期",
            "validity_date.required" => "有效期加载失败",
            "certificate_no.required" => "请输入统一编号",
            "certificate_no.unique" => "统一编号已存在",
            "certificate_no.regex" => "统一编号格式不正确",
            "standard_id.required" => "请选择检定标准",
            "start.required" => "请输入启用时间",
            "start.numeric" => "启用时间格式不正确",
            "times.required" => "请输入检定次数",
            "times.numeric" => "检定次数格式不正确",
        ];
    }
}
