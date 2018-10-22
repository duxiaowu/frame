<?php
namespace App\Controller;
use App\Utils\Log;
abstract class BaseController{
    public function __construct()
    {

    }

    public function getSeq() {

    }
    public function log($msg) {
        $log = new Log();
        $log->write($msg);
    }
}