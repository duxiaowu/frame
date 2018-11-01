<?php
/**
 * Redis工具类
 */

namespace App\Utils;
/**
 * Class RedisCluster
 * @package App\Utils
 * 连接redis集群
 */
class RedisCluster {
    use RedisTrait;
    private static $_instance = null; // 单例

    /**
     * 构造方法
     *
     * @param array $redisConfig Redis配置
     */
    private function __construct($redisConfig) {
        $this->_config = $redisConfig;
    }

    /**
     * 获取单例
     *
     * @param array $redisConfig Redis配置
     * @return self
     */
    public static function getInstance($redisConfig = array()) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($redisConfig);
        }
        return self::$_instance;
    }

    public function connect() {
        if ($this->_redis === null) {
            $this->_redis = \RedisClusterClass(
                null,
                $this->_config
            );
    }

        $ret = $this->_redis->ping();
        if ($ret !== '+PONG') {
            $this->_redis->close();
            $this->_redis = null;
            throw new \RedisException('Cannot connect(ping) to redis server.');
        }
        return $this->_redis;
    }
}