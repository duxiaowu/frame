<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */
namespace App\Model\Redis;
use App\Utils\Factory;
class  indexModel extends model {
    public static function get() {
        $conn = Factory::getRedis();
        $res = $conn->get("a");
        return $res;
    }
}