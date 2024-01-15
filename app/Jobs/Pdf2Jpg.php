<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class Pdf2Jpg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $pdf = "storage/certificate/$this->id.pdf";
        $path = "storage/jpg/$this->id";

        if (!file_exists($pdf)) {
            return '文件不存在';
        }
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
        File::makeDirectory($path, 0777, true, true);
        exec("python F:/phpstudy_pro/WWW/laravel8/python/get_jpg_form_pdf.py " . $this->id . " 2>&1", $out, $status);
    }
}
