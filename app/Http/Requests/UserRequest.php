<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'password' => 'required',
        ];
        if ($this->method() === "PUT") {
            $rules['username'] = [
                'required',
                Rule::unique('users')->ignore($this->route('user')),
            ];
        } else {
            $rules['username'] = [
                'required',
                Rule::unique('users'),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "username.required" => "请输入用户名",
            "username.unique" => "用户名已存在",
            "password.required" => "请输入密码",
        ];
    }
}
