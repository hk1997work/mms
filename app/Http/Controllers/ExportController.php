<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;

class ExportController extends Controller
{
    public function index()
    {
        $positions = Position::select('name', 'level')->whereIn('level', [1, 2, 3])->distinct()->get();
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        return view("export.index", compact('positions', 'categories'));
    }

    public function create()
    {
        $certificates = CertificatesView::select('id', 'order', 'position1', 'instrument', 'model')->where('valid', 1)->where('position3', '计量器具')->where('sign', 1)->orderBy('order')->get();
        $settings = Setting::first()->order ?? '';
        return view('export.create', compact('certificates', 'settings'));
    }

    public function store(Request $request)
    {
        $orders = CertificatesView::whereIn('id', explode(',', $request->id))->pluck('order')->implode(',');
        return Setting::count() ? !!Setting::whereRaw('1=1')->update(['order' => $orders]) : !!Setting::create(['order' => $orders]);
    }

    public function update(Request $request)
    {
        $certificates = CertificatesView::whereIn('position4', $request->position)->whereIn('position3', $request->type)->whereIn('position2', $request->class)->whereIn('category', $request->category);
        if (isset($request->daterange)) {
            $start_date = substr($request->daterange, 0, 10);
            $end_date = substr($request->daterange, -10);
            $certificates = $certificates->where('start_date', '<=', $end_date)->where('end_date', '>', $start_date);
        } else {
            $certificates = $certificates->where('valid', 1);
        }
        $path = "storage/" . \Auth::user()->id;
        $types = $request->check_type == 'on' ? $request->type : [$request->type];
        $positions = $request->check_position == 'on' ? $request->position : [$request->position];
        $classes = $request->check_class == 'on' ? $request->class : [$request->class];
        $categories = $request->check_category == 'on' ? $request->category : [$request->category];
        foreach ($types as $type) {
            foreach ($positions as $position) {
                foreach ($classes as $class) {
                    foreach ($categories as $category) {
                        $clonedCertificates = clone $certificates;
                        $this->export($clonedCertificates, $request->contents, $type, $position, $class, $category);
                    }
                }
            }
        }
        $zip = new ZipArchive();
        if ($zip->open($path . '/证书台账' . date('Y-m-d') . '.zip', ZipArchive::CREATE) == true) {
            $this->addFileToZip($path . '/证书台账', $zip);
            $zip->close();
        }
        if (File::isDirectory($path . '/证书台账')) {
            File::deleteDirectory($path . '/证书台账');
        }
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($path . '/证书台账' . date('Y-m-d') . '.zip'));
        header("Content-Disposition: attachment; filename=证书台账" . date('Y-m-d') . ".zip");
        readfile($path . '/证书台账' . date('Y-m-d') . '.zip');
        @ob_end_clean();
        if (file_exists($path . '/证书台账' . date('Y-m-d') . '.zip')) {
            unlink($path . '/证书台账' . date('Y-m-d') . '.zip');
        }
    }

    public function export($certificates, $contents, $type, $position, $class, $category)
    {
        $path = "storage/" . \Auth::user()->id;
        $filename = '';
        if (is_array($type)) {
            $types = $type;
        } else {
            $types[] = $type;
            $filename = '-' . $type;
        }
        if (is_array($position)) {
            $positions = $position;
        } else {
            $positions[] = $position;
            $filename = $filename . '-' . $position;
        }
        if (is_array($class)) {
            $classes = $class;
        } else {
            $classes[] = $class;
            $filename = $filename . '-' . $class;
        }
        if (is_array($category)) {
            $categories = $category;
        } else {
            $categories[] = $category;
            $filename = $filename . '-' . $category;
        }
        $styleArray = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
            'font' => [
                'name' => '宋体',
                'size' => 10,
            ],
            'numberFormat' => [
                'formatCode' => '@',
            ],
        ];
        $certificates = $certificates->whereIn('position4', $positions)->whereIn('position3', $types)->whereIn('position2', $classes)->whereIn('category', $categories)->orderBy('order')->orderBy('start_date')->get();
        if ($certificates->count() == 0) {
            return;
        }
        if (in_array("台账", $contents)) {
            $i = 3;
            $inputFileName = 'storage/mould/台账模板.xlsx';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($certificates as $certificate) {
                $i++;
                $sheet->setCellValueByColumnAndRow(1, $i, $certificate->order);
                $sheet->setCellValueByColumnAndRow(2, $i, $certificate->category);
                $sheet->setCellValueByColumnAndRow(3, $i, $certificate->certificate_no);
                $sheet->setCellValueByColumnAndRow(4, $i, $certificate->position1);
                $sheet->setCellValueByColumnAndRow(5, $i, $certificate->instrument);
                $sheet->setCellValueByColumnAndRow(6, $i, $certificate->model);
                $sheet->setCellValueByColumnAndRow(7, $i, $certificate->number);
                $sheet->setCellValueByColumnAndRow(8, $i, $certificate->limit);
                $sheet->setCellValueByColumnAndRow(9, $i, $certificate->accuracy);
                $sheet->setCellValueByColumnAndRow(10, $i, $certificate->factory);
                $sheet->setCellValueByColumnAndRow(11, $i, $certificate->verification_date);
                $sheet->setCellValueByColumnAndRow(12, $i, $certificate->validity_date);
                $sheet->setCellValueByColumnAndRow(13, $i, $certificate->cycle);
                $sheet->setCellValueByColumnAndRow(14, $i, $certificate->abc);
                $sheet->setCellValueByColumnAndRow(15, $i, $certificate->department);
                $sheet->setCellValueByColumnAndRow(16, $i, $certificate->valid ? '有效' : '无效');
                $sheet->setCellValueByColumnAndRow(17, $i, $certificate->times);
                $sheet->setCellValueByColumnAndRow(18, $i, $certificate->start);
                $sheet->setCellValueByColumnAndRow(19, $i, $certificate->remark);
                $sheet->getRowDimension($i)->setRowHeight(20);
            }
            $sheet->getStyle("A4:S$i")->applyFromArray($styleArray);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            if (File::isDirectory($path . '/证书台账/台账') == false) {
                File::makeDirectory($path . '/证书台账/台账', 0777, true, true);
            }
            $writer->save($path . "/证书台账/台账/台账$filename.xlsx");
        }
        if (in_array("核对台账", $contents)) {
            $i = 3;
            $inputFileName = 'storage/mould/核对台账模板.xlsx';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($certificates as $certificate) {
                $i++;
                $sheet->setCellValueByColumnAndRow(1, $i, $certificate->order);
                $sheet->setCellValueByColumnAndRow(2, $i, $certificate->position1);
                $sheet->setCellValueByColumnAndRow(3, $i, $certificate->instrument);
                $sheet->setCellValueByColumnAndRow(4, $i, $certificate->model);
                $sheet->setCellValueByColumnAndRow(5, $i, $certificate->number);
                $sheet->setCellValueByColumnAndRow(6, $i, $certificate->verification_date);
                $sheet->setCellValueByColumnAndRow(7, $i, $certificate->validity_date);
                $sheet->setCellValueByColumnAndRow(8, $i, $certificate->department);
                $sheet->setCellValueByColumnAndRow(9, $i, $certificate->remark);
                $sheet->getRowDimension($i)->setRowHeight(20);
            }
            $sheet->getStyle("A4:I$i")->applyFromArray($styleArray);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            if (File::isDirectory($path . '/证书台账/核对台账') == false) {
                File::makeDirectory($path . '/证书台账/核对台账', 0777, true, true);
            }
            $writer->save($path . "/证书台账/核对台账/核对台账$filename.xlsx");
        }
        if (in_array("标准台账", $contents)) {
            $i = 4;
            $inputFileName = 'storage/mould/标准台账模板.xlsx';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($certificates as $certificate) {
                $i++;
                $sheet->setCellValueByColumnAndRow(1, $i, $certificate->order);
                $sheet->setCellValueByColumnAndRow(2, $i, $certificate->certificate_no);
                $sheet->setCellValueByColumnAndRow(3, $i, $certificate->position1);
                $sheet->setCellValueByColumnAndRow(4, $i, $certificate->position2);
                $sheet->setCellValueByColumnAndRow(5, $i, $certificate->instrument);
                $sheet->setCellValueByColumnAndRow(6, $i, $certificate->model);
                $sheet->setCellValueByColumnAndRow(7, $i, $certificate->number);
                $sheet->setCellValueByColumnAndRow(8, $i, $certificate->limit);
                $sheet->setCellValueByColumnAndRow(9, $i, $certificate->accuracy);
                $sheet->setCellValueByColumnAndRow(10, $i, $certificate->factory);
                $sheet->setCellValueByColumnAndRow(11, $i, $certificate->verification_date);
                $sheet->setCellValueByColumnAndRow(12, $i, $certificate->validity_date);
                $sheet->setCellValueByColumnAndRow(13, $i, $certificate->cycle);
                $sheet->setCellValueByColumnAndRow(14, $i, substr($certificate->validity_date, 0, 7));
                $sheet->setCellValueByColumnAndRow(15, $i, $certificate->abc);
                $sheet->setCellValueByColumnAndRow(16, $i, $certificate->department);
                $sheet->setCellValueByColumnAndRow(17, $i, $certificate->valid ? '有效' : '无效');
                $sheet->setCellValueByColumnAndRow(18, $i, $certificate->cycle);
                $sheet->setCellValueByColumnAndRow(19, $i, $certificate->times);
                $sheet->setCellValueByColumnAndRow(20, $i, $certificate->start);
                $sheet->setCellValueByColumnAndRow(21, $i, '');
                $sheet->setCellValueByColumnAndRow(22, $i, $certificate->remark);
                $sheet->getRowDimension($i)->setRowHeight(20);
            }
            $sheet->getStyle("A5:V$i")->applyFromArray($styleArray);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            if (File::isDirectory($path . '/证书台账/标准台账') == false) {
                File::makeDirectory($path . '/证书台账/标准台账', 0777, true, true);
            }
            $writer->save($path . "/证书台账/标准台账/标准台账$filename.xlsx");
        }
        if (in_array("计量证书", $contents)) {
            foreach ($certificates as $certificate) {
                $pdf_file = "public/certificate/$certificate->id.pdf";
                $pdf_path = "public/" . \Auth::user()->id
                    . "/证书台账/证书/证书$filename/$certificate->order--$certificate->instrument--$certificate->number--【"
                    . substr($certificate->verification_date, 0, 4) . "年"
                    . substr($certificate->verification_date, 5, 2) . "月"
                    . substr($certificate->verification_date, 8, 2) . "日-"
                    . substr($certificate->validity_date, 0, 4) . "年"
                    . substr($certificate->validity_date, 5, 2) . "月"
                    . substr($certificate->validity_date, 8, 2) . "日】.pdf";
                if (Storage::exists($pdf_file) && !Storage::exists($pdf_path)) {
                    Storage::copy($pdf_file, $pdf_path);
                }
            }
        }
        if (in_array("监理资料", $contents)) {
            $i = 3;
            $inputFileName = 'storage/mould/台账模板.xlsx';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $settings = explode(',', Setting::first()->order);
            $cers = $certificates->whereIn('order', $settings);
            if ($cers->count() == 0) {
                return;
            }
            foreach ($cers as $cer) {
                $i++;
                $sheet->setCellValueByColumnAndRow(1, $i, $cer->order);
                $sheet->setCellValueByColumnAndRow(2, $i, $cer->category);
                $sheet->setCellValueByColumnAndRow(3, $i, $cer->certificate_no);
                $sheet->setCellValueByColumnAndRow(4, $i, $cer->position1);
                $sheet->setCellValueByColumnAndRow(5, $i, $cer->certificate_name);
                $sheet->setCellValueByColumnAndRow(6, $i, $cer->model);
                $sheet->setCellValueByColumnAndRow(7, $i, $cer->number);
                $sheet->setCellValueByColumnAndRow(8, $i, $cer->limit);
                $sheet->setCellValueByColumnAndRow(9, $i, $cer->accuracy);
                $sheet->setCellValueByColumnAndRow(10, $i, $cer->factory);
                $sheet->setCellValueByColumnAndRow(11, $i, $cer->verification_date);
                $sheet->setCellValueByColumnAndRow(12, $i, $cer->validity_date);
                $sheet->setCellValueByColumnAndRow(13, $i, $cer->cycle);
                $sheet->setCellValueByColumnAndRow(14, $i, $cer->abc);
                $sheet->setCellValueByColumnAndRow(15, $i, $cer->department);
                $sheet->setCellValueByColumnAndRow(16, $i, $cer->valid ? '有效' : '无效');
                $sheet->setCellValueByColumnAndRow(17, $i, $cer->times);
                $sheet->setCellValueByColumnAndRow(18, $i, $cer->start);
                $sheet->setCellValueByColumnAndRow(19, $i, $cer->remark);
                $sheet->getRowDimension($i)->setRowHeight(20);
                $pdf_file = "public/certificate/$cer->id.pdf";
                $pdf_path = "public/" . \Auth::user()->id
                    . "/证书台账/监理资料/证书$filename/$cer->order--$cer->instrument--$cer->number--【"
                    . substr($cer->verification_date, 0, 4) . "年"
                    . substr($cer->verification_date, 5, 2) . "月"
                    . substr($cer->verification_date, 8, 2) . "日-"
                    . substr($cer->validity_date, 0, 4) . "年"
                    . substr($cer->validity_date, 5, 2) . "月"
                    . substr($cer->validity_date, 8, 2) . "日】.pdf";
                if (Storage::exists($pdf_file) && !Storage::exists($pdf_path)) {
                    Storage::copy($pdf_file, $pdf_path);
                }
            }
            $sheet->getStyle("A4:S$i")->applyFromArray($styleArray);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            if (File::isDirectory($path . '/证书台账/监理资料') == false) {
                File::makeDirectory($path . '/证书台账/监理资料', 0777, true, true);
            }
            $writer->save($path . "/证书台账/监理资料/监理资料$filename.xlsx");
        }
    }
}
