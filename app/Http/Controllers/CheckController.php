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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CheckController extends Controller
{
    public function index()
    {
        $positions = PositionsView::where('str', 'LIKE', "%," . Position::where('name', '计量器具')->first()->id . ",%")->where('sign', 0)->orderBy('code')->orderBy('order')->get();
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

    public function update($position_id)
    {
        $certificates = CertificatesView::where('valid', 1)->where('type', '计量器具')->where('position_id', $position_id)->where('sign', 0)->orderBy('order')->get();
        foreach ($certificates as $certificate) {
            $folderPath = "storage/check/$certificate->id";
            if (file_exists($folderPath) && is_dir($folderPath)) {
                $files = scandir($folderPath);
                $certificate->count = count($files) - 2;
                if ($certificate->count > 0) {
                    natsort($files);
                    $fileDate = Carbon::createFromFormat('Y-m-d', substr(end($files), 0, 10));
                    $fileAge = $fileDate->diffInDays(Carbon::now());
                    if ($fileAge < 30) {
                        $certificate->color = 'success';
                    } elseif ($fileAge < 90) {
                        $certificate->color = 'info';
                    } elseif ($fileAge < 180) {
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

    public function show($certificate_id)
    {
        $folderPath = "storage/check/$certificate_id";
        $files = array_diff(scandir($folderPath), ['.', '..']);
        if (!count($files)) {
            return false;
        }
        natsort($files);
        $files = array_reverse($files);
        return view('check.show', compact('files', 'certificate_id'));
    }
}
