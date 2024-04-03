<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Number;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NumberController extends Controller
{
    public function create()
    {
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
        $factory_id = $_GET['id'];
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        return view('tools.number.create', compact('states', 'factory_id', 'name'));
    }

    public function store(NumberRequest $request)
    {
        $arr['factory_id'] = $request->factory_id;
        $arr['number'] = $request->number;
        $arr['remark'] = $request->remark;
        $arr['state_id'] = $request->state_id;
        return !!Number::create($arr);
    }

    public function edit(Number $number)
    {
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
        return view('tools.number.edit', compact('number', 'states'));
    }

    public function update(NumberRequest $request, Number $number)
    {
        $number->number = $request->number;
        $number->remark = $request->remark;
        $number->state_id = $request->state_id;
        $state = Parameter::find($request->state_id);
        if ($state->name == '在用') {
            if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->exists()) {
                return !!$number->save();
            } else {
                return '无可用证书,无法修改状态';
            }
        } elseif ($state->name == '借用') {
            return !!$number->save();
        } else {
            if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->exists()) {
                return '证书使用中,无法修改状态';
            } else {
                return !!$number->save();
            }
        }
    }

    public function show($number)
    {
        $certificate = Certificate::where('number_id', $number)->orderBy('validity_date', 'desc')->first();
        if ($certificate) {
            $certificates = DB::table('certificates_views')->where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->orderBy('verification_date', 'desc')->get();
            foreach ($certificates as $c) {
                $c->path = "storage/jpg/$c->id";
                if (!File::exists($c->path)) {
                    $this->pdf2jpg($c->id);
                }
                $c->files = Storage::files("\public\jpg\\$c->id");
                if ($certificate->id == $c->id) {
                    $disable = $c->id;
                }
                $dates[] = $c->start_date;
                $dates[] = $c->end_date;
            }
            $max = max($dates);
            $min = min($dates);
            return view('certificate.show', compact('certificates', 'disable', 'max', 'min'));
        }
        return false;
    }

    public function destroy($number)
    {
        $id = Certificate::selectRaw('GROUP_CONCAT(number_id) AS str')->whereIn('number_id', explode(',', $number))->first();
        if ($id->str) {
            $result = Number::selectRaw('GROUP_CONCAT(number) AS name')->whereIn('id', explode(',', $id->str))->first();
            return $result->name . '使用中,无法删除';
        } else {
            return !!Number::whereIn('id', explode(',', $number))->delete();
        }
    }
}
