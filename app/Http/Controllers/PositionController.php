<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Position;
use App\Models\PositionsView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function index()
    {
        return view('position.index');
    }

    public function list(Request $request)
    {
        return DataTables::of(PositionsView::select('id', 'name1', 'name2', 'name3', 'name4', 'code', 'count', 'total', 'level', 'sign'))
            ->editColumn('name1', function ($data) {
                return $this->toLevel($data, $data->level, 1, 'name', 'position', 'dark', $data->count || !$data->sign);
            })
            ->editColumn('name2', function ($data) {
                return $this->toLevel($data, $data->level, 2, 'name', 'position', 'warning', $data->count || !$data->sign);
            })
            ->editColumn('name3', function ($data) {
                return $this->toLevel($data, $data->level, 3, 'name', 'position', 'success', $data->count || !$data->sign);
            })
            ->editColumn('name4', function ($data) {
                return $this->toLevel($data, $data->level, 4, 'name', 'position', 'info', $data->count || !$data->sign);
            })
            ->editColumn('code', function ($data) {
                return $this->toBadges($data->code, 'primary');
            })
            ->editColumn('count', function ($data) {
                return $this->toValidateBadge($data->count == $data->total ? $data->total : $data->count . '/' . $data->total, 'secondary', $data->sign);
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['name1', 'name2', 'name3', 'name4', 'code']);
            })
            ->setTotalRecords(Position::count())
            ->rawColumns([1, 2, 3, 4, 5, 6])
            ->removeColumn('total', 'level', 'sign')
            ->make(false);
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $position = Position::find($id);
        return view('position.create', compact('position'));
    }

    public function store(PositionRequest $request)
    {
        $parent = Position::find($request->pid);
        $arr['name'] = $request->name;
        $arr['code'] = $request->code;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Position::max('id') + 1;
        $arr['sign'] = $request->sign;
        return !!Position::create($arr);
    }

    public function edit(Position $position)
    {
        return view('position.edit', compact('position'));
    }

    public function update(PositionRequest $request, Position $position)
    {
        $position->name = $request->name;
        $position->code = $request->code;
        $position->sign = $request->sign;
        return !!$position->save();
    }

    public function destroy($position)
    {
        return $this->delete(Position::class, $position, ['children', 'certificate', 'tool'], 'name');
    }

    public function move(Position $position, $type)
    {
        return $this->moveUpDown($position, $type);
    }

    public function show(Position $position)
    {
        return view('position.show', compact('position'));
    }

    public function list_show(Request $request)
    {
        $id = $_GET['id'];
        $query = CertificatesView::selectRaw("MAX(id) AS id,`order`,position1,instrument,MAX(valid) AS valid,MIN(verification_date) AS verification_date,COUNT(*) AS count")
            ->where('position_id1', $id)->orWhere('position_id2', $id)->orWhere('position_id3', $id)->orWhere('position_id4', $id)
            ->groupBy('order', 'position1', 'position_id1', 'instrument');
        return DataTables::of($query)
            ->editColumn('valid', function ($data) {
                return $this->toValidateBadge($data->valid ? '有效' : '失效', 'success', $data->valid);
            })
            ->rawColumns([4])
            ->make(false);
    }

    public function sn(Certificate $certificate)
    {
        $positions = PositionsView::where('name3', PositionsView::find($certificate->position_id)->name3)->get();
        return view('position.sn', compact('certificate', 'positions'));
    }

    public function updateSn(Request $request, Certificate $certificate)
    {
        if (Certificate::where('position_id', $request->position_id)->where('sn', $request->sn)->exists()) {
            return '序号重复';
        }
        return !!Certificate::where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->update(['position_id' => $request->position_id, 'sn' => $request->sn]);
    }

    public function moveSn(Certificate $certificate, $type)
    {
        return $this->moveUpDown($certificate, $type, 'position_id', 'sn');
    }
}
