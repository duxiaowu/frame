<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des:
 */

define('_DATA','var/www/data/topics'); // 数据存储路径
define("_LOG", "/var/www/log/topics");
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
$GLOBALS['redisNodeConfig'] = array('10.10.80.44:7000', '10.10.80.44:7001', '10.10.80.44:7002', '10.10.80.44:7003', '10.10.80.44:7004', '10.10.80.44:7005', 1.5, 1.5);
define("OPENAI_IP", "http://172.20.207.218/");
define("APPID","8e21aaDE5D77");
define("SECRET","690bf4d85e688322e63ada05479a5249");
#define("CBAS_URL", OPENAI_IP . "compute/v1/oprt_exp_server?uid=%s&expid=1");
define("CBAS_URL", "http://10.10.24.190/duweibin/newTopics/s.php");
#define("TOKEN_URL", OPENAI_IP . "auth/v1/token?appid=".APPID."&secret=".SECRET);
define("TOKEN_URL", "http://10.10.24.190/duweibin/newTopics/t.php");
define("SNS_KEY","mb_topics_sns_%s");//mb_topics_seq_605443568

define("SNS_URL","http://t.10jqka.com.cn/api.php?method=post.getPostDataForCa&pid=%s");