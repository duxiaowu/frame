<?php
/**
 * Redis工具类
 */

namespace App\Utils;

trait RedisTrait {
    protected $_redis = null; // redis实例
    protected $_config = array();// redis配置
    protected $_usePContent = true;

    /**
     * Redis连接（失败就会抛出异常）
     */
    public function connect() {
        if ($this->_redis === null) {
            $this->_redis = new \Redis();
            // 配置读取
            $host = isset($this->_config['host']) ? $this->_config['host'] : 'localhost';
            $port = isset($this->_config['port']) ? $this->_config['port'] : '6379';
            $auth = isset($this->_config['auth']) ? $this->_config['auth'] : null;
            // redis连接
            if ($this->_usePContent) {
                $ret = $this->_redis->pconnect($host, $port);
            } else {
                $ret = $this->_redis->connect($host, $port);
            }
            if ($ret === false) {
                throw new \RedisException('Cannot connect to redis server.');
            }
            if ($auth !== null) {
                $ret = $this->_redis->auth($auth);
            }
            if ($ret === false) {
                throw new \RedisException('Redis password is not correct.');
            }
        }

        $ret = $this->_redis->ping();
        if ($ret !== '+PONG') {
            $this->_redis->close();
            $this->_redis = null;
            throw new \RedisException('Cannot connect(ping) to redis server.');
        }
        return $this->_redis;
    }

    /**
     * 获取Redis实例
     *
     * @return \Redis
     */
    public function getRedis() {
        return $this->_redis;
    }

    /**
     * 析构函数
     */
    public function __destruct() {
        if ($this->_redis instanceof \Redis) {
            try {
                $this->_redis->close();
            } catch (\RedisException $e) {

            }
        }
    }
}
