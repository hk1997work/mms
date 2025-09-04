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
        if ($this->level == 1) {
            $rules = [
                'name' => [
                    'required',
                    Rule::unique('numbers')->where('pid', $this->pid)->ignore($this->route('number')),
                ],
                'remark' => [
                    'required',
                    Rule::unique('numbers')->where('pid', $this->pid)->ignore($this->route('number')),
                ]
            ];
        }else{
            $rules = [
                'state_id' => 'required',
                'name' => [
                    'required',
                    Rule::unique('numbers')->where('pid', $this->pid)->ignore($this->route('number')),
                ],
            ];
        }
        return $rules;
    }

    public function messages()
    {
        if ($this->level == 1) {
            return [
                "name.required" => "请输入生产厂家",
                "name.unique" => "生产厂家已存在",
                "remark.required" => "请输入厂家全称",
                "remark.unique" => "厂家全称已存在",
            ];
        }else{
            return [
                "name.required" => "请输入出厂编号",
                "name.unique" => "出厂编号已存在",
                "state_id.required" => "请选择管理状态",
            ];
        }
    }
}
