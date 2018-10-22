<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des: 诊股数据一只股一天一次
 */

namespace App\Model;

use App\Utils\Factory;

class  ZgModel extends model
{
    protected static $pre = "mb_topics_zg_"; //mb_topics_zq_300033_0828

    public static function get($code, $date)
    {
        $conn = Factory::getRedis();
        $key = ZgModel::$pre . $code . '_' . $date;
        $res = $conn->get($key);
        return $res;
    }

    public static function set($code, $date, $content)
    {
        $conn = Factory::getRedis();
        $key = ZgModel::$pre . $code . '_' . $date;
        $res = $conn->set($key, $content);
        return $res;
    }
}