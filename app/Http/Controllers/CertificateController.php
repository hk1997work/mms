<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Http\Requests\ReceiveRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\Standard;
use App\Models\StandardsView;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;


class CertificateController extends Controller
{
    public function index()
    {
        $types = Position::where('pid', Position::where('level', 1)->where('name', '备用')->first()->id)->orderBy('sort')->get();
        $type = isset($_GET['id']) ? Position::find($_GET['id']) : $types->first();
        $positions = Position::where('name', $type->name)->orderBy('sort')->with('parent')->get();
        $position = isset($_GET['position']) ? Position::find($_GET['position']) : null;
        return view("certificate.index", compact('type', 'types', 'position', 'positions'));
    }

    public function list(Request $request)
    {
        $path = $_GET['path'];
        $query = CertificatesView::select('id', 'order', 'position1', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'receiver', 'check', 'remark')->where('type_id', $_GET['id']);
        if (isset($_GET['position'])) {
            $query->where('position_id3', $_GET['position']);
        }
        switch ($path) {
            case 'active':
                $query->where('valid', 1);
                break;
            case 'invalid':
                $query->where('valid', 0);
                break;
            case 'deactive':
                $query->where('state', '封存');
                break;
            case 'scrap':
                $query->where('state', '报废');
                break;
        }
        return DataTables::of($query)
            ->editColumn('instrument', function ($data) use ($path) {
                if ($data->validity_date < Carbon::now()->format('Y-m-d') && ($path == 'active')) {
                    return $this->toBadges($data->instrument, 'danger');
                } elseif ($data->validity_date < Carbon::now()->subMonth(-1)->format('Y-m-d') && ($path == 'active')) {
                    return $this->toBadges($data->instrument, 'warning');
                } else {
                    return $this->toValidate($data->instrument, 1);
                }
            })
            ->editColumn('check', function ($data) use ($path) {
                return $this->toValidate($data->check ? '未贴' : null, !$data->check);
            })
            ->editColumn('receiver', function ($data) use ($path) {
                return $data->receiver ?? null;
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['order', 'position1', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'receiver', 'check', 'remark'], 'check', ['', '未贴']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request, ['id', 'order', 'position1', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'receiver', 'check', 'remark']);
            })
            ->rawColumns([4, 10, 11])
            ->make(false);
    }

    public function create()
    {
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        $type = Position::find($_GET['id']);
        $positions = Position::where('name', $type->name)->with('children')->get();
        $tools = Tool::where('type_id', $type->id)->orderBy('instrument')->get();
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
        $arr['replacement'] = $request->replacement;
        $arr['replace_date'] = $request->replace_date;
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
        $type = Position::find($certificate->type_id);
        $positions = Position::where('name', $type->name)->with('children')->get();
        $tools = Tool::where('instrument', $certificate->instrument)->get();
        $standards = StandardsView::where('level', 2)->get();
        $spares = CertificatesView::select('id', 'order', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'remark')->where('valid', 1)->where('position1', '备用')->where('instrument', $certificate->instrument)->orderBy('order')->get();
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
            $old_number = Number::find($certificate->number_id);
            $old_number->state_id = Parameter::where('name', $request->cause ? '损坏' : '待检')->first()->id;
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
            $old_number->state_id = Parameter::where('name', $request->cause ? '损坏' : '待检')->first()->id;
            $old_number->save();

            $certificate->sn = $old->sn;
            $certificate->position_id = $old->position_id;
            $certificate->remark = $old->remark;
            $certificate->start_date = Carbon::parse(date('Y-m-d'))->min($old->validity_date)->max($certificate->verification_date);
            $new_number = Number::find($certificate->number_id);
            $new_number->state_id = Parameter::where('name', '在用')->first()->id;
            $new_number->save();
            return !!$certificate->save();
        } elseif ($request->type == 'edit') {
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
        $certificates = CertificatesView::where('position_id1', $certificate->position_id)->where('sn', $certificate->sn)->orderBy('verification_date', 'desc')->get();
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
        $id = explode(',', $certificate);
        $certificates = Certificate::find($id);
        if ($certificates[0]->valid) {
            Certificate::whereIn('id', $id)->update(['valid' => 0]);
            return !!Number::whereIn('id', $certificates->pluck('number_id'))->update(['state_id' => Parameter::where('name', '待检')->first()->id]);
        } else {
            foreach ($certificates as $c) {
                if (Storage::exists("public/certificate/$c->id.pdf")) {
                    Storage::delete("public/certificate/$c->id.pdf");
                }
                if (File::isDirectory("storage/jpg/$c->id")) {
                    File::deleteDirectory("storage/jpg/$c->id");
                }
                if (File::isDirectory("storage/check/$c->id")) {
                    File::deleteDirectory("storage/check/$c->id");
                }
            }
            return !!DB::table('certificate_standard')->whereIn('certificate_id', $id)->delete() && Certificate::whereIn('id', $id)->delete();
        }
    }

    public function receive($certificate)
    {
        return view('certificate.receive', compact('certificate'));
    }

    public function updateReceive(ReceiveRequest $request, $certificate)
    {
        $ids = explode(',', $certificate);
        $result = Certificate::whereIn('id', $ids)->whereNotNull('receiver')->where('receiver', '!=', '')->whereNotNull('receiving_date')->where('receiving_date', '!=', '')
            ->pluck('certificate_no')->implode(',');
        if ($result) {
            return $result . '已经领用,无法重复领用';
        } else {
            return !!Certificate::whereIn('id', explode(',', $certificate))->update(['receiver' => $request->receiver, 'receiving_date' => $request->receiving_date]);
        }
    }

    public function download(CertificatesView $certificate)
    {
        if (isset($_GET['type'])) {
            return response()->file(storage_path("app/public/certificate/$certificate->id.pdf"));
        } else {
            return response()->download(storage_path("app/public/certificate/$certificate->id.pdf"), "$certificate->order--$certificate->instrument--$certificate->number--【$certificate->verification_date" . '至' . "$certificate->validity_date" . "】.pdf");
        }
    }
}
