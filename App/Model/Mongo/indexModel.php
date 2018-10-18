<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */
namespace App\Model\Mongo;
use App\Utils\Factory;
class indexModel extends model {

     protected static $table = 'topic';
     public static function add($data) {
         $Mongo = Factory::getMongo();
         $table = indexModel::$table;
         $res = $Mongo->insert($table, $data);
         return $res;
     }
     public static function update($data, $condition){
         $Mongo = Factory::getMongo();
         $table = indexModel::$table;
         $res = $Mongo->update($table, $data, $condition);
         return $res;
     }
     public static function delete($condition){
         $Mongo = Factory::getMongo();
         $table = indexModel::$table;
         $res = $Mongo->delete($table, $condition);
         return $res;
     }
     public static function getList($filed = "*", $condition = array(), $order = ''){
         $Mongo = Factory::getMongo();
         $table = indexModel::$table;
         $res = $Mongo->select($table);
         return $res;
     }
}