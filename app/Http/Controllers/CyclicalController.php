<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use ZipArchive;

class CyclicalController extends Controller
{

    public function index()
    {
        $years = CertificatesView::selectRaw('SUBSTR(`month`,1,4) as `year`')->groupBy('year')->orderBy('year')->havingRaw('COUNT(month) > ?', [100])->get();
        return view("cyclical.index", compact('years'));
    }

    public function store(Request $request)
    {
        $path = "storage/" . \Auth::user()->username;
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }

        foreach ($request->year as $year) {
            foreach ($request->type as $unit4) {
                foreach ($request->position as $unit2) {
                    $i = 1;
                    $inputFileName = 'storage/mould/周检通知单模板.xlsx';
                    $spreadsheet = IOFactory::load($inputFileName);
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->getDefaultRowDimension()->setRowHeight(25);
                    $months = CertificatesView::selectRaw('month,unit2,unit4')->where('unit2', $unit2)->where('unit4', $unit4)->whereRaw("SUBSTR(`month`,1,4) = $year")->groupBy('month', 'unit4', 'unit2')->orderBy('month')->get();
                    foreach ($months as $month) {
                        $certificates = CertificatesView::where('month', $month->month)->where('unit4', $unit4)->where('unit2', $unit2)->orderBy('order')->get();
                        foreach ($certificates as $certificate) {
                            if ($i % 25 == 0) {
                                $i++;
                            }
                            if ($i % 25 == 1) {
                                $i1 = $i + 1;
                                $i2 = $i + 2;
                                $i3 = $i + 3;
                                $i23 = $i + 23;
                                $i24 = $i + 24;
                                //第一行
                                $sheet->mergeCells("A$i:L$i");
                                $styleArray = [
                                    'alignment' => [
                                        'horizontal' => 'center',
                                        'vertical' => 'center',
                                    ],
                                    'font' => [
                                        'name' => '宋体',
                                        'size' => 16,
                                        'bold' => true,
                                    ],
                                    'numberFormat' => [
                                        'formatCode' => '@'
                                    ],
                                ];
                                $sheet->getStyle("A$i")->applyFromArray($styleArray);
                                $sheet->setCellValueByColumnAndRow(1, $i, "周检通知单");
                                //第二行
                                $sheet->mergeCells("A$i1:L$i1");
                                $styleArray = [
                                    'alignment' => [
                                        'horizontal' => 'right',
                                        'vertical' => 'center',
                                    ],
                                    'font' => [
                                        'name' => '宋体',
                                        'size' => 10,
                                        'bold' => true,
                                    ],
                                    'numberFormat' => [
                                        'formatCode' => '@'
                                    ],
                                ];
                                $sheet->getStyle("A$i1")->applyFromArray($styleArray);
                                $sheet->setCellValueByColumnAndRow(1, $i1, "编号:$unit4-$unit2-$month->month");
                                //第三行
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
                                        'bold' => true,
                                    ],
                                    'numberFormat' => [
                                        'formatCode' => '@'
                                    ],
                                ];
                                $sheet->getStyle("A$i2:L$i2")->applyFromArray($styleArray);
                                $sheet->setCellValueByColumnAndRow(1, $i2, "序号");
                                $sheet->setCellValueByColumnAndRow(2, $i2, "岗位");
                                $sheet->setCellValueByColumnAndRow(3, $i2, "器具名称");
                                $sheet->setCellValueByColumnAndRow(4, $i2, "规格型号");
                                $sheet->setCellValueByColumnAndRow(5, $i2, "出厂编号");
                                $sheet->setCellValueByColumnAndRow(6, $i2, "测量范围");
                                $sheet->setCellValueByColumnAndRow(7, $i2, "精确度");
                                $sheet->setCellValueByColumnAndRow(8, $i2, "生产厂家");
                                $sheet->setCellValueByColumnAndRow(9, $i2, "检定日期");
                                $sheet->setCellValueByColumnAndRow(10, $i2, "有效期");
                                $sheet->setCellValueByColumnAndRow(11, $i2, "检定周期");
                                $sheet->setCellValueByColumnAndRow(12, $i2, "备注");
                                //当前页
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
                                        'size' => 10
                                    ],
                                    'numberFormat' => [
                                        'formatCode' => '@'
                                    ],
                                ];
                                $sheet->getStyle("A$i3:L$i23")->applyFromArray($styleArray);
                                $styleArray = [
                                    'alignment' => [
                                        'horizontal' => 'right',
                                        'vertical' => 'center',
                                    ],
                                    'font' => [
                                        'name' => '宋体',
                                        'size' => 10,
                                    ],
                                    'numberFormat' => [
                                        'formatCode' => '@'
                                    ],
                                ];
                                $sheet->getStyle("A$i24:L$i24")->applyFromArray($styleArray);
                                $sheet->getRowDimension($i24)->setRowHeight(50);
                                $sheet->setCellValueByColumnAndRow(2, $i24, "计量员:");
                                $sheet->setCellValueByColumnAndRow(5, $i24, "负责人:");
                                $sheet->setCellValueByColumnAndRow(9, $i24, "日期:$month->month");
                                if (is_file('storage/sign/1.png')) {
                                    $drawing = new Drawing();
                                    $drawing->setPath('storage/sign/1.png');
                                    $drawing->setHeight(50);
                                    $drawing->setCoordinates("C$i24");
                                    $drawing->setWorksheet($sheet);
                                }
                                if (is_file("storage/sign/$unit4$unit2.png")) {
                                    $drawing = new Drawing();
                                    $drawing->setPath("storage/sign/$unit4$unit2.png");
                                    $drawing->setHeight(50);
                                    $drawing->setCoordinates("F$i24");
                                    $drawing->setWorksheet($sheet);
                                }

                                $i = $i + 3;
                            }

                            $sheet->setCellValueByColumnAndRow(1, $i, $certificate->order);
                            $sheet->setCellValueByColumnAndRow(2, $i, $certificate->position);
                            $sheet->setCellValueByColumnAndRow(3, $i, $certificate->instrument);
                            $sheet->setCellValueByColumnAndRow(4, $i, $certificate->model);
                            $sheet->setCellValueByColumnAndRow(5, $i, $certificate->number);
                            $sheet->setCellValueByColumnAndRow(6, $i, $certificate->limit);
                            $sheet->setCellValueByColumnAndRow(7, $i, $certificate->accuracy);
                            $sheet->setCellValueByColumnAndRow(8, $i, $certificate->factory);
                            $sheet->setCellValueByColumnAndRow(9, $i, $certificate->verification_date);
                            $sheet->setCellValueByColumnAndRow(10, $i, $certificate->validity_date);
                            $sheet->setCellValueByColumnAndRow(11, $i, $certificate->cycle);
                            $sheet->setCellValueByColumnAndRow(12, $i, $certificate->remark);
                            $i++;
                        }
                        $i = $i - $i % 25 + 25;
                    }
                    @ob_end_clean();
                    if ($i > 1) {
                        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                        if (File::isDirectory($path . '/周检通知单') == false) {
                            File::makeDirectory($path . '/周检通知单', 0777, true, true);
                        }
                        $writer->save($path . "/周检通知单/周检通知单$unit4$unit2$year.xls");
                    }
                }
            }
        }

        $zip = new ZipArchive();
        if ($zip->open($path . '/周检通知' . date('Y-m-d') . '.zip', ZipArchive::CREATE) === TRUE) {
            $this->addFileToZip($path, $zip);
            $zip->close();
        }
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($path . '/周检通知' . date('Y-m-d') . '.zip'));
        header("Content-Disposition: attachment; filename=周检通知" . date('Y-m-d') . ".zip");
        readfile($path . '/周检通知' . date('Y-m-d') . '.zip');
    }


}
