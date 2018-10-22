<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des: 诊股数据一只股一天一次
 */

namespace App\Model;

use App\Utils\Factory;

class  YunYingModel extends model
{
    protected static $key = "mb_topics_yunying"; //mb_topics_zq_300033_0828

    public static function get($platform, $ver)
    {
        $arr = array();
        $data = self::getData();
        if (!$data) {
            return $arr;
        }
        foreach ($data as $key => $value) {
            if ($value['starttime'] > $now || $value['endtime'] < $now) {
                continue;
            }
            if (!empty($value['version'])) {
                if ($value['version'][$platform . 'platform']['start'] > $ver || $value['version'][$platform . 'platform']['end'] < $ver) {
                    continue;
                }
            }

            $arr = array(
                'id' => $value['id'],
                'title' => $value['title'],
                'subTitle' => $value['subTitle'],
                'tag' => $value['tag'],
                'jumpurl' => $value['jumpurl'],
                'qsid' => explode(',', $value['qsid']),
                'num' => $value['position'],
            );

        }
        return $arr;
    }

    private static function getData()
    {
        $conn = Factory::getRedis();
        $key = ZgModel::$key;
        $res = $conn->get($key);
        return json_decode($res);
    }

}