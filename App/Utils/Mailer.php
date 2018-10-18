<?php
/**
 * 邮件发送工具类
 *
 */

namespace App\Utils;

class Mailer
{
    protected $sendUrl  = ''; // 邮件发送接口地址
    protected $params   = null; // 邮件发送参数
    protected $curlUtil = null; // Curl工具
    protected $logger   = null; // 日志记录工具

    /**
     * 构造方法
     *
     * @param string $sendUrl 邮件发送接口地址
     */
    public function __construct($sendUrl)
    {
        $this->curlUtil = new CurlUtil();
        $this->setSendUrl($sendUrl)->clearParams();
    }

    /**
     * 执行邮件发送
     *
     * @return bool
     */
    public function send()
    {
        // 组装邮件参数
        $params = array(
            'recv'     => json_encode($this->params['recv']),
            'cc'       => json_encode($this->params['cc']),
            'subject'  => iconv('utf-8', 'gbk', $this->params['subject']),
            'content'  => iconv('utf-8', 'gbk', $this->params['content']),
            'fromname' => iconv('utf-8', 'gbk', $this->params['fromname']),
            'isall'    => $this->params['isall'],
        );
        // 执行网络请求
        $this->curlUtil->open($this->sendUrl);
        try {
            $res = $this->curlUtil->post($params);
        } catch (\Exception $e) {
            return false;
        }
        if ($res == '1') {
            return true;
        }
        return false;
    }

    /**
     * 设置邮件发送地址
     *
     * @param string $sendUrl 邮件发送接口地址
     * @return self
     */
    public function setSendUrl($sendUrl)
    {
        $this->sendUrl = $sendUrl;
        return $this;
    }

    /**
     * 设置邮件接收者
     *
     * @param array $revs 接收用户数组
     * @return self
     */
    public function setReceivers(array $revs)
    {
        $this->params['recv'] = $revs;
        return $this;
    }

    /**
     * 设置抄送用户
     *
     * @param array $cc 抄送用户数组
     * @return $this
     */
    public function setCC(array $cc)
    {
        $this->params['cc'] = $cc;
        return $this;
    }

    /**
     * 设置主题
     *
     * @param string $subject 主题
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->params['subject'] = $subject;
        return $this;
    }

    /**
     * 设置内容
     *
     * @param string $content 内容
     * @return $this
     */
    public function setContent($content)
    {
        $this->params['content'] = $content;
        return $this;
    }

    /**
     * 设置发件人称呼
     *
     * @param string $fromName 发件人称呼
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->params['fromname'] = $fromName;
        return $this;
    }

    /**
     * 设置是否全部发送
     * 增加参数isall。其中加isall为1时，可以将发送人、抄送用户一起发送。为空则单个发送。
     * 默认为便利发送所有的用户进行单个发送（原因：为了防止一个用户离职，导致所有的用户发送失败）
     *
     * @param int|string $isAll 是否全部发送 1-表示是, 空('')表示不是
     * @return self
     */
    public function setIsAll($isAll)
    {
        $this->params['isall'] = $isAll;
        return $this;
    }

    /**
     * 清空发件参数
     *
     * @return self
     */
    public function clearParams()
    {
        $this->params = array(
            'recv'     => array(), // 接收用户
            'cc'       => array(), // 抄送用户
            'subject'  => '', // 主题
            'content'  => '', // 内容
            'fromname' => '', // 发送人称呼
            'isall'    => '',
        );
        return $this;
    }
}