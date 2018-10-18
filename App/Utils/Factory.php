<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/17
 * des: 工厂模式先取注册树上的对象取不到重新new
 */

namespace App\Utils;

class Factory
{
    static function getDb()
    {
        $db = Register::get('db');
        if (!$db) {
            $db = Db::getInstance($GLOBALS['dbConfig']);
            Register::set('db', $db);
        }
        return $db;
    }

    static function getRedis()
    {
        $conn = Register::get('redis');
        if (!$conn) {
            $redis = RedisSingle::getInstance($GLOBALS['redisConfig']);
            $redis->connect();
            $conn = $redis->getRedis();
            Register::set('redis', $conn);
        }
        return $conn;
    }

    static function getMongo()
    {
        $mongo = Register::get('mongo');
        if (!$mongo) {
            $mongo = Mongo::getInstance($GLOBALS['mongoConfig']);
            Register::set('mongo', $mongo);
        }
        return $mongo;
    }
}