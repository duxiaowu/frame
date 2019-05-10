<?php

namespace App\Utils;
class Db
{

    private static $_instance = null; //该类中的唯一一个实例
    private $dbConn;

    private function __construct($dbConfig)
    {//防止在外部实例化该类
        $this->dbConn = mysqli_init();
        $this->dbConn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 120); //设置超时时间
        $this->dbConn->real_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['db'], $dbConfig['port']);
        $this->dbConn->query("set names utf8");
    }

    private function __clone()
    {

    }

//禁止通过复制的方式实例化该类

    public static function getInstance($dbConfig)
    {
        if (self::$_instance == null) {
            self::$_instance = new self($dbConfig);
        }
        return self::$_instance;
    }

    public function getCode($args)
    {
        $code = '';
        if (is_array($args)) {
            foreach ($args as $k => $v) {
                $code .= "`$k`='$v',";
            }
        }
        $code = substr($code, 0, -1);
        return $code;
    }

    public function insert($table, $data)
    {
        $value = $this->getCode($data);
        $sql = "insert into {$table} set {$value}";
        if ($this->query($sql)) {
            return $this->dbConn->insert_id;
        }
    }

    public function select($table, $filed, $condition = array(), $order = '')
    {
        $sql = "select {$filed} from {$table} ";
        if (!empty($condition)) {
            $sql .= "where {$condition['key']} {$condition['type']}  {$condition['value']} {$order}";
        }
        $result = $this->query($sql);
        if (!$result) {
            return false;
        }
        $result_arr = array();
        while ($row = $result->fetch_assoc()) {
            $result_arr[] = $row;
        }
        return $result_arr;
    }

    public function update($table, $val, $condition)
    {
        $value = $this->getCode($val);
        $sql = "update {$table} set {$value}  where {$condition['key']} {$condition['type']}  {$condition['value']}";
        if ($this->query($sql)) {
            return TRUE;
        }
    }

    public function delete($table, $condition)
    {
        $sql = "delete from  $table where  {$condition['key']} {$condition['type']}  {$condition['value']}";
        if ($this->query($sql)) {
            return TRUE;
        }
    }

    public function query($sql)
    {
        $result = $this->dbConn->query($sql);
        if ($result) {
            return $result;
        } else {
           return false;
        }
    }


    public function getError()
    {
        return $this->dbConn->error;
    }

}
