<?php

namespace App\Controller;

use App\Utils\Factory;

abstract class BaseController
{
    public function __construct()
    {

    }

    public function log($msg)
    {
        $log = Factory::getLogTool('web');
        $log->write(__CLASS__ . '|' . __METHOD__ . $msg);
    }
}