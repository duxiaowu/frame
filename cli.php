<?php
require_once __DIR__ . '/global.php';
require_once _ROOT . '/autoload.php';
$con = $argv[1];
$act = $argv[2];
$cli = null;
switch ($con) {
    case 'index':
        $cli = new \App\Cli\IndexCli();
        break;
}

if ($cli instanceof App\Cli\BaseCli) {
    $cli->$act();
}



