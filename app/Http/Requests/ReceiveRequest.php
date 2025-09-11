<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'receiver' => 'required',
            'receiving_date' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "receiver.required" => "请输入领用人",
            "receiving_date.required" => "请输入领用日期",
        ];
    }
}
