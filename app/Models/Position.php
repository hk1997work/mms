<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'pid', 'level', 'sort', 'sort_str', 'sign',
    ];

    public function children()
    {
        return $this->hasMany(Position::class, 'pid', 'id')->orderBy('sort')->select('id', 'pid', 'name')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'pid', 'id')->select('id', 'pid', 'name')->with('parent');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'position_id', 'id');
    }

    public function validCertificates()
    {
        return $this->certificates()->where('valid', 1);
    }

    public function getList($request)
    {
        $query = self::select('id', 'name', 'pid', 'level', 'sign', 'code', 'sort')
            ->with([
                'parent',
                'children' => function ($query) {
                    $query->withCount([
                        'certificates as certificates_count' => function ($q) {
                            $q->select(DB::raw('count(distinct sn)'));
                        },
                        'validCertificates as valid_certificates_count' => function ($q) {
                            $q->select(DB::raw('count(distinct sn)'));
                        },
                    ])
                        ->with([
                            'children' => function ($query) {
                                $query->withCount([
                                    'certificates as certificates_count' => function ($q) {
                                        $q->select(DB::raw('count(distinct sn)'));
                                    },
                                    'validCertificates as valid_certificates_count' => function ($q) {
                                        $q->select(DB::raw('count(distinct sn)'));
                                    },
                                ])
                                    ->with([
                                        'children' => function ($query) {
                                            $query->withCount([
                                                'certificates as certificates_count' => function ($q) {
                                                    $q->select(DB::raw('count(distinct sn)'));
                                                },
                                                'validCertificates as valid_certificates_count' => function ($q) {
                                                    $q->select(DB::raw('count(distinct sn)'));
                                                },
                                            ]);
                                        },
                                    ]);
                            },
                        ]);
                },
            ])
            ->withCount([
                'certificates as certificates_count' => function ($q) {
                    $q->select(DB::raw('count(distinct sn)'));
                },
                'validCertificates as valid_certificates_count' => function ($q) {
                    $q->select(DB::raw('count(distinct sn)'));
                },
            ])
            ->orderBy('sort_str');

        return DataTables::of($query)
            ->editColumn('name', function ($data) {
                $tag = $data->sign ? 'danger' : 'light';
                if ($data->level == 1) {
                    return "<span class='badge bg-" . $tag . "-subtle border border-" . $tag . "-subtle text-" . $tag . "-emphasis'>" . e($data->name) . "</span>";
                }
            })
            ->editColumn('pid', function ($data) {
                if ($data->level == 1) {
                    return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='position' data-id='" . e($data->id) . "'>增加</span>";
                } elseif ($data->level == 2) {
                    $tag = $data->sign ? 'danger' : 'warning';
                    return "<span class='badge bg-" . $tag . "-subtle border border-" . $tag . "-subtle text-" . $tag . "-emphasis'>" . e($data->name) . "</span>";
                }
            })
            ->editColumn('level', function ($data) {
                if ($data->level == 2) {
                    return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='position' data-id='" . e($data->id) . "'>增加</span>";
                } elseif ($data->level == 3) {
                    $tag = $data->sign ? 'danger' : 'success';
                    return "<span class='badge bg-" . $tag . "-subtle border border-" . $tag . "-subtle text-" . $tag . "-emphasis'>" . e($data->name) . "</span>";
                }
            })
            ->editColumn('sign', function ($data) {
                if ($data->level == 3) {
                    return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='position' data-id='" . e($data->id) . "'>增加</span>";
                } elseif ($data->level == 4) {
                    $tag = $data->sign ? 'danger' : 'info';
                    return "<span class='badge bg-" . $tag . "-subtle border border-" . $tag . "-subtle text-" . $tag . "-emphasis'>" . e($data->name) . "</span>";
                }
            })
            ->editColumn('code', function ($data) {
                return $data->code
                    ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis'>" . e($data->code) . "</span>"
                    : '';
            })
            ->editColumn('sort', function ($data) {
                $total = $data->getTotal();
                $tag = $total ? 'secondary' : 'danger';
                return "<span class='badge bg-" . $tag . "-subtle border border-" . $tag . "-subtle text-" . $tag . "-emphasis'>" . e($total) . "</span>";
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('name', 'like', "%$term%")
                                    ->orWhere('code', 'like', "%$term%")
                                    ->orWhereHas('children', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('children.children', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('children.children.children', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent.parent', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent.parent.parent', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    });
                            });
                        }
                    });
                }
            })
            ->rawColumns([1, 2, 3, 4, 5, 6])
            ->removeColumn('children', 'parent', 'certificates_count', 'valid_certificates_count')
            ->make(false);
    }

    public function getTotal()
    {
        $total = $this->certificates_count;
        $valid = $this->valid_certificates_count;
        $maxDepth = 4 - $this->level;
        $children = $this->children;
        for ($i = 1; $i <= $maxDepth; $i++) {
            foreach ($children as $child) {
                $total += $child->certificates_count;
                $valid += $child->valid_certificates_count;
            }
            $children = $children->flatMap->children;
        }
        return $total == $valid ? $total : ($valid . '/' . $total);
    }
}
