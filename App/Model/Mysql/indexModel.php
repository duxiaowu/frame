<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */

namespace App\Model\Mysql;

use App\Utils\Factory;

class indexModel extends model
{
    protected static $table = 'eq_ztfp_info';

    public static function add($data)
    {
        $db = Factory::getDb();
        $table = indexModel::$table;
        $res = $db->insert($table, $data);
        return $res;
    }

    public static function update($data, $condition)
    {
        $db = Factory::getDb();
        $table = indexModel::$table;
        $res = $db->update($table, $data, $condition);
        return $res;
    }

    public static function delete($condition)
    {
        $db = Factory::getDb();
        $table = indexModel::$table;
        $res = $db->delete($table, $condition);
        return $res;
    }

    public static function getList($filed = "*", $condition = array(), $order = '')
    {
        $db = Factory::getDb();
        $table = indexModel::$table;
        $res = $db->select($table, $filed, $condition, $order);
        return $res;
    }

    public static function query($sql)
    {
        $db = Factory::getDb();
        $res = $db->query($sql);
        return $res;
    }
}