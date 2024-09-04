<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\PositionsView;
use App\Models\Standard;
use App\Models\StandardsView;
use App\Models\Tool;
use App\Models\ToolsView;
use Carbon\Carbon;
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

        $path = $_GET['path'];
        $type_id = isset($_GET['id']) ? $_GET['id'] : Position::where('level', 2)->orderBy('sort')->first()->id;
        $data = CertificatesView::select('id', 'order', 'position', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'remark', 'number_remark', 'unit3');
        switch ($path) {
            case 'active':
                $data->where('state', '在用')->where('valid', 1);
                break;
            case 'borrow':
                $data->where('state', '借用')->whereRaw('(number_id,validity_date) IN (SELECT `c`.`number_id`, max( `c`.`validity_date` ) AS `validity_date` FROM `certificates` `c` GROUP BY `c`.`number_id`)');
                break;
            case 'invalid':
                $data->where('valid', 0);
                break;
            case 'deactive':
                $data->where('state', '封存');
                break;
            case 'scrap':
                $data->where('state', '报废');
                break;
        }
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
                        <input type='checkbox' name='cb' class='cb' id='$value[id]' data-url='/storage/certificate/$value[id].pdf'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['validity_date'] < Carbon::now()->format('Y-m-d') && ($path == 'active' || $path == 'borrow')) {
                $data[$key]['instrument'] = "<span class='tag btn-sm tag-danger'>$value[instrument]</span>";
            } elseif ($value['validity_date'] < Carbon::now()->subMonth(-1)->format('Y-m-d') && ($path == 'active' || $path == 'borrow')) {
                $data[$key]['instrument'] = "<span class='tag btn-sm tag-warning'>$value[instrument]</span>";
            } else {
                $data[$key]['instrument'] = $value['instrument'];
            }
            $data[$key]['remark'] = $data[$key]['remark'] . ($data[$key]['number_remark'] ? '<div class="text-primary">' . $data[$key]['number_remark'] . '</div>' : '');
            if ($path == 'active' && $value['position'] != '备用' && ($value['unit3'] == '计量器具' || $value['unit3'] == '检测仪表')) {
                $folderPath = "public/check/$value[id]";
                if (!Storage::exists($folderPath) || !count(Storage::files($folderPath))) {
                    $data[$key]['remark'] = "<span class='text-danger'>检查</span>" . $data[$key]['remark'];
                }
            }
            unset($data[$key]['unit3']);
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
        $standards = StandardsView::where('level', 2)->get();
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
            $number = Number::find($request->number_id);
            $number->state_id = Parameter::where('name', '在用')->first()->id;
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
        $standards = StandardsView::where('level', 2)->get();
        $spares = CertificatesView::where('valid', 1)->where('position', '备用')->where('state', '!=', '借用')->where('instrument', $certificate->instrument)->orderBy('order')->get();
        return view('certificate.edit', compact('certificate', 'categories', 'departments', 'positions', 'tools', 'standards', 'spares'));
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        if ($request->type == 'apply') {
            $certificate->position_id = $request->position_id;
            $certificate->sn = $request->sn;
            $certificate->remark = $request->remark;
            $number = Number::find($certificate->number_id);
            $number->state_id = Parameter::where('name', '在用')->first()->id;
            $number->save();
            return !!$certificate->save();
        } elseif ($request->type == 'replace') {
            $certificate->valid = 0;
            $certificate->end_date = Carbon::parse(date('Y-m-d'))->min($certificate->validity_date)->max($request->verification_date)->subDay();
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
            $arr['start_date'] = Carbon::parse(date('Y-m-d'))->min($certificate->validity_date)->max($request->verification_date);
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
            $old->end_date = Carbon::parse(date('Y-m-d'))->min($old->validity_date)->max($certificate->verification_date)->subDay();
            $old->save();
            $old_number = Number::find($old->number_id);
            $old_number->state_id = Parameter::where('name', $request->cause)->first()->id;
            $old_number->save();

            $certificate->sn = $old->sn;
            $certificate->position_id = $old->position_id;
            $certificate->remark = $old->remark;
            $certificate->start_date = Carbon::parse(date('Y-m-d'))->min($old->validity_date)->max($certificate->verification_date);
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

    public function destroy($certificate)
    {
        $certificates = Certificate::find(explode(',', $certificate));
        if ($certificates[0]->valid) {
            Certificate::whereIn('id', explode(',', $certificate))->update(['valid' => 0]);
            return !!Number::whereIn('id', $certificates->pluck('number_id'))->update(['state_id' => Parameter::where('name', '待检')->first()->id]);
        } else {
            foreach ($certificates as $c) {
                if (Storage::exists("public/certificate/$c->id.pdf")) {
                    Storage::delete("public/certificate/$c->id.pdf");
                }
                if (File::isDirectory("storage/jpg/$c->id")) {
                    File::deleteDirectory("storage/jpg/$c->id");
                }
            }
            return !!Certificate::whereIn('id', explode(',', $certificate))->delete();
        }
    }

    public function download(CertificatesView $certificate)
    {
        return response()->download(storage_path("app/public/certificate/$certificate->id.pdf"), "$certificate->order--$certificate->instrument--$certificate->number--【$certificate->verification_date" . '至' . "$certificate->validity_date" . "】.pdf");
    }
}
