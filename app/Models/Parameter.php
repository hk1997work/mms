<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'pid', 'level', 'sort', 'sort_str',
    ];

    public function children()
    {
        return $this->hasMany(Parameter::class, 'pid', 'id')->orderBy('sort')->select('id', 'pid', 'name')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Parameter::class, 'pid', 'id')->select('id', 'pid', 'name')->with('parent');
    }

    public function category()
    {
        return $this->hasMany(Certificate::class, 'category_id', 'id');
    }

    public function department()
    {
        return $this->hasMany(Certificate::class, 'department_id', 'id');
    }

    public function cycle()
    {
        return $this->hasMany(Tool::class, 'cycle_id', 'id');
    }

    public function abc()
    {
        return $this->hasMany(Tool::class, 'abc_id', 'id');
    }

    public function plan()
    {
        return $this->hasMany(Tool::class, 'plan_id', 'id');
    }

    public function state()
    {
        return $this->hasMany(Number::class, 'state_id', 'id');
    }

    public function getList($request)
    {
        $query = self::select('id', 'name', 'pid', 'level')
            ->with('children', 'parent')
            ->withCount('department', 'category', 'cycle', 'abc', 'plan', 'state')
            ->orderBy('sort_str');

        return DataTables::of($query)
            ->editColumn('name', function ($data) {
                return $data->level == 1
                    ? "<span class='badge bg-warning-subtle border border-warning-subtle text-warning-emphasis'>" . e($data->name) . "</span>"
                    : '';
            })
            ->editColumn('pid', function ($data) {
                return $data->level == 1
                    ? "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='parameter' data-id='" . e($data->id) . "'>增加</span>"
                    : "<span class='badge bg-success-subtle border border-success-subtle text-success-emphasis'>" . e($data->name) . "</span>";
            })
            ->editColumn('level', function ($data) {
                $count = $data->level == 1
                    ? $data->children->count()
                    : $data->department_count | $data->category_count | $data->cycle_count | $data->abc_count | $data->plan_count | $data->state_count;
                return $count == 0
                    ? "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>"    . e($count) . "</span>"
                    : "<span class='badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis'>" . e($count) . "</span>";
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('name', 'like', "%$term%")
                                    ->orWhereHas('children', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    });
                            });
                        }
                    });
                }
            })
            ->removeColumn('children', 'parent', 'department_count', 'category_count', 'cycle_count', 'abc_count', 'plan_count', 'state_count')
            ->rawColumns([1, 2, 3])
            ->make(false);
    }
}
