<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Http\Requests\FactoryRequest;
use App\Http\Requests\NumberRequest;
use App\Http\Requests\ReplaceRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Factory;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\PositionsView;
use App\Models\Standard;
use App\Models\StandardsView;
use App\Models\Tool;
use App\Models\ToolsView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CertificateController extends Controller
{
    public function index()
    {
        $type = Position::find(isset($_GET['id']) ? $_GET['id'] : Position::where('level', 2)->orderBy('sort')->first()->id);
        $types = Position::orderBy('sort')->get();
        return view("certificate.index", compact('types', 'type'));
    }

    public function list()
    {
        $arr = [
            "active" => ['valid', '1'],
            "invalid" => ['valid', '0'],
            "deactive" => ['state', '封存'],
            "scrap" => ['state', '报废']
        ];
        $path = $_GET['path'];
        $type_id = isset($_GET['id']) ? $_GET['id'] : Position::where('level', 2)->orderBy('sort')->first()->id;
        $data = CertificatesView::select('id', 'order', 'position', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'remark')->where($arr[$path][0], $arr[$path][1]);
        if ($path == 'active' || $path == 'invalid') {
            $data = $data->where(function ($query) use ($type_id) {
                $query->where('unit1_id', $type_id)
                    ->orWhere('unit2_id', $type_id)
                    ->orWhere('unit3_id', $type_id)
                    ->orWhere('unit4_id', $type_id)
                    ->orWhere('position_id', $type_id);
            });
        }
        $data = $data->orderBy('validity_date')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            $data[$key]['instrument'] = ($value['validity_date'] < now() && $path == 'active') ? "<span class='tag btn-sm tag-danger'>$value[instrument]</span>" : $value['instrument'];
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $position = Position::find($_GET['id']);
        $positions = PositionsView::where('str', 'like', '%' . $position->id . '%')->whereIn('level', [4, 5])->get();
        $tools = Tool::where('type_id', ($position->level == 2) ? $position->id : $position->pid)->orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        return view('certificate.create', compact('categories', 'departments', 'positions', 'tools', 'standards'));
    }

    public function store(CertificateRequest $request)
    {
        $arr['sn'] = $request->sn;
        $arr['certificate_name'] = $request->certificate_name;
        $arr['position_id'] = $request->position_id;
        $arr['certificate_no'] = $request->certificate_no;
        $arr['number_id'] = $request->number_id;
        $arr['verification_date'] = $request->verification_date;
        $arr['validity_date'] = $request->validity_date;
        $arr['valid'] = 1;
        $arr['category_id'] = $request->category_id;
        $arr['department_id'] = $request->department_id;
        $arr['start'] = $request->start;
        $arr['times'] = $request->times;
        $arr['remark'] = $request->remark;
        $arr['start_date'] = $request->verification_date;
        $arr['end_date'] = $request->validity_date;
        if ($certificate = Certificate::create($arr)) {
            $position = Position::find($request->position_id)->name;
            $number = Number::find($request->number_id);
            $number->state_id = Parameter::where('name', $position == '备用' ? '备用' : '在用')->first()->id;
            $number->save();
            $standards = Standard::find($request->standard_id);
            $certificate->standards()->sync($standards);
            if ($request->hasFile('file_certificate')) {
                $file = $request->file('file_certificate');
                $file->storeAs('public/certificate', "$certificate->id.pdf");
                $this->pdf2jpg($certificate->id);
            }
            return true;
        } else {
            return false;
        }
    }

    public function edit(CertificatesView $certificate)
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $positions = PositionsView::where('str', 'like', '%' . $certificate->unit3_id . '%')->whereIn('level', [4, 5])->get();
        $tools = ToolsView::where('instrument', $certificate->instrument)->orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $spares = CertificatesView::where('valid', 1)->where('position', '备用')->where('instrument', $certificate->instrument)->orderBy('order')->get();
        return view('certificate.edit', compact('certificate', 'categories', 'departments', 'positions', 'tools', 'standards', 'spares'));
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        if ($request->type == 'apply') {
            $certificate->position_id = $request->position;
            $certificate->sn = $this->getSn($request->position);
            $certificate->remark = $request->remarks;
            $number = Number::find($certificate->number_id);
            $number->state_id = Parameter::where('name', '在用')->first()->id;
            $number->save();
            return !!$certificate->save();
        } elseif ($request->type == 'replace') {
            $certificate->valid = 0;
            $certificate->end_date = Carbon::parse(date('Y-m-d'))->min($request->verification_date)->max($certificate->validity_date);
            $certificate->save();
            $old_certificate = CertificatesView::find($request->id);
            $old_number = Number::find($old_certificate->number_id);
            $old_number->state_id = Parameter::where('name', $request->cause)->first()->id;
            $old_number->save();

            $arr['sn'] = $certificate->sn;
            $arr['certificate_name'] = $request->certificate_name;
            $arr['position_id'] = $certificate->position_id;
            $arr['certificate_no'] = $request->certificate_no;
            $arr['number_id'] = $request->number_id;
            $arr['verification_date'] = $request->verification_date;
            $arr['validity_date'] = $request->validity_date;
            $arr['valid'] = 1;
            $arr['category_id'] = $request->category_id;
            $arr['department_id'] = $request->department_id;
            $arr['start'] = $request->start;
            $arr['times'] = $request->times;
            $arr['remark'] = $request->remark;
            $arr['start_date'] = Carbon::parse(date('Y-m-d'))->min($request->verification_date)->max($certificate->validity_date);
            $arr['end_date'] = $request->validity_date;
            if ($new_certificate = Certificate::create($arr)) {
                $number = Number::find($request->number_id);
                $number->state_id = Parameter::where('name', '在用')->first()->id;
                $number->save();
                $standards = Standard::find($request->standard_id);
                $new_certificate->standards()->sync($standards);
                if ($request->hasFile('file_certificate')) {
                    $file = $request->file('file_certificate');
                    $file->storeAs('public/certificate', "$new_certificate->id.pdf");
                    $this->pdf2jpg($new_certificate->id);
                }
                return true;
            } else {
                return false;
            }
        } elseif ($request->type == 'spare') {
            $old = Certificate::find($request->id);
            $old->valid = 0;
            $old->end_date = Carbon::parse(date('Y-m-d'))->min($certificate->verification_date)->max($old->validity_date);
            $old->save();
            $old_number = Number::find($old->number_id);
            $old_number->state_id = Parameter::where('name', $request->cause)->first()->id;
            $old_number->save();

            $certificate->sn = $old->sn;
            $certificate->position_id = $old->position_id;
            $certificate->remark = $old->remark;
            $certificate->start_date = Carbon::parse(date('Y-m-d'))->min($certificate->verification_date)->max($old->validity_date);
            $new_number = Number::find($certificate->number_id);
            $new_number->state_id = Parameter::where('name', '在用')->first()->id;
            $new_number->save();
            return !!$certificate->save();
        } else {
            $certificate->department_id = $request->department_id;
            $certificate->category_id = $request->category_id;
            $certificate->verification_date = $request->verification_date;
            $certificate->validity_date = $request->validity_date;
            $certificate->certificate_no = $request->certificate_no;
            $certificate->certificate_name = $request->certificate_name;
            $certificate->remark = $request->remark;
            if ($certificate->save()) {
                $standards = Standard::find($request->standard_id);
                $certificate->standards()->sync($standards);
                if ($request->hasFile('file_certificate')) {
                    $file = $request->file('file_certificate');
                    $file->storeAs('public/certificate', "$certificate->id.pdf");
                    $this->pdf2jpg($certificate->id);
                }
                return true;
            } else {
                return false;
            }
        }
    }

    public function show(Certificate $certificate)
    {
        $certificates = DB::table('certificates_views')->where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->orderBy('verification_date', 'desc')->get();
        foreach ($certificates as $c) {
            $c->path = "storage/jpg/$c->id";
            $c->files = Storage::files("\public\jpg\\$c->id");
            if ($certificate->id == $c->id) {
                $disable = $c->id;
            }
        }
        return view('certificate.show', compact('certificates', 'disable'));
    }

    public function destroy($certificate)
    {
        $certificate = Certificate::find($certificate);
        if ($certificate->valid) {
            $certificate->valid = 0;
            $number = Number::find($certificate->number_id);
            $number->state_id = Parameter::where('name', '待检')->first()->id;
            $number->save();
            return !!$certificate->save();
        } else {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                Storage::delete("public/certificate/$certificate->id.pdf");
            }
            if (File::isDirectory("storage/jpg/$certificate->id")) {
                File::deleteDirectory("storage/jpg/$certificate->id");
            }
            return !!$certificate->delete();
        }
    }

    public function displace(Certificate $old, Certificate $new, $cause)
    {

    }
}
