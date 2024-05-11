<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SampleController extends Controller
{
    public function index()
    {
        $dates = [];
        $id = [];
        $certificates = CertificatesView::orderBy('order')->get();
        foreach ($certificates as $certificate) {
            $folderPath = "public/check/$certificate->id";
            if (Storage::exists($folderPath)) {
                $files = Storage::files($folderPath);
                foreach ($files as $file) {
                    $dates[] = substr($file, 18, 10);
                    $id[substr($file, 18, 10)][] = $certificate->id;
                }
            }
        }
        $dates = array_unique($dates);
        rsort($dates);
        return view("sample.index", compact('dates', 'id'));
    }

    public function list()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $data = CertificatesView::selectRaw("`order`,`position`,`unit1`,`instrument`,`model`,`number`,`limit`,'完好' AS `mark`,`remark`")->whereIn('id', explode(',', $id))->orderBy('order')->get()->toArray();
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function show($date)
    {
        $checks = [];
        $certificates = CertificatesView::where('verification_date', '<', $date)->where('validity_date', '>', $date)->orderBy('order')->get();
        foreach ($certificates as $certificate) {
            $folderPath = "public/check/$certificate->id";
            if (Storage::exists($folderPath)) {
                $files = Storage::files($folderPath);
                foreach ($files as $file) {
                    if (substr($file, 18, 10) == $date) {
                        $checks[$certificate->position][] = [
                            'path' => $file,
                            'certificate' => $certificate,
                        ];
                    }
                }
            }
        }
        return view('sample.show', compact('checks'));
    }

    public
    function store(Request $request)
    {
        $i = 1;
        $inputFileName = 'storage/mould/抽检记录模板.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $certificates = CertificatesView::whereIn('id', explode(',', $request->id))->orderBy('order')->get();
        foreach ($certificates as $certificate) {
            if ($i % 24 == 23) {
                $i = $i + 2;
            }
            if ($i % 24 == 1) {
                $i1 = $i + 1;
                $i2 = $i + 2;
                $i3 = $i + 3;
                $i4 = $i + 4;
                $i5 = $i + 5;
                $i21 = $i + 21;
                $i22 = $i + 22;
                $i23 = $i + 23;
                //第一行
                $sheet->mergeCells("A$i:J$i");
                $styleArray = [
                    'alignment' => [
                        'horizontal' => 'right',
                        'vertical' => 'bottom',
                    ],
                    'font' => [
                        'name' => '宋体',
                        'size' => 10,
                    ],
                    'numberFormat' => [
                        'formatCode' => '@'
                    ],
                ];
                $sheet->getStyle("A$i")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(1, $i, "格式Form：NJJL/QSR34KJZL Rev0");
                //第二行
                $sheet->mergeCells("A$i1:J$i1");
                $styleArray = [
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'bottom',
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
                $sheet->getStyle("A$i1")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(1, $i1, "监  视  与  测  量  设  备  抽  检  记  录");
                //第三行
                $sheet->mergeCells("A$i2:J$i2");
                $styleArray = [
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'top',
                    ],
                    'font' => [
                        'name' => '宋体',
                        'size' => 12,
                        'bold' => true,
                    ],
                    'numberFormat' => [
                        'formatCode' => '@'
                    ],
                ];
                $sheet->getStyle("A$i2")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(1, $i2, "Spot Check Record for Monitoring and Measurement Equipment");
                //第四行
                $sheet->mergeCells("A$i3:J$i3");
                $styleArray = [
                    'alignment' => [
                        'horizontal' => 'left',
                        'vertical' => 'bottom',
                    ],
                    'font' => [
                        'name' => '宋体',
                        'size' => 10,
                    ],
                    'numberFormat' => [
                        'formatCode' => '@'
                    ],
                ];
                $sheet->getStyle("A$i3")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(1, $i3, "抽检日期Checked on: $request->date");
                //第五行
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
                $sheet->getStyle("A$i4:J$i4")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(1, $i4, "序号");
                $sheet->setCellValueByColumnAndRow(2, $i4, "使用岗位");
                $sheet->setCellValueByColumnAndRow(3, $i4, "使用者");
                $sheet->setCellValueByColumnAndRow(4, $i4, "器具名称");
                $sheet->setCellValueByColumnAndRow(5, $i4, "规格型号");
                $sheet->setCellValueByColumnAndRow(6, $i4, "出厂编号");
                $sheet->setCellValueByColumnAndRow(7, $i4, "检测范围");
                $sheet->setCellValueByColumnAndRow(8, $i4, "检定合格证");
                $sheet->setCellValueByColumnAndRow(9, $i4, "备注");
                $sheet->setCellValueByColumnAndRow(10, $i4, "被检查人签字");
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
                $sheet->getStyle("A$i5:J$i21")->applyFromArray($styleArray);
                $styleArray = [
                    'alignment' => [
                        'horizontal' => 'center',
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
                $sheet->getStyle("A$i22:J$i23")->applyFromArray($styleArray);
                $sheet->setCellValueByColumnAndRow(2, $i22, "抽检人:");
                $sheet->setCellValueByColumnAndRow(2, $i23, "Checked by:");
                if (is_file('storage/sign/1.png')) {
                    $drawing = new Drawing();
                    $drawing->setPath('storage/sign/1.png');
                    $drawing->setHeight(40);
                    $drawing->setCoordinates("C$i22");
                    $drawing->setWorksheet($sheet);
                }
                $i = $i + 5;
            }
            $sheet->setCellValueByColumnAndRow(1, $i, $certificate->order);
            $sheet->setCellValueByColumnAndRow(2, $i, $certificate->position);
            $sheet->setCellValueByColumnAndRow(3, $i, $certificate->unit1);
            $sheet->setCellValueByColumnAndRow(4, $i, $certificate->instrument);
            $sheet->setCellValueByColumnAndRow(5, $i, $certificate->model);
            $sheet->setCellValueByColumnAndRow(6, $i, $certificate->number);
            $sheet->setCellValueByColumnAndRow(7, $i, $certificate->limit);
            $sheet->setCellValueByColumnAndRow(8, $i, '完好');
            if (is_file("storage/sign/$certificate->unit4$certificate->unit2.png")) {
                $drawing = new Drawing();
                $drawing->setPath("storage/sign/$certificate->unit4$certificate->unit2.png");
                $drawing->setHeight(40);
                $drawing->setCoordinates("J$i");
                $drawing->setWorksheet($sheet);
            }
            $i++;
        }
        for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
            $sheet->getRowDimension($row)->setRowHeight(25);
        }
        ob_start();
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        return response(ob_get_clean())
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="抽检记录' . $request->date . '.xlsx"');
    }
}
