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
        $types = CertificatesView::selectRaw("unit2, unit4, SUBSTR(`validity_date`,1,7) as `date`")->where('validity_date', '>', '2020-08-01')->whereIn('unit2', ['南京钢管分公司', '防腐分公司', '科技质量中心'])->groupBy('date')->groupBy('unit2')->groupBy('unit4')->orderBy('date')->orderBy('unit2')->orderBy('unit4', 'DESC')->get();
        return view("cyclical.index", compact('types'));
    }

    public function list()
    {
        $date = isset($_GET['date']) ? $_GET['date'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $department = isset($_GET['department']) ? $_GET['department'] : '';
        $data = CertificatesView::select('order', 'position', 'instrument', 'model', 'number', 'limit', 'accuracy', 'factory', 'verification_date', 'validity_date', 'cycle', 'remark')->where('unit4', $type)->where('unit2', $department)->whereRaw("SUBSTR(`validity_date`,1,7) = '$date'")->orderBy('order')->get()->toArray();
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function store(Request $request)
    {
        $path = "storage/" . \Auth::user()->id;
        $types = CertificatesView::select('unit2', 'unit4')->whereRaw("SUBSTR(`validity_date`,1,7) = '$request->date'")->whereIn('unit2', ['南京钢管分公司', '防腐分公司', '科技质量中心'])->groupBy('unit2', 'unit4')->get();
        foreach ($types as $type) {
            $i = 1;
            $inputFileName = 'storage/mould/周检通知单模板.xlsx';
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $certificates = CertificatesView::where('unit2', $type->unit2)->where('unit4', $type->unit4)->whereRaw("SUBSTR(`validity_date`,1,7) = '$request->date'")->orderBy('order')->get();
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
                    $sheet->setCellValueByColumnAndRow(1, $i1, "编号:$type->unit2-$type->unit4-$request->date");
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
                    $sheet->setCellValueByColumnAndRow(2, $i24, "计量员:");
                    $sheet->setCellValueByColumnAndRow(5, $i24, "负责人:");
                    $sheet->setCellValueByColumnAndRow(9, $i24, "日期:$request->date");
                    if (is_file('storage/sign/1.png')) {
                        $drawing = new Drawing();
                        $drawing->setPath('storage/sign/1.png');
                        $drawing->setHeight(50);
                        $drawing->setCoordinates("C$i24");
                        $drawing->setWorksheet($sheet);
                    }
                    if (is_file("storage/sign/$type->unit4$type->unit2.png")) {
                        $drawing = new Drawing();
                        $drawing->setPath("storage/sign/$type->unit4$type->unit2.png");
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
            $i = ($i % 25 == 0) ? $i : $i - $i % 25 + 25;
            for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
                $sheet->getRowDimension($row)->setRowHeight($row % 25 == 0 ? 50 : 25);
            }
            if ($i > 1) {
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                if (File::isDirectory($path . '/周检通知单') == false) {
                    File::makeDirectory($path . '/周检通知单', 0777, true, true);
                }
                $writer->save($path . "/周检通知单/周检通知单" . $type->unit2 . $type->unit4 . $request->date . ".xlsx");
            }
        }
        $zip = new ZipArchive();
        if ($zip->open($path . '/周检通知单' . $request->date . '.zip', ZipArchive::CREATE) == TRUE) {
            $this->addFileToZip($path . '/周检通知单', $zip);
            $zip->close();
        }
        if (File::isDirectory($path . '/周检通知单')) {
            File::deleteDirectory($path . '/周检通知单');
        }
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($path . '/周检通知单' . $request->date . '.zip'));
        header("Content-Disposition: attachment; filename=周检通知单" . $request->date . ".zip");
        readfile($path . '/周检通知单' . $request->date . '.zip');
        @ob_end_clean();
        if (file_exists($path . '/周检通知单' . $request->date . '.zip')) {
            unlink($path . '/周检通知单' . $request->date . '.zip');
        }
    }
}
