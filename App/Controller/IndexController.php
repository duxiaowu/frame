<?php
namespace App\Controller;
define('__SCRIPT', 'index');
use App\Model\Redis;
use App\Model\Mysql;
use App\Model\Mongo;
use App\Utils\Curl;
class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $data =  Redis\indexModel::get();
//        var_dump($data);
       $data = Mysql\indexModel::getList();
        return $data;
//        $data = Mongo\indexModel::getList();
//        var_dump($data);
    }
}