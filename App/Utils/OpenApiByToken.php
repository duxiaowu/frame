<?php
/**
 * openapi工具类
 */
namespace App\Utils;

class OpenApiByToken
{
    use OpenApiTrait;
    protected static $token_key = 'mb_topics_token';
    protected static $token = null;

    public function __construct()
    {
        $this->getToken();
        $this->header = array("access-token:" . self::$token);
    }

    public function getToken()
    {
        $redis = Factory::getRedis();
        $token = $redis->get(self::$token_key);
        self::$token = $token;
        if (!$token) {
            $this->curlToken();
        }
    }

    public function curlToken()
    {
        $curl = Factory::getCurlTool();
        $curl->open(TOKEN_URL);
        try {
            $res = $curl->get();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $tokenArr = json_decode($res, true);
        self::$token = $tokenArr['access_token'];
        $redis = Factory::getRedis();
        $res = $redis->setnx(self::$token_key . '_lock', 1); // 加锁
        $redis->expire(self::$token_key . '_lock', 3);
        if (!$res) {
            return $redis->get(self::$token_key);
        }
        $redis->set(self::$token, self::$token_key);
        $redis->del(self::$token_key . '_lock'); //释放锁
        return self::$token;
    }
}


class OpenApiByAuth
{
    use OpenApiTrait;
    protected static $auth = "Basic OGUyMWFhREU1RDc3OjBiNTA1YTQ2NDE0ZGFlZWMxZjU5YzVhYjBjOGUyMDkx";

    public function __construct()
    {
        $this->header = array("Authorization:" . self::$auth);
    }
}

