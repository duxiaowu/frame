<?php
/**
 * 日志工具类
 */

namespace App\Utils;

class Log
{
    private $_root;

    public function __construct($type = '')
    {
        if($type == "")  $this->_root = "/tmp/" ;
        $this->_root = _LOG . '/' . $type . '/';
    }

    public function write($msg)
    {
        if(isset($_REQUEST['con'])) $module = $_REQUEST['con'];
        if(isset($argv[1])) $module = $argv[1];
        $dir = $this->_root . '/' . date('Ymd');
        if (!file_exists($dir)) {
            $this->mkdirs($dir);
        }
        $logFile = $dir . '/' . $module . '.log';

        $msg = '[' . date('Y-m-d H:i:s') . ']' . $msg . PHP_EOL;
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