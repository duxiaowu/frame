<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */

define('_DATA', _ROOT . '/data'); // 数据存储路径
define('_LOG', _ROOT . '/log'); // 日志路径
$GLOBALS['Decorator'] = array(
    'App\Decorator\Json',
    //'App\Decorator\Login',
    //'App\Decorator\Template',
);
$GLOBALS['redisConfig'] = array(
    'host' => 'localhost',
    'port' => '6379',
    'auth' => '10jqka@123',
);
$GLOBALS['dbConfig'] = array(
    'host' => '127.0.0.1',
    'port' => '3306',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'eq',
);
$GLOBALS['mongoConfig'] = array(
    'host' => '127.0.0.1',
    'port' => '27017',
    'db' => 'topic',
);

