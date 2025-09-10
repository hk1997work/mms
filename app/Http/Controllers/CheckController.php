<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CheckController extends Controller
{
    public function index()
    {
        $positions = DB::table('positions_checks_views')->get();
        return view("check.index", compact('positions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'file' => 'required|image|mimes:png,jpg|max:10240',
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
            Storage::put($folderPath . '/' . $fileName, $compressedImage->__toString());
            return DB::table('certificate_check')->insert([
                'certificate_id' => $request->certificate_id,
                'filepath' => $folderPath . '/' . $fileName,
            ]);
        }
        return '上传失败';
    }

    public function update($position)
    {
        $certificates = DB::table('certificates_checks_views')->where('position', $position)->get();
        $position = DB::table('positions_checks_views')->where('name', $position)->where('level', 4)->first();
        $unit = DB::table('positions_checks_views')->where('name', $position->pname)->where('level', 1)->first();
        return [
            'position' => $position,
            'unit' => $unit,
            'certificates' => $certificates,
        ];
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
                $checks[substr(basename($file), 0, 7)][] = [
                    'path' => $file,
                ];
            }
        }
        return view('check.show', compact('checks'));
    }
}
