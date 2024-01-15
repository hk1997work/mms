<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                Rule::unique('permissions')->ignore($this->route('permission')),
            ],
            'description' => [
                'required',
                Rule::unique('permissions')->where('pid', $this->pid)->ignore($this->route('permission')),
            ]
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入权限名称",
            "name.unique" => "权限名称已存在",
            "description.required" => "请输入权限描述",
            "description.unique" => "权限描述已存在",
        ];
    }
}
