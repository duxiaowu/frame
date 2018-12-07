<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */

namespace App\Utils;
use MongoDB\Driver\BulkWrite as BulkManager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Manager as MongoDBManager;
use MongoDB\Driver\Query as DriveQuery;
use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\WriteConcern;
class Mongo
{
    private $_mongo;
    private $_bulk;
    private $_db;
    private static $_instance = null; //该类中的唯一一个实例

    protected function __construct($mongoConfig)
    {
        $dsn = $mongoConfig['dsn'];
        $this->_mongo = new MongoDBManager($dsn);
        $this->_bulk = new BulkManager();
        $this->_db = $mongoConfig['db'];
    }

    private function __clone()
    {

    }

    //禁止通过复制的方式实例化该类

    public static function getInstance($mongoConfig)
    {
        if (self::$_instance == null) {
            self::$_instance = new self($mongoConfig);
        }
        return self::$_instance;
    }

    public function insert($table, $data)
    {
        foreach ($data as $value) {
            $this->_bulk->insert($value);
        }
        return $this->_mongo->executeBulkWrite($this->_db . '.' . $table, $this->_bulk);
    }

    public function update($table,$condition, $data)
    {
        $this->_bulk->update(
            $condition,
            ['$set' => $data],
            ['multi' => true, 'upsert' => false]
        );
        return $this->_mongo->executeBulkWrite($this->_db . '.' . $table, $this->_bulk);
    }

    public function delete($table, $condition, $limit = 0)
    {
        $this->_bulk->delete($condition, ['limit' => $limit]);   // limit 为 1 时，删除第一条匹配数据
        return $this->_mongo->executeBulkWrite($this->_db . '.' . $table, $this->_bulk);
    }

    public function select($table, $filter = array(), $options = array())
    {
        $arr = array();
        $query = new DriveQuery($filter, $options);
        $cursor = $this->_mongo->executeQuery($this->_db . '.' . $table, $query);
        foreach ($cursor as $document) {
            $arr[] = $document;
        }
        return $arr;
    }
}

