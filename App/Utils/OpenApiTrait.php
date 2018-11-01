<?php
/**
 * openapi工具类
 */
namespace App\Utils;

trait OpenApiTrait
{

    protected $header = null;

    public function getData($url, $filed = '')
    {
        $curl = Factory::getCurlTool();
        $curl->open($url, $this->header);
        try {
            $data = $curl->post($filed);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $arr = json_decode($data, true);
        return $arr;
    }


}
