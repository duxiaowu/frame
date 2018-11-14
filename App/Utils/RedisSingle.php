<?php
/**
 * Redis工具类
 */

namespace App\Utils;

/**
 * 单例Redis工具类
 */
class RedisSingle {
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
}
