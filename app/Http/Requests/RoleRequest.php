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
        $rules['name'] = [
            'required',
            Rule::unique('roles')->ignore($this->route('role')),
        ];
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
