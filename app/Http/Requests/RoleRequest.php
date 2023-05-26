<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
                Rule::unique('admin_roles')->ignore($this->route('role')),
            ];
        } else {
            $rules['name'] = [
                'required',
                Rule::unique('admin_roles'),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "name.required" => "请输入角色名称",
            "name.unique" => "角色名称已存在",
        ];
    }
}
