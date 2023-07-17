<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrintRequest;
use App\Models\CertificatesView;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PrintController extends Controller
{
    public function index()
    {
        $certificates = CertificatesView::where('valid', 1)->get();
        return view("print.index", compact('certificates'));
    }

    public function store(PrintRequest $request)
    {
        if (isset($request->cb)) {
            $certificates = CertificatesView::whereIn('id', array_keys($request->cb))->orderBy('order')->get();
            $i = ($request->column - 1) * 2 + 1;
            $j = ($request->row - 1) * 6;

            $inputFileName = 'storage/mould/标签模板.xls';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            if ($request->row > 1) {
                for ($a = ($request->row - 2) * 6; $a >= 0; $a = $a - 6) {
                    $sheet->getRowDimension($a + 1)->setRowHeight(10);
                    $sheet->getRowDimension($a + 2)->setRowHeight(10);
                    $sheet->getRowDimension($a + 3)->setRowHeight(10);
                    $sheet->getRowDimension($a + 4)->setRowHeight(10);
                    $sheet->getRowDimension($a + 5)->setRowHeight(10);
                    $sheet->getRowDimension($a + 6)->setRowHeight(21);
                }
            }
            foreach ($certificates as $certificate) {
                if (isset($request->check_position)) {
                    $sheet->setCellValueByColumnAndRow($i, $j + 1, $certificate->position);
                }
                $sheet->setCellValueByColumnAndRow($i, $j + 2, $certificate->number);
                $sheet->setCellValueByColumnAndRow($i, $j + 3, "      " . str_replace('-', '   ', $certificate->verification_date));
                $sheet->setCellValueByColumnAndRow($i, $j + 4, "      " . str_replace('-', '   ', $certificate->validity_date));
                $sheet->setCellValueByColumnAndRow($i, $j + 5, "      " . $certificate->department);
                $sheet->getRowDimension($j + 1)->setRowHeight(10);
                $sheet->getRowDimension($j + 2)->setRowHeight(10);
                $sheet->getRowDimension($j + 3)->setRowHeight(10);
                $sheet->getRowDimension($j + 4)->setRowHeight(10);
                $sheet->getRowDimension($j + 5)->setRowHeight(10);
                $sheet->getRowDimension($j + 6)->setRowHeight(21);
                $i > 8 ? $j = $j + 6 : '';
                $i > 8 ? $i = 1 : $i = $i + 2;
            }
            $styleArray = [
                'alignment' => [
                    'horizontal' => 'left',
                    'vertical' => 'center',
                ],
                'font' => [
                    'name' => '方正小标宋简体',
                    'size' => 7
                ],
                'numberFormat' => [
                    'formatCode' => '@'
                ],
            ];
            $sheet->getStyle("A1:I" . ($j + 6))->applyFromArray($styleArray);
            @ob_end_clean();
            ob_start();
            header('Content-Type:application/vnd.ms-excel');
            header("Content-Disposition:attachment;filename=" . "标签:" . date("Y-m-d") . ".xls");
            header('Cache-Control:max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
            exit;
        }
        return redirect("/print")->withErrors("请选择打印标签");
    }
}
