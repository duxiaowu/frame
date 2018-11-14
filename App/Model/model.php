<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */
namespace App\Model;
use App\Utils\Factory;

abstract class  model
{
    public static function webLog($msg)
    {
        $log = Factory::getLogTool('web');
        $log->write($msg);
    }

    public static function cronLog($msg)
    {
        $log = Factory::getLogTool('cron');
        $log->write($msg);
    }
}