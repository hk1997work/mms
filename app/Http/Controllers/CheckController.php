<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\ChecksView;
use App\Models\Filter;
use App\Models\Position;
use App\Models\PositionsView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CheckController extends Controller
{
    public function index()
    {
        $positions = PositionsView::selectRaw('name1,name3,level,MIN(code) AS code,MIN(sort) AS sort')->where(function ($query) {
            $query->whereIn('name1', ['计量器具', '检测仪表'])
                ->orWhereIn('name2', ['计量器具', '检测仪表'])
                ->orWhereIn('name3', ['计量器具', '检测仪表'])
                ->orWhereIn('name4', ['计量器具', '检测仪表']);
        })->whereIn('level', [3, 5])->where('sign', 0)->where('count', '!=', 0)
            ->groupBy('name1', 'name3', 'level')->orderBy(DB::raw('MIN(`code`)'))->orderBy(DB::raw('MIN(`order`)'))->get();
        return view("check.index", compact('positions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'file' => 'required|image|mimes:png,jpg|max:4096',
        ];
        $request->validate($rules);
        $file = $request->file('file');
        if ($file->isValid()) {
            $date = date("Y-m-d H'i's");

            $compressedImage = Image::make($file->getRealPath())
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            $folderPath = "public/check/$request->certificate_id";
            $fileName = $date . '.' . $file->getClientOriginalExtension();

            $compressedImageHash = hash('sha256', $compressedImage->__toString());

            $existingFiles = Storage::files($folderPath);
            foreach ($existingFiles as $existingFile) {
                $existingFilePath = storage_path("app/$existingFile");
                $existingFileHash = hash_file('sha256', $existingFilePath);
                if ($compressedImageHash === $existingFileHash) {
                    return '上传文件重复';
                }
            }
            return !!Storage::put($folderPath . '/' . $fileName, $compressedImage->__toString());
        }
        return '上传失败';
    }

    public function update($position)
    {
        $certificates = CertificatesView::where('valid', 1)->where('position', $position)->whereIn('type', ['计量器具', '检测仪表'])->where('sign', 0)->orderBy('order')->get();
        foreach ($certificates as $certificate) {
            $folderPath = "storage/check/$certificate->id";
            if (file_exists($folderPath) && is_dir($folderPath)) {
                $files = scandir($folderPath);
                $certificate->count = count($files) - 2;
                if ($certificate->count > 0) {
                    natsort($files);
                    $fileDate = Carbon::createFromFormat('Y-m-d', substr(end($files), 0, 10));
                    $fileAge = $fileDate->diffInDays(Carbon::now());
                    if ($fileAge == 0) {
                        $certificate->color = 'success';
                    } elseif ($fileAge < 30) {
                        $certificate->color = 'info';
                    } elseif ($fileAge < 90) {
                        $certificate->color = 'warning';
                    } else {
                        $certificate->color = 'danger';
                    }
                    $certificate->last = str_replace("'", ':', pathinfo(end($files), PATHINFO_FILENAME));
                    $certificate->hidden = '';
                } else {
                    $certificate->color = 'danger';
                    $certificate->last = '待检查';
                    $certificate->hidden = 'hidden';
                }
            } else {
                $certificate->count = 0;
                $certificate->color = 'danger';
                $certificate->last = '待检查';
                $certificate->hidden = 'hidden';
            }
        }
        return $certificates;
    }

    public function show($id)
    {
        $checks = [];
        $folderPath = "public/check/$id";
        if (Storage::exists($folderPath)) {
            $files = Storage::files($folderPath);
            if (!count($files)) {
                return false;
            }
            foreach ($files as $file) {
                $checks[substr($file, 18, 7)][] = [
                    'path' => $file,
                ];
            }
        }
        return view('check.show', compact('checks'));
    }
}
