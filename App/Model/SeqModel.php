<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des: 资讯详情获取
 */

namespace App\Model;

use App\Utils\Factory;

class  SeqModel extends model
{
    protected static $pre = 'mb_topics_seq_'; //mb_topics_seq_605443568
    protected static $zixiun_url = "http://news.10jqka.com.cn/siteapi/thsapp_account_fupan/news/?track=accountfupan&seqs=";
    protected static $snslive_url = "http://t.10jqka.com.cn/api.php?method=post.getPostDataForCa&pid=";

    public static function get($seq, $type)
    {
        $conn = Factory::getRedis();
        $key = SeqModel::$pre . $seq;
        $res = $conn->get($key);
        if (!$res) {
            $finalData = SeqModel::curlData($type, $seq);
            $content = json_encode($finalData);
            SeqModel::set($seq, $content);
        }
        return json_decode($res, true);
    }

    public static function set($seq, $content)
    {
        $conn = Factory::getRedis();
        $key = SeqModel::$pre . $seq;
        $res = $conn->set($key, $content);
        return $res;
    }

    public static function curlData($type, $seq)
    {
        $url = ($type == "snslivepost") ? SeqModel::$snslive_url . $seq : SeqModel::$zixiun_url . $seq;
        $curl = Factory::getCurlTool();
        $curl->open($url);
        try {
            $data = $curl->get();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $data = json_decode($data, true);
        if ($type == 'news') $finalData = $data['data'][$seq];
        if ($type == 'snslivepost') {
            $finalData['seq'] = $seq;
            $finalData['ctime'] = date("Y-m-d H:i:s", $data['result']['mtime']);
            $finalData['rtime'] = date("Y-m-d H:i:s", $data['result']['mtime']);
            $finalData['title'] = $data['result']['title'];
            $finalData['source'] = $data['result']['nickname'];
            $finalData['stockcode'] = "";
            $finalData['summ'] = $data['result']['content'];
            $finalData['url'] = $data['result']['src'];
        }
        $finalData['newstype'] = $type;
        return $finalData;
    }
}