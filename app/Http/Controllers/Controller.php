<?php

namespace App\Http\Controllers;

use App\Jobs\Pdf2Jpg;
use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\ToolsView;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use QL\QueryList;
use GuzzleHttp;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getSn($position_id)
    {
        DB::select('CALL get_sn(?,@sn)', [$position_id]);
        return DB::select('SELECT @sn AS sn')[0]->sn;
    }

    function getInfo(ToolsView $tool)
    {
        return $tool;
    }

    function getFactories($tool_id)
    {
        $factories = Factory::where('tool_id', $tool_id)->orderBy('factory')->get();
        return $factories;
    }

    function getNumbers(Factory $factory_id)
    {
        $numbers = Number::where('factory_id', $factory_id->id)->where(function ($query) {
            if (isset($_GET['number_id']) && $_GET['number_id'] == 0) {
                $query->where('state_id', Parameter::where('name', '待检')->first()->id)
                    ->orWhere('state_id', Parameter::where('name', '在用')->first()->id);
            } else {
                $query->where('state_id', Parameter::where('name', '待检')->first()->id)
                    ->orWhere('id', isset($_GET['number_id']) ? $_GET['number_id'] : 0);
            }
        })->orderBy('number')->get();
        return $numbers;
    }

    function getNumber($number_id)
    {
        return Certificate::selectRaw('LEFT(IFNULL(MIN(verification_date),NOW()),4) AS start,COUNT(*) + 1 AS times')->where('number_id', $number_id)->first();
    }

    function getStandards($path)
    {
        exec("python F:/phpstudy_pro/WWW/laravel8/python/get_standard_from_pdf.py 2>&1 " . str_replace('"', '\"', $path), $out, $status);
        if ($status == 0) {
            return json_decode($out[count($out) - 1]);
        }
        return false;
    }

    function pdf(Request $request)
    {
        if ($request->hasFile('file_certificate')) {
            $file = $request->file('file_certificate');
            exec("python F:/phpstudy_pro/WWW/laravel8/python/get_qr_code_from_pdf.py  2>&1 " . $file->path(), $out, $status);
            if ($status == 0 && count($out) > 0) {
                switch (substr($out[0], 0, 20)) {
                    case '':
                        return '识别二维码失败';
                    case 'http://lims.njsjly.c':
                        $result = $this->nanjing($out[0]);
                        break;
                    case 'https://serv.jsmi.co':
                        $result = $this->jiangsu_new($out[0]);
                        break;
                    case 'https://www.jsmi.com.':
                        $result = $this->jiangsu_old($out[0]);
                        break;
                    default:
                        return '识别成功,未接入API,请联系管理员.';
                }
                $result['standard_id'] = $this->getStandards($file->path())[0]->standards;
                if (Certificate::where('certificate_no', $result['certificate_no'])->where('certificate_no', '<>', $request->certificate_no)->count()) {
                    $result['exist'] = 1;
                }
                $number = Number::where('number', $result['number'])->get();
                if ($number->count() == 1) {
                    $factory = Factory::where('id', $number->first()->factory_id)->first();
                    $result['number_id'] = $number->first()->id;
                    $result['factory_id'] = $factory->id;
                    $result['tool_id'] = $factory->tool_id;
                }
                return $result;
            } else {
                return '识别失败.';
            }
        }
    }

    function nanjing($url)
    {
        $id = explode('?zsId=', $url)[1];
        $client = new GuzzleHttp\Client(['verify' => false]);
        $res = $client->get('https://lims.njsjly.com/cmiims/f/sys/webQuery/inquiryByIdInfo?' . $this->nanjing_encode('{"id":"' . $id . '"}'));
        $body = json_decode($this->nanjing_decode($res->getBody()->getContents()));
        $ccbh = isset($body->certificateInfo->ccbh) && $body->certificateInfo->ccbh !== null && $body->certificateInfo->ccbh !== '/' ? $body->certificateInfo->ccbh : '';
        $sbbh = isset($body->certificateInfo->sbbh) && $body->certificateInfo->sbbh !== null && $body->certificateInfo->sbbh !== '/' ? $body->certificateInfo->sbbh : '';
        $data = array(
            'tool' => $body->certificateInfo->qj,
            'model' => $body->certificateInfo->xhgg,
            'factory' => isset($body->certificateInfo->zzcs) ? $body->certificateInfo->zzcs : '',
            'number' => $ccbh . $sbbh,
            'category' => $body->certificateInfo->zslx,
            'verification_date' => $body->certificateInfo->jd_rq,
            'certificate_no' => $body->certificateInfo->zs_bh,
            'department' => '市计量院',
        );
        return $data;
    }

    function jiangsu_new($url)
    {
        $id = explode('?zsh=', $url)[1];
        $client = new GuzzleHttp\Client(['verify' => false]);
        $res = $client->get("https://serv.jsmi.com.cn/admin/zs/getByZshEwm/$id");
        $body = json_decode($res->getBody()->getContents());
        $data = array(
            'tool' => $body->data->zsQjmc,
            'model' => $body->data->zsXhgg,
            'factory' => $body->data->zsZzc,
            'number' => (($body->data->zsCcbh == null || $body->data->zsCcbh == '/') ? '' : $body->data->zsCcbh) . (($body->data->zsSbbh == null || $body->data->zsSbbh == '/') ? '' : $body->data->zsSbbh),
            'category' => $body->data->zsZslx,
            'verification_date' => $body->data->zsJdrq,
            'certificate_no' => $body->data->zsZsh,
            'department' => '省计量院',
        );
        return $data;
    }

    function jiangsu_old($url)
    {
        $rules = array(
            'tool' => ['#txtQJMC', 'text'],
            'model' => ['#txtXHGG', 'text'],
            'factory' => ['#txtZZC', 'text'],
            'number' => ['#txtCCBH', 'text'],
            'category' => ['#txtZSLX', 'text'],
            'verification_date' => ['#txtJDRQ', 'text'],
            'certificate_no' => ['#txtZSH', 'text'],
        );
        $data = QueryList::get($url)->rules($rules)->query()->getData();
        $data['department'] = '省计量院';
        return $data;
    }


    function pdf2jpg($id)
    {
        Pdf2Jpg::dispatch($id);
    }

    function addFileToZip($path, $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                if (is_dir($path . '/' . $filename)) {
                    $this->addFileToZip($path . '/' . $filename, $zip);
                } else {
                    $zip->addFile($path . '/' . $filename, str_replace('storage/' . \Auth::user()->id . '/', '', $path) . '/' . $filename);
                }
            }
        }
        closedir($handler);
    }

    function nanjing_decode($str)
    {
        #base64解密
        $str = base64_decode($str);
        #AES和HEX解密
        $key = "njmind.comnjsjly";
        $str = openssl_decrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64解密
        $str = base64_decode($str);
        return $str;
    }

    function nanjing_encode($str)
    {
        #base64加密
        $str = base64_encode($str);
        #AES和HEX加密
        $key = "njmind.comnjsjly";
        $str = openssl_encrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64加密
        $str = base64_encode($str);
        return $str;
    }

    function jiangsu_decode($str, $key)
    {
        #base64解密
        $str = base64_decode($str);
        #AES和HEX解密
        $str = openssl_decrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        return $str;
    }

    function jiangsu_encode($str, $key)
    {
        #AES和HEX加密
        $str = openssl_encrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64加密
        $str = base64_encode($str);
        return $str;
    }

    function toBadges($str, $type)
    {
        $texts = explode(',', $str);
        $badges = [];
        foreach ($texts as $text) {
            $badges[] = "<span class='badge bg-" . e($type) . "-subtle border border-" . e($type) . "-subtle text-" . e($type) . "-emphasis'>" . e($text) . "</span>";
        }
        return implode(' ', $badges);
    }

    function toValidate($str, $validate)
    {
        return $validate
            ? e($str)
            : "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>" . e($str) . "</span>";
    }

    function toManyBadges($str, $items, $field)
    {
        $ids = explode(',', $str);
        return $items->map(function ($item) use ($ids, $field) {
            if (in_array($item->id, $ids)) {
                $html = '<a class="badge btn btn-outline-secondary text-secondary-emphasis" href="#" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="';
                $html .= $item->children->map(function ($item) use ($ids, $field) {
                    $html = in_array($item->id, $ids)
                        ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($item->{$field}) . "</span> "
                        : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($item->{$field}) . "</span> ";
                    $html .= $item->children->map(function ($item) use ($ids, $field) {
                        return in_array($item->id, $ids)
                            ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($item->{$field}) . "</span>"
                            : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($item->{$field}) . "</span>";
                    })->implode(' ');
                    return $html;
                })->implode('<br>');
                $html .= '"> ' . e($item->{$field}) . '</a>';
                return $html;
            } else {
                return "<span class='badge btn btn-outline-secondary text-secondary-emphasis invisible'>" . e($item->{$field}) . "</span>";
            }
        })->implode(' ');
    }

    function toLevel($data, $level, $column, $field, $menu, $type, $validate)
    {
        $type = $validate ? $type : 'danger';
        $text = $field . ($data->level - $column + 1);
        if ($level == $column) {
            return "<span class='badge bg-" . e($type) . "-subtle border border-" . e($type) . "-subtle text-" . e($type) . "-emphasis'>" . e($data->{$text}) . "</span>";
        } elseif ($level == $column - 1) {
            return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='" . e($menu) . "' data-id='" . e($data->id) . "'>增加</span>";
        } elseif ($level > ($column - 2)) {
            return "<span class='badge text-dark-emphasis'>" . e($data->{$text}) . "</span>";
        }
    }

    function toSearch($query, $request, $lists)
    {
        if (empty($search = $request->search['value'])) {
            return $query;
        }
        $terms = array_filter(explode(' ', $search));
        return $query->where(function ($q) use ($terms, $lists) {
            foreach ($terms as $term) {
                $q->where(function ($innerQ) use ($term, $lists) {
                    foreach ($lists as $list) {
                        $innerQ->orWhere($list, 'like', "%{$term}%");
                    }
                });
            }
        });
    }

    function toOrder($query, $request, $lists)
    {
        if (empty($order = $request->order)) {
            return $query;
        }
        return $query->orderBy($lists[$order[0]['column']], $order[0]['dir']);
    }

    function moveUpDown($model, $type)
    {
        $query = $model::where('pid', $model->pid);
        if ($type) {
            $result = $query->where('sort', '<', $model->sort)->max('sort');
        } else {
            $result = $query->where('sort', '>', $model->sort)->min('sort');
        }
        if ($result) {
            $exchangeModel = $model::where('sort', $result)->first();
            $exchangeModel->sort = $model->sort;
            $model->sort = $result;

            return !!$model->save() && !!$exchangeModel->save();
        }
        return $type ? '已经是最顶层,无法上移' : '已经是最底层,无法下移';
    }

    function delete($model, $str, $lists, $field)
    {
        $ids = explode(',', $str);
        $result = $model::whereIn('id', $ids)
            ->where(function ($query) use ($lists) {
                foreach ($lists as $list) {
                    $query->orHas($list);
                }
            })
            ->pluck($field)
            ->implode(',');
        if ($result) {
            return $result . '使用中,无法删除';
        } else {
            return !!$model::whereIn('id', $ids)->delete();
        }
    }
}

