<?php
/**
 * Curl工具类
 */

namespace App\Utils;

class Curl
{
    /**
     * _uri 要请求的url
     *
     * @var string
     * @access private
     */
    private $_uri;

    /**
     * _curl
     *
     * @var resource
     * @access private
     */
    private $_curl;

    /**
     * _options
     *
     * @var array
     * @access private
     */
    private $_options = array();

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        $this->_options['CURLOPT_HEADER'] = 0;
        $this->_options['CURLOPT_RETURNTRANSFER'] = 1;
        $this->_options['CURLOPT_USERAGENT'] = 'Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-CN; rv:1.8.1.20) Gecko/20081217 Firefox/2.0.0.20';
    }

    /**
     * open 初始化一个链接
     *
     * @param string $url
     * @param int    $timeout 超时时间
     * @return void
     * @access public
     */
    public function open($url, $timeout = 30)
    {
        if (!is_resource($this->_curl)) {
            $this->_curl = curl_init();
        }
        $this->_uri = $url;
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, $this->_options['CURLOPT_RETURNTRANSFER']);
        curl_setopt($this->_curl, CURLOPT_USERAGENT, $this->_options['CURLOPT_USERAGENT']);
        curl_setopt($this->_curl, CURLOPT_HEADER, $this->_options['CURLOPT_HEADER']);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $timeout);
    }

    /**
     * post post方式提交数据
     *
     * @param mixed $data 查询串，支持数组
     * @access public
     * @return string
     */
    public function post($data)
    {
        curl_setopt($this->_curl, CURLOPT_URL, $this->_uri);
        curl_setopt($this->_curl, CURLOPT_POST, 1);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $data);

        return $this->_request();
    }

    /**
     * file_post post方式提交数据
     *
     * @param array $data
     * @access public
     * @return string
     */
    public function filePost($data)
    {
        curl_setopt($this->_curl, CURLOPT_URL, $this->_uri);
        curl_setopt($this->_curl, CURLOPT_POST, 1);
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $data);

        return $this->_request();
    }

    /**
     * get get方式请求数据
     *
     * @param mixed $data 查询串，支持数组
     * @access public
     * @return string
     */
    public function get($data)
    {
        $url = $this->_uri;
        if (!empty ($data)) {
            $url .= strpos($url, '?') === false ? '?' : '&';
            $url .= is_array($data) ? http_build_query($data) : $data;
        }

        curl_setopt($this->_curl, CURLOPT_URL, $url);
        return $this->_request();
    }

    /**
     * _request 发送请求
     *
     * @access public
     * @return string
     * @throws \Exception
     */
    public function _request()
    {
        $response = curl_exec($this->_curl);
        $errno = curl_errno($this->_curl);
        if ($errno != 0) {
            throw new \Exception (curl_error($this->_curl), $errno);
        }
        // var_dump($response);exit;
        return $response;
    }

    /**
     * info 返回信息
     *
     * @access public
     * @return array
     */
    public function info()
    {
        return curl_getinfo($this->_curl);
    }

    /**
     * close 关闭连接
     *
     * @access public
     * @return void
     */
    public function close()
    {
        if (is_resource($this->_curl)) {
            curl_close($this->_curl);
            $this->_curl = null;
        }
    }

    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
