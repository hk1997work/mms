<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Yajra\DataTables\DataTables;

class SampleController extends Controller
{
    public function index()
    {
        $dates = DB::table('certificate_check')->selectRaw("SUBSTRING(SUBSTRING_INDEX(filepath, '/', -1), 1, 10) AS date")->groupBy('date')->orderBy('date', 'desc')->pluck('date');
        return view("sample.index", compact('dates'));
    }

    public function list()
    {
        $date = isset($_GET['id']) ? $_GET['id'] : '';
        $query = CertificatesView::select('order', 'position1', 'position2', 'instrument', 'model', 'number', 'limit', DB::raw("'完好' AS status"), 'remark')
            ->leftJoin('certificate_check', 'certificates_views.id', 'certificate_check.certificate_id')
            ->whereRaw("SUBSTRING(SUBSTRING_INDEX(filepath, '/', -1), 1, 10) = ?", [$date])->orderBy('order');
        return DataTables::of($query)->make(false);
    }

    public function show($date)
    {
        $checks = [];
        $certificates = CertificatesView::select('position1', 'number', 'verification_date', 'validity_date', 'department', 'filepath')
            ->leftJoin('certificate_check', 'certificates_views.id', 'certificate_check.certificate_id')
            ->whereRaw("SUBSTRING(SUBSTRING_INDEX(filepath, '/', -1), 1, 10) = ?", [$date])->orderBy('order')->get();
        foreach ($certificates as $certificate) {
            $checks[$certificate->position1][] = $certificate;
        }
        return view('sample.show', compact('checks'));
    }

    public function store(Request $request)
    {
        $i = 1;
        $inputFileName = 'storage/mould/抽检记录模板.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $certificates = CertificatesView::select('certificates_views.*')
            ->leftJoin('certificate_check', 'certificates_views.id', 'certificate_check.certificate_id')
            ->whereRaw("SUBSTRING(SUBSTRING_INDEX(filepath, '/', -1), 1, 10) = ?", [$request->date])->orderBy('order')->get();
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
                        'formatCode' => '@',
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
                        'formatCode' => '@',
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
                        'formatCode' => '@',
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
                        'formatCode' => '@',
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
                        'formatCode' => '@',
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
                        'size' => 10,
                    ],
                    'numberFormat' => [
                        'formatCode' => '@',
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
                        'formatCode' => '@',
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
            $sheet->setCellValueByColumnAndRow(2, $i, $certificate->position1);
            $sheet->setCellValueByColumnAndRow(3, $i, $certificate->position2);
            $sheet->setCellValueByColumnAndRow(4, $i, $certificate->instrument);
            $sheet->setCellValueByColumnAndRow(5, $i, $certificate->model);
            $sheet->setCellValueByColumnAndRow(6, $i, $certificate->number);
            $sheet->setCellValueByColumnAndRow(7, $i, $certificate->limit);
            $sheet->setCellValueByColumnAndRow(8, $i, '完好');
            if (is_file("storage/sign/$certificate->position4.png")) {
                $drawing = new Drawing();
                $drawing->setPath("storage/sign/$certificate->position4.png");
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

    public function destroy(Request $request)
    {
        if (Storage::exists($request->filepath)) {
            Storage::delete($request->filepath);
        }
        return !!DB::table('certificate_check')->where('filepath', $request->filepath)->delete();
    }
}
