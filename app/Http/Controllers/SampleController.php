<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use App\Models\PositionsView;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use ZipArchive;

class SampleController extends Controller
{
    public function index()
    {
        $levels = PositionsView::select('name1')->where('level', 3)->where('sign', 0)->where('total', '>', 30)->distinct()->get();
        return view("sample.index",compact('levels'));
    }

    public function store(Request $request)
    {
        $path = "storage/" . \Auth::user()->username;
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
        $start_date = substr($request->daterange, 0, 10);
        $end_date = substr($request->daterange, -10);
        $periods = CarbonPeriod::create($start_date, $end_date)->toArray();

        $i = 1;
        $inputFileName = 'storage/mould/抽检记录模板.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDefaultRowDimension()->setRowHeight(24);

        foreach ($periods as $period) {
            $results = CertificatesView::select('id')->where('verification_date', '<=', $period)->where('validity_date', '>=', $period)->where('type', '计量器具')->whereIn('unit2', $request->position)->inRandomOrder()->limit(17);
            $ids = array_column($results->get()->toArray(), 'id');
            $certificates = CertificatesView::whereIn('id', $ids)->orderBy('order')->get();
            $date = substr($period, 0, 10);
            foreach ($certificates as $certificate) {
                if ($i % 24 == 0) {
                    $i++;
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
                    $sheet->setCellValueByColumnAndRow(1, $i3, "抽检日期Checked on: $date");
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
                    $sheet->setCellValueByColumnAndRow(5, $i4, "规格/型号");
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
                $sheet->setCellValueByColumnAndRow(1, $i, ($i) % 24 - 5);
                $sheet->setCellValueByColumnAndRow(2, $i, $certificate->position);
                $sheet->setCellValueByColumnAndRow(3, $i, $certificate->unit1);
                $sheet->setCellValueByColumnAndRow(4, $i, $certificate->instrument);
                $sheet->setCellValueByColumnAndRow(5, $i, $certificate->model);
                $sheet->setCellValueByColumnAndRow(6, $i, $certificate->number);
                $sheet->setCellValueByColumnAndRow(7, $i, $certificate->limit);
                $sheet->setCellValueByColumnAndRow(8, $i, Rand(0, 20) > 1 ? '完好' : '更换');
                if (is_file("storage/sign/$certificate->unit4$certificate->unit2.png")) {
                    $drawing = new Drawing();
                    $drawing->setPath("storage/sign/$certificate->unit4$certificate->unit2.png");
                    $drawing->setHeight(40);
                    $drawing->setCoordinates("J$i");
                    $drawing->setWorksheet($sheet);
                }
                $i++;
            }
            $i = $i - $i % 24 + 24;
        }

        @ob_end_clean();
        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        if (File::isDirectory($path . '/抽检记录') == false) {
            File::makeDirectory($path . '/抽检记录', 0777, true, true);
        }
        $writer->save($path . "/抽检记录/抽检记录.xls");


        $zip = new ZipArchive();
        if ($zip->open($path . '/抽检记录' . date('Y-m-d') . '.zip', ZipArchive::CREATE) === TRUE) {
            $this->addFileToZip($path, $zip);
            $zip->close();
        }
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($path . '/抽检记录' . date('Y-m-d') . '.zip'));
        header("Content-Disposition: attachment; filename=抽检记录" . date('Y-m-d') . ".zip");
        readfile($path . '/抽检记录' . date('Y-m-d') . '.zip');
    }
}
