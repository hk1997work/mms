<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToolRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'model' => 'required',
            'limit' => 'required',
            'accuracy' => 'required',
            'cycle_id' => 'required',
            'abc_id' => 'required',
            'plan_id' => 'required',
        ];
        if ($this->method() === "PUT") {
            $rules['instrument'] = [
                'required',
                Rule::unique('tools')->where('model', $this->model)->where('limit', $this->limit)
                    ->where('accuracy', $this->accuracy)->ignore($this->route('tool')),
            ];
        } else {
            $rules['instrument'] = [
                'required',
                Rule::unique('tools')->where('model', $this->model)->where('limit', $this->limit)
                    ->where('accuracy', $this->accuracy),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'instrument.required' => '请输入器具名称',
            'instrument.unique' => '量具已存在',
            'model.required' => '请输入规格型号',
            'limit.required' => '请输入测量范围',
            'accuracy.required' => '请输入精确度',
            'cycle_id.required' => '请选择检定周期',
            'abc_id.required' => '请选择ABC类',
            'plan_id.required' => '请选择检定计划',
        ];
    }
}
