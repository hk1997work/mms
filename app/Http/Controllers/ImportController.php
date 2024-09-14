<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\PositionsView;
use App\Models\Tool;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function update(Request $request)
    {
        $file = $request->file('import-file')->getRealPath();
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $zaiyong = Parameter::where('name', '在用')->first()->id;
        $daijian = Parameter::where('name', '待检')->first()->id;
        foreach ($worksheet->getRowIterator() as $key => $row) {
            if ($key != 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $row = [];
                $data = [];
                foreach ($cellIterator as $cell) {
                    $row[] = $cell->getValue();
                }
                if (!Certificate::where('certificate_no', $row[2])->exists()) {
                    $data['sn'] = $row[0];
                    $data['certificate_name'] = '';
                    $data['position_id'] = PositionsView::where('name4', $row[1])->where('name2', $row[4])->where('name1', $row[3])->where('level', 5)->first()->id;
                    $data['certificate_no'] = $row[2];
                    $tool_id = Tool::where('instrument', $row[5])->where('model', $row[6])->where('limit', $row[8])->where('accuracy', $row[9])->first()->id;
                    $factory = Factory::where('tool_id', $tool_id)->where('factory', $row[10])->first();
                    if ($factory) {
                        $factory_id = $factory->id;
                    } else {
                        $new_factory['tool_id'] = $tool_id;
                        $new_factory['factory'] = $row[10];
                        $new_factory['fullname'] = '';
                        $factory_id = Factory::create($new_factory)->id;
                    }
                    $number = Number::where('factory_id', $factory_id)->where('number', $row[7])->first();
                    if ($number) {
                        $data['number_id'] = $number->id;
                    } else {
                        $new_number['factory_id'] = $factory_id;
                        $new_number['number'] = $row[7];
                        $new_number['state_id'] = ($row[16] == '有效' ? $zaiyong : $daijian);
                        $data['number_id'] = Number::create($new_number)->id;
                    }
                    $data['verification_date'] = $row[11];
                    $data['validity_date'] = $row[12];
                    $data['valid'] = ($row[16] == '有效' ? 1 : 0);
                    $data['category_id'] = 0;
                    $data['department_id'] = Parameter::where('name', $row[15])->first()->id;
                    $data['start'] = 0;
                    $data['times'] = 0;
                    $data['remark'] = $row[17];
                    $data['start_date'] = $row[11];
                    $data['end_date'] = $row[12];
                    Certificate::create($data);
                }
            }
        }
        return 1;
    }
}
