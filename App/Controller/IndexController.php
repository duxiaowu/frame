<?php
namespace App\Controller;
define('__SCRIPT', 'index');
use App\Model;
class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
       // $data = Model\FpInfoModel::addnew("352918378");
      //  $data = ['userid' => '352918378'];
       // $data = Model\FpInfoModel::update("3555578", $data);
       // $data = Model\FpInfoModel::delete("352918378");
        $data = Model\FpInfoModel::getList("352918378");
        return $data;
    }
}