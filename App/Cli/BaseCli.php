<?php

namespace App\Cli;

use App\Utils\Factory;

class BaseCli
{
    public function log($msg)
    {
        $log = Factory::getLogTool('cron');
        $log->write($msg);
    }

}