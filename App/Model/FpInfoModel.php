<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des: cbas获取复盘信息
 */

namespace App\Model;

use App\Utils\Factory;

class  FpInfoModel extends model
{
    protected static $table = 'topic_';

    public static function addnew($userid)
    {
        $data = self::getNewInfo($userid);
        if (empty($data)) return true;
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid,-2);
        $res = $Mongo->insert($table, $data);
        return $res;
    }

    public static function update($userid, $data)
    {
        $condition = ['userid' => $userid];
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid,-2);
        $res = $Mongo->update($table, $condition, $data);
        return $res;
    }

    public static function delete($userid)
    {
        $condition = ['userid' => $userid];
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid,-2);
        $res = $Mongo->delete($table, $condition);
        return $res;
    }

    public static function getList($userid, $filed = "*", $order = '')
    {
        $arr = array();
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid,-2);
        $filter = ['userid' => $userid];
        $option = [
            'projection' => [
                'data' => 1,
                'type' => 1,
                'time' => 1,
            ],
            'limit' => 10,
            'sort' => [
                'time' => -1
            ],
        ];
        $res = $Mongo->select($table,$filter,$option);
        foreach ($res as $value) {
            $tmpArr = array(
                'id' => $value->data,
                'type' => $value->type,
                'time' => $value->time,
            );
            $arr[] = $tmpArr;
        }
        return $arr;
    }

    protected static function getNewInfo($userid)
    {
        $arr = array();
        $url = sprintf(CBAS_URL, $userid);
        $openApi = Factory::getOpenApi('auth');
        $data = $openApi->getData($url);
        if (in_array($data['status_code'], array('-104', '-1', '-105', '-106'))) {
            echo json_encode($data);
            $openApi->curlToken();
            $data = $openApi->getData($url);
        }
        $data = $data['data'];
        if (!isset($data['jx_post'])) $data['jx_post'] = array();
        if (!isset($data['pt_post'])) $data['pt_post'] = array();
        $postArr = array_merge($data['pt_post'], $data['jx_post']);
        array_multisort(array_column($postArr, 'time'), SORT_DESC, $postArr);
        unset($data['jx_post']);
        unset($data['pt_post']);
        $newData['pt_post'] = $postArr;
        $newData['seq'] = $data['seq'];
        $newData['zg'] = $data['zg'];
        unset($data);
        foreach ($newData as $key => $value) {
            foreach ($value as $info) {
                $data_id = ($key == "zg") ? $info['code'] : $info['id'];
                $arr[] = array(
                    "userid" => $userid,
                    "type" => $key,
                    "time" => microtime(true),
                    "data" => $data_id,
                );
            }
        }
        return $arr;
    }
}