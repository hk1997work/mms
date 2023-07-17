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
        $rules['name'] = [
            'required',
            Rule::unique('permissions')->ignore($this->route('permission')),
        ];
        $rules['description'] = [
            'required',
            Rule::unique('permissions')->where('pid', $this->pid)->ignore($this->route('permission')),
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入角色名称",
            "name.unique" => "角色名称已存在",
            "description.required" => "请输入角色描述",
            "description.unique" => "角色描述已存在",
        ];
    }
}
