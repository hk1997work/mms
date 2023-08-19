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
use App\Models\StandardsView;
use App\Models\Tool;
use App\Models\ToolsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CertificateController extends Controller
{
    public function index()
    {
        $types = PositionsView::orderBy('sort')->get();
        switch (\Request::segment(1)) {
            case "active":
                $position_id = isset($_GET['position_id']) ? $_GET['position_id'] : Position::where('name', '计量器具')->first()->id;
                $position = Position::find($position_id);
                $certificates = CertificatesView::where('valid', 1)->where(function ($query) use ($position_id) {
                    $query->where('unit1_id', $position_id)
                        ->orWhere('unit2_id', $position_id)
                        ->orWhere('unit3_id', $position_id)
                        ->orWhere('unit4_id', $position_id)
                        ->orWhere('position_id', $position_id);
                })->get();
                $positions = Position::where('sign', 0)->get();
                return view("certificate.index", compact('types', 'certificates', 'position', 'positions'));
            case "invalid":
                $position_id = isset($_GET['position_id']) ? $_GET['position_id'] : Position::where('name', '计量器具')->first()->id;
                $position = Position::find($position_id);
                $certificates = CertificatesView::where('valid', 0)->where(function ($query) use ($position_id) {
                    $query->where('unit1_id', $position_id)
                        ->orWhere('unit2_id', $position_id)
                        ->orWhere('unit3_id', $position_id)
                        ->orWhere('unit4_id', $position_id)
                        ->orWhere('position_id', $position_id);
                })->orderBy('validity_date')->get();
                return view("certificate.index", compact('types', 'certificates', 'position'));
            case "deactive":
                $certificates = CertificatesView::where('state', '封存')->get();
                return view("certificate.index", compact('types', 'certificates'));
            case "scrap":
                $certificates = CertificatesView::where('state', '报废')->get();
                return view("certificate.index", compact('types', 'certificates'));
        }
    }

    public function create()
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $position = Position::find(isset($_GET['id']) ? $_GET['id'] : Position::where('name', '计量器具')->first()->id);
        $positions = PositionsView::where('str', 'like', '%' . $position->id . '%')->whereIn('level', [4, 5])->get();
        $tools = Tool::where('type_id', ($position->level == 2) ? $position->id : $position->pid)->orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        return view('certificate.create', compact('categories', 'departments', 'positions', 'tools', 'standards'));
    }

    public function store(CertificateRequest $request)
    {
        $position = Position::find($request->position_id)->name;
        if (Factory::where('tool_id', $request->tool_id)->where('factory', $request->factory_id)->exists()) {
            $request->factory_id = Factory::where('tool_id', $request->tool_id)->where('factory', $request->factory_id)->first()->id;
        } elseif (Factory::where('tool_id', $request->tool_id)->where('id', $request->factory_id)->exists() == false) {
            $factory_controller = new FactoryController;
            $factory = new FactoryRequest();
            $factory->tool_id = $request->tool_id;
            $factory->factory = $request->factory_id;
            $factory->fullname = '';
            $request->factory_id = $factory_controller->store($factory);
        }
        if (Number::where('factory_id', $request->factory_id)->where('number', $request->number_id)->exists()) {
            $number = Number::where('factory_id', $request->factory_id)->where('number', $request->number_id)->first();
            $request->number_id = $number->id;
            $number->state_id = Parameter::where('name', $position == '备用' ? '备用' : '在用')->first()->id;
            $number->save();
        } elseif (Number::where('factory_id', $request->factory_id)->where('id', $request->number_id)->exists() == false) {
            $number_controller = new NumberController;
            $number = new NumberRequest();
            $number->factory_id = $request->factory_id;
            $number->number = $request->number_id;
            $number->state_id = Parameter::where('name', $position == '备用' ? '备用' : '在用')->first()->id;
            $request->number_id = $number_controller->store($number);
        } else {
            $number = Number::find($request->number_id);
            $number->state_id = Parameter::where('name', $position == '备用' ? '备用' : '在用')->first()->id;
            $number->save();
        }
        $arr['sn'] = $request->sn;
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
        $arr['money'] = $request->money;
        $arr['remark'] = $request->remark;
        $arr['standard_id'] = "," . implode(",", $request->standard_id) . ",";
        if ($certificate = Certificate::create($arr)) {
            if ($request->hasFile('file_certificate')) {
                $file = $request->file('file_certificate');
                $file->storeAs('public/certificate', "$certificate->id.pdf");
                $this->pdf2jpg($certificate->id);
            }
            return $certificate->id;
        } else {
            return false;
        }
    }

    public function show(Certificate $certificate)
    {
        $certificates = DB::table('certificates_views1')->where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->orderBy('verification_date', 'desc')->get();
        foreach ($certificates as $key => $c) {
            $c->path = "storage/jpg/$c->id";
            if (!Storage::exists("\public\jpg\\$c->id" . "/0.jpg")) {
                $this->pdf2jpg($c->id);
            }
            $c->files = Storage::files("\public\jpg\\$c->id");
            if ($certificate->id == $c->id) {
                $disable = count($certificates) - $key - 1;
            }
        }
        return view('certificate.show', compact('certificates', 'disable'));
    }

    public function edit(CertificatesView $certificate)
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $certificate->standard_id = explode(',', $certificate->standard_id);
        return view('certificate.edit', compact('certificate', 'standards', 'categories', 'departments'));
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        $certificate->certificate_no = $request->certificate_no;
        $certificate->verification_date = $request->verification_date;
        $certificate->validity_date = $request->validity_date;
        $certificate->category_id = $request->category_id;
        $certificate->department_id = $request->department_id;
        $certificate->money = $request->money;
        $certificate->standard_id = "," . implode(',', $request->standard_id) . ",";
        $certificate->remark = $request->remark;
        if ($certificate->save()) {
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

    public function replace(CertificatesView $certificate)
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $certificate->standard_id = explode(',', $this->getStandard($certificate->tool_id));
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $tools = ToolsView::where('instrument', $certificate->instrument)->orderBy('instrument')->get();
        $spares = CertificatesView::where('valid', 1)->where('position', '备用')->where('instrument', $certificate->instrument)->orderBy('order')->get();
        return view('certificate.replace', compact('certificate', 'standards', 'tools', 'spares', 'categories', 'departments'));
    }

    public function updateReplace(ReplaceRequest $request, Certificate $certificate)
    {
        $certificate->valid = 0;
        $certificate->save();
        $old_number = Number::find($certificate->number_id);
        $old_number->state_id = $request->cause ? Parameter::where('name', '损坏')->first()->id : Parameter::where('name', '待检')->first()->id;
        $old_number->save();

        if (Factory::where('tool_id', $request->tool_id)->where('id', $request->factory_id)->exists() == false) {
            $factory_controller = new FactoryController;
            $factory = new FactoryRequest();
            $factory->tool_id = $request->tool_id;
            $factory->factory = $request->factory_id;
            $factory->fullname = '';
            $request->factory_id = $factory_controller->store($factory);
        }
        if (Number::where('factory_id', $request->factory_id)->where('id', $request->number_id)->exists() == false) {
            $number_controller = new NumberController;
            $number = new NumberRequest();
            $number->factory_id = $request->factory_id;
            $number->number = $request->number_id;
            $number->state_id = Parameter::where('name', '在用')->first()->id;
            $request->number_id = $number_controller->store($number);
        } else {
            $number = Number::find($request->number_id);
            $number->state_id = Parameter::where('name', '在用')->first()->id;
            $number->save();
        }
        $arr['sn'] = $certificate->sn;
        $arr['position_id'] = $certificate->position_id;
        $arr['certificate_no'] = $request->certificate_no;
        $arr['number_id'] = $request->number_id;
        $arr['verification_date'] = $request->verification_date;
        $arr['validity_date'] = $request->validity_date;
        $arr['valid'] = 1;
        $arr['category_id'] = $request->category_id;
        $arr['department_id'] = $request->department_id;
        $arr['start'] = $certificate->start;
        $arr['times'] = $certificate->times + 1;
        $arr['money'] = $request->money;
        $arr['standard_id'] = "," . implode(",", $request->standard_id) . ",";
        $arr['remark'] = $request->remark;
        if ($new_certificate = Certificate::create($arr)) {
            if ($request->hasFile('file_certificate')) {
                $file = $request->file('file_certificate');
                $file->storeAs('public/certificate', "$new_certificate->id.pdf");
                $this->pdf2jpg($new_certificate->id);
            }
            return true;
        } else {
            return false;
        }
    }

    public function apply(Certificate $certificate, Request $request)
    {
        if (isset($request->position)) {
            $certificate->position_id = $request->position;
            $certificate->remark = $request->remarks;
            $certificate->sn = $this->getSn($request->position);
            $number = Number::find($certificate->number_id);
            $number->state_id = Parameter::where('name', '在用')->first()->id;
            $number->save();
            return !!$certificate->save();
        }
        return '请选择使用岗位';
    }

    public function displace(Certificate $old, Certificate $new, $cause)
    {
        $old->valid = 0;
        $old_number = Number::find($old->number_id);
        $old_number->state_id = Parameter::where('name', $cause ? '损坏' : '待检')->first()->id;
        $old_number->save();
        $old->save();

        $new->sn = $old->sn;
        $new->position_id = $old->position_id;
        $new->start = $old->start;
        $new->times = $old->times + 1;
        $new->remark = $old->remark;
        $new_number = Number::find($new->number_id);
        $new_number->state_id = Parameter::where('name', '在用')->first()->id;
        $new_number->save();
        return !!$new->save();
    }
}
