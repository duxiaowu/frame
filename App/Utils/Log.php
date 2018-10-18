<?php
/**
 * 日志工具类
 */

namespace App\Utils;

class Log
{
    private $_root;

    public function __construct($root = '')
    {
        ($root == "") ? $this->_root = "/tmp/" : $this->_root = $root;
    }

    public function write($msg)
    {
        if (!defined('__SCRIPT')) {
            define('__SCRIPT', 'unknow');
        }
        $dir = $this->_root . '/' . date('Ymd');
        if (!file_exists($dir)) {
            $this->mkdirs($dir);
        }
        $logFile = $dir . '/' . __SCRIPT . '.log';

        $msg = '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n";
        file_put_contents($logFile, $msg, FILE_APPEND);
    }

    private function mkdirs($dir, $mode = 0755)
    {
        if (!is_dir($dir)) {
            $this->mkdirs(dirname($dir), $mode);
            return @mkdir($dir, $mode);
        }
        return true;
    }
}