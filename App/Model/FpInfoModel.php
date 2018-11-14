<?php
/**
 * Created by PhpStorm.
 * User: duweibin
 * Date: 2018/10/10
 * des: cbas获取复盘信息,存mongodb，并取出来
 */

namespace App\Model;

use App\Utils\Factory;
//use MobilePHPLib\Ths\TradeDate\TradeDateUtil;
//use MobilePHPLib\Ths\TradeDate\TradeDateException;
class  FpInfoModel extends model
{
    protected static $table = 'topic_';
    /**
     * @var string 取帖子详情接口
     */
    protected static $sns_url = 'http://t.10jqka.com.cn/api.php?method=newgroup.batchGetContentByPids&return=json&pids=';
    /*
     *   =group_post_519548866,group_post_519545899 取点赞数接口
     */
    protected static $sns_sta_url = 'http://bbsclick.10jqka.com.cn/getlist?log=1&field=likes&app=sns&key=';

    /**
     * @param $userid
     * @return bool|\MongoDB\Driver\WriteResult
     * 从cbas取最新数据并存mongo
     */

    public static function addnew($userid)
    {
        $data = self::getNewInfo($userid);
        if (empty($data)) return false;
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid, -2);
        $res = $Mongo->insert($table, $data);
        return $res;
    }

    /**
     * @param $userid
     * @param $data
     * @return \MongoDB\Driver\WriteResult
     * 更新数据暂用不到
     */
    public static function update($userid, $data)
    {
        $condition = ['userid' => $userid];
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid, -2);
        $res = $Mongo->update($table, $condition, $data);
        return $res;
    }

    /**
     * @param $userid
     * @return \MongoDB\Driver\WriteResult
     * 删除数据暂用不到
     */
    public static function delete($userid)
    {
        $condition = ['userid' => $userid];
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid, -2);
        $res = $Mongo->delete($table, $condition);
        return $res;
    }

    /**
     * @param $userid
     * @param string $id
     * @return bool
     * 取最新十条数据，或某个ojectId之前的历史数据
     */
    public static function getList($userid, $objectId = '')
    {
        //$util = TradeDateUtil::getInstance();
       // $prevDate = $util->getPrevTradeDate(date("Y-m-d"), 2);
       // $limitTime = strtotime($prevDate);
        $arr = array();
        $postParm = array();
        $Mongo = Factory::getMongo();
        $table = FpInfoModel::$table . substr($userid, -2);
        $filter = ['userid' => $userid];
        if (!empty($objectId)) {
            $filter['_id'] = ['$lt' => new \MongoDB\BSON\ObjectID($objectId)];
        }
        //$filter['time'] = ['$gt' => $limitTime];
        $option = [
            'projection' => [
                'data' => 1,
                'type' => 1,
                'time' => 1,
            ],
            'limit' => 10,
            'sort' => [
                'time' => -1
            ],
        ];
        $res = $Mongo->select($table, $filter, $option);
        if (empty($res)) {
            return false;
        }
        foreach ($res as $value) {
            $nextId = $value->_id;
            $tmpArr = array(
                'id' => $value->data,
                'type' => $value->type,
                // 'time' => $value->time,
            );
            if ($tmpArr['type'] == 'zg') {
                $tmpArr['info'] = ZgModel::get($tmpArr['id'], date("md", $value->time));
            }
            if ($tmpArr['type'] == 'seq') {
                $tmpArr['info'] = SeqModel::get($tmpArr['id']);
            }
            if (empty($tmpArr['info']) && ($tmpArr['type'] == 'zg' || $tmpArr['type'] == 'seq')) continue;
            if (empty($tmpArr['info']['url']) && $tmpArr['type'] == 'seq') continue;
            if (in_array($tmpArr['type'], array('pt_post', 'jx_post'))) {
                $postParm[] = $tmpArr['id'];
                $likePostParm[] = 'group_post_' . $tmpArr['id'];
            }
            $arr[$tmpArr['id']] = $tmpArr;
        }
        $arr = self::dealSNSData($postParm, $likePostParm, $arr);
        $returnData['data'] = array_values($arr);
        $returnData['nextId'] = strval($nextId);
        return $returnData;
    }

    /**
     * @param $postParm
     * @param $likePostParm
     * @param $arr
     * @return bool
     * 从sns取帖子详情
     */
    protected static function dealSNSData($postParm, $likePostParm, $arr)
    {
        $postParmStr = implode(',', $postParm);
        $likePostParmStr = implode(',', $likePostParm);
        $url = self::$sns_url . $postParmStr;
        $curl = Factory::getCurlTool();
        $curl->open($url);
        try {
            $data = $curl->get();
        } catch (Exception $e) {
            self::webLog($e->getMessage());
            return false;
        }
        $curl = Factory::getCurlTool();
        $curl->open(self::$sns_sta_url . $likePostParmStr);
        try {
            $likeData = $curl->get();
        } catch (Exception $e) {
            self::webLog($e->getMessage());
        }
        $likeData = json_decode($likeData, true);
        $postData = json_decode($data, true);
        if ($postData['errorCode'] != 0) {
            self::webLog($data);
            return false;
        }
        foreach ($postData['result'] as $postInfo) {
            unset($postInfo['extend']);
            unset($postInfo['from']);
            unset($postInfo['id']);
            unset($postInfo['type']);
            unset($postInfo['isTz']);
            unset($postInfo['isV']);
            unset($postInfo['medal']);
            unset($postInfo['valid']);
            $postInfo['flag'] = intval($likeData['result']['group_post_' . $postInfo['pid']]['flag']);
            $postInfo['likes'] = intval($likeData['result']['group_post_' . $postInfo['pid']]['likes']);
            $infoArr = $postInfo;
            $arr[$postInfo['pid']]['info'] = $infoArr;
        }
        return $arr;
    }

    /**
     * @param $userid
     * @return array
     * 从CBAS取某个用户最新数据
     */
    protected static function getNewInfo($userid)
    {
        $arr = array();
        $url = sprintf(CBAS_URL, $userid);
        $openApi = Factory::getOpenApi('auth');
        $data = $openApi->getData($url);
        if (in_array($data['status_code'], array('-104', '-1', '-105', '-106'))) {
            $openApi->curlToken();
            $data = $openApi->getData($url);
        }
        $data = $data['data'];
        if (empty($data)) return array();
        if (!isset($data['jx_post'])) $data['jx_post'] = array();
        if (!isset($data['pt_post'])) $data['pt_post'] = array();
        $postArr = array_merge($data['pt_post'], $data['jx_post']);
        array_multisort(array_column($postArr, 'time'), SORT_DESC, $postArr);
        unset($data['jx_post']);
        unset($data['pt_post']);
        if (!empty($postArr)) $newData['pt_post'] = $postArr;
        if (isset($data['seq'])) $newData['seq'] = $data['seq'];
        if (isset($data['zg'])) $newData['zg'] = $data['zg'];
        unset($data);
        foreach ($newData as $key => $value) {
            if ($key == 'zg' && date("H") < 15) continue;
            foreach ($value as $info) {
                $data_id = ($key == "zg") ? $info['code'] : $info['id'];
                if ($key == 'seq' && !SeqModel::check($data_id)) {
                    SeqModel::set($data_id, $info['newstype']);
                }

                if ($key == 'zg' && !ZgModel::get($data_id)) {
                    $info['time'] = time();
                    $res = ZgModel::set($data_id, json_encode($info, 320));
                }
                $arr[] = array(
                    "userid" => $userid,
                    "type" => $key,
                    "time" => microtime(true),
                    "data" => $data_id,
                );
            }
        }
        return $arr;
    }
}