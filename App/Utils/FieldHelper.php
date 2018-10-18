<?php
/**
 * 字段验证类（待扩展）
 */

namespace App\Utils;

class FieldHelper
{
    protected $fields     = array();
    protected $hasJsonGot = false; // 标记是否已经获取过Json

    /**
     * 获取通过json传递过来的数据
     *
     * @param string $name    字段名
     * @param string $default 默认值
     * @return array|bool|mixed|null
     */
    public function json($name = null, $default = null)
    {
        if (!$this->hasJsonGot) {
            $input = file_get_contents('php://input');
            $this->fields = json_decode($input, true);
            if ($this->fields === false) {
                $this->fields = array();
            }
        }
        if ($name === '' || $name === null) {
            return $this->fields;
        }
        return isset($this->fields[$name]) ? $this->fields[$name] : $default;
    }

    /**
     * 获取post过来的字段数据
     *
     * @param string $name    字段名 传入null或者空字符串将返回所有字段，形式为array(name => value)
     * @param string $default 默认值
     * @return null|string
     */
    public function post($name = null, $default = null)
    {
        if ($name === '' || $name === null) {
            return $_POST;
        }
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    /**
     * 获取get过来的字段数据
     *
     * @param string $name    字段名 传入null或者空字符串将返回所有字段，形式为array(name => value)
     * @param string $default 默认值
     * @return null|string
     */
    public function get($name = null, $default = null)
    {
        if ($name === '' || $name === null) {
            return $_GET;
        }
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    /**
     * 获取get和post过来的字段数据
     *
     * @param string $name    字段名 传入null或者空字符串将返回所有字段，形式为array(name => value)
     * @param string $default 默认值
     * @return mixed
     */
    public function getOrPost($name = null, $default = null)
    {
        if ($name === '' || $name === null) {
            return $_REQUEST;
        }
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * 判断是否为空（）
     *
     * @param $str
     * @return bool
     */
    public function isEmpty($str)
    {
        if (is_string($str)) {
            return !isset($str{0});
        }
        if (!is_int($str) && !is_float($str)) {
            return empty($str);
        }
        return false;
    }

    /**
     * 判断是否为url地址
     *
     * @param $url
     * @return bool
     */
    public function isUrl($url)
    {
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
        // 正则判断
        if (preg_match($regex, $url)) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为整数
     *
     * @param $str
     * @return bool
     */
    public function isInt($str)
    {
        return $this->isDigits($str);
    }

    /**
     * 判断是否为数字
     *
     * @param $str
     * @return bool
     */
    public function isNumber($str)
    {
        return is_numeric($str);
    }

    /**
     * 判断是否都为数字
     *
     * @param $str
     * @return bool
     */
    public function isDigits($str)
    {
        if ($str === '0') {
            return true;
        }
        return ctype_digit($str);
    }
}